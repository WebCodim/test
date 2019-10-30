<?php

namespace App\Service;

use App\Entity\Product;

/**
 * Class GeneratorService
 * @package App\Service
 */
class GeneratorService extends AbstractService
{
    /**
     * @param int $count
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateProducts(int $count = 20): void
    {
        while (--$count) {
            $product = new Product();
            $product->setName('ITEM-' . mb_strtoupper(substr(md5(random_bytes(10)), 0, 4)));
            $product->setPrice(number_format(random_int(100, 1000), 2, '.',''));
            $this->entityManager->persist($product);
        }
        $this->entityManager->flush();
    }
}