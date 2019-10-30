<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\Factory;

/**
 * Class ProductService
 * @package App\Service
 */
class ProductService extends AbstractService
{
    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getList(int $limit , int $offset ): array
    {
        $productRepository = Factory::getProductRepository();
        $page = $productRepository->getLimitOffset($limit, $offset);
        /**@var Product[] $result */
        $result = $page->getQuery()->getResult();

        $data = [];
        foreach ($result as $item) {
            $data[] = $item->asArray();
        }

        return [
            'result' => $data,
            'count' => $page->count(),
        ];
    }
}