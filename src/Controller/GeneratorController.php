<?php

namespace App\Controller;

use App\Service\GeneratorService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GeneratorController
 * @package App\Controller
 */
class GeneratorController
{
    /**
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateProducts(): Response
    {
        $generatorService = new GeneratorService();
        $generatorService->generateProducts();

        return new JsonResponse([
            'code' => 200,
            'result' => 'Products generated'
        ]);
    }
}
