<?php

namespace App\Controller;

use App\Classes\Gateway\YandexGateway;
use App\Exception\ApiBadRequestException;
use App\Service\OrderService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 * @package App\Controller
 */
class OrderController
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        $products = $request->get('products', []);

        if (!is_array($products)) {
            throw new ApiBadRequestException('Products mast be array');
        }

        $orderService = new OrderService();
        $orderId = $orderService->create($products);

        return new JsonResponse([
            'code' => 200,
            'result' => [
                'order_id' => $orderId,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function pay(Request $request): Response
    {
        $orderId = $request->get('order_id', null);
        $amount = $request->get('amount', null);

        if (is_null($orderId)) {
            throw new ApiBadRequestException('order_id not passed');
        }

        if (is_null($amount)) {
            throw new ApiBadRequestException('amount not passed');
        }

        $orderService = new OrderService();
        $orderService->pay($orderId, $amount, new YandexGateway());

        return new JsonResponse([
            'code' => 200,
            'result' => 'Order has been paid'
        ]);
    }
}
