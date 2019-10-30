<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController
{
    /**
     * @return Response
     */
    public function status() : Response
    {
        return new JsonResponse([
            'code' => 200,
            'result' => 'Ok'
        ]);
    }
}