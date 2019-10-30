<?php

namespace App\Controller;

use App\Exception\ApiBadRequestException;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController
{
    /** @const DEFAULT_LIMIT int*/
    private const DEFAULT_LIMIT = 20;
    /** @const DEFAULT_OFFSET int*/
    private const DEFAULT_OFFSET = 0;
    /** @const MAX_LIMIT int*/
    private const MAX_LIMIT = 100;

    /**
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        $limit = (int)$request->get('limit', self::DEFAULT_LIMIT);
        $offset = (int)$request->get('offset', self::DEFAULT_OFFSET);

        if ($limit < 0) {
            throw  new ApiBadRequestException('Limit must be a positive integer or zero');
        }
        if ($limit > self::MAX_LIMIT) {
            throw  new ApiBadRequestException('Limit must not exceed ' . self::MAX_LIMIT);
        }
        if ($offset < 0) {
            throw  new ApiBadRequestException('Offset must be a positive integer or zero');
        }

        $productService = new ProductService();
        $data = $productService->getList($limit, $offset);

        return new JsonResponse([
            'code' => 200,
            'result' => $data['result'],
            'meta' => [
                'count' => $data['count'],
            ],
        ]);
    }
}