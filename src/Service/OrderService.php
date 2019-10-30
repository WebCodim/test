<?php

namespace App\Service;


use App\Classes\Gateway\IGateway;
use App\Entity\Order;
use App\Exception\ApiBadRequestException;
use App\Repository\Factory;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\ProductRepository;
use App\Entity\Product;

/**
 * Class OrderService
 * @package App\Service
 */
class OrderService extends AbstractService
{
    /**
     * @param array $productIds
     * @return int
     * @throws \Exception
     */
    public function create(array $productIds): int
    {
        $productRepository = Factory::getProductRepository();

        $products = $productRepository->getProductsByIds($productIds);

        if (count($products) < count($productIds)) {
            foreach ($products as $product) {
                unset($productIds[array_search($product->getId(), $productIds)]);
            }
            throw new ApiBadRequestException('Incorrect products : ' . implode(',', $productIds));
        }

        $this->entityManager->beginTransaction();
        try {
            $order = (new Order())
                ->setProducts(new ArrayCollection($products))
                ->setAmount($this->getSumPrices($products));

            $this->entityManager->persist($order);
            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (\Throwable $t) {
            $this->entityManager->rollBack();
            throw new \RuntimeException($t->getMessage(), $t->getCode(), $t);
        }

        return $order->getId();
    }

    /**
     * @param array $products
     * @return float
     */
    public function getSumPrices(array &$products): float
    {
        $sum = (float)0;
        foreach ($products as $product) {
            $sum += $product->getPrice();
        }
        return $sum;
    }

    /**
     * @param int $orderId
     * @param float $amount
     * @param IGateway $gateway
     */
    public function pay(int $orderId, float $amount, IGateway $gateway)
    {
        $this->entityManager->beginTransaction();
        try {

            $orderRepoRepository = Factory::getOrderRepository();

            /** @var Order|null $order */
            $order = $orderRepoRepository->find($orderId);
            if (!$order) {
                throw new ApiBadRequestException('Order not found');
            }

            if ($order->getStatus() !== Order::STATUS_NEW) {
                throw new ApiBadRequestException('Order already paid');
            }

            if ($order->getAmount() !== $amount) {
                throw new ApiBadRequestException('Invalid amount');
            }

            if (!$gateway->process()) {
                throw new \RuntimeException('Processing error');
            }

            $order->setStatus(Order::STATUS_PAID);
            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (ApiBadRequestException $e) {
            $this->entityManager->rollback();
            throw new ApiBadRequestException($e->getMessage());
        } catch (\Throwable $t) {
            $this->entityManager->rollback();
            throw new \RuntimeException($t->getMessage(), $t->getCode(), $t);
        }
    }
}