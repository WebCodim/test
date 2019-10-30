<?php

namespace App\Service;

use App\App;
use Doctrine\ORM\EntityManager;

/**
 * Class AbstractService
 * @package App\Service
 */
abstract class AbstractService
{
    /** @var $entityManager EntityManager */
    protected $entityManager;

    /**
     * AbstractService constructor.
     */
    public function __construct()
    {
        $this->entityManager = App::getInstance()->getEntityManager();
    }
}