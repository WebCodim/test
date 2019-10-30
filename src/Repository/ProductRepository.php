<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository extends EntityRepository
{
    /**
     * @param int $limit
     * @param int $offset
     *
     * @return Paginator
     */
    public function getLimitOffset(int $limit, int $offset): Paginator
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from(Product::class, 'p')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param array $productIds
     * @return Product[]
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function getProductsByIds(array $productIds): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->where($qb->expr()->in('p.id', $productIds))
            ->indexBy('p','p.id');

        $products = $qb->getQuery()->getResult();

        $result = [];
        foreach ($productIds as $productId) {
            if (isset($products[$productId])) {
                $result[] = $products[$productId];
            }
        }

        return $result;
    }
}