<?php

namespace App\Repository;

use App\App;
use App\Entity\Order;
use App\Entity\Product;

class Factory
{
    /**
     * @return OrderRepository
     */
    public static function getOrderRepository(): OrderRepository
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = App::getInstance()->getEntityManager()->getRepository(Order::class);
        return $orderRepository;
    }

    /**
     * @return ProductRepository
     */
    public static function getProductRepository(): ProductRepository
    {
        /** @var ProductRepository $productRepository */
        $productRepository = App::getInstance()->getEntityManager()->getRepository(Product::class);
        return $productRepository;
    }
}