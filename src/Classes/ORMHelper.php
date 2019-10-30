<?php

namespace App\Classes;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class ORMHelper
{
    /**
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getEntityManager(): EntityManager
    {
        $config = Setup::createAnnotationMetadataConfiguration([BASEPATH . '/src/Entity'], true, null, null, false);
        $dbConfig = self::getDbConfig();
        return EntityManager::create($dbConfig, $config);
    }

    /**
     * @return array
     */
    private static function getDbConfig(): array
    {
        return [
            'driver' => 'pdo_mysql',
            'host' => getenv('DB_HOST'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
        ];
    }
}