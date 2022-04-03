<?php

namespace Src\PlayerMatcher\Adapters\DbRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    public function create(): EntityManager
    {
        $inDevMode                 = (bool)$_ENV['DEV_MODE'];
        $proxyDir                  = null;
        $cache                     = null;
        $useSimpleAnnotationReader = false;

        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entities'], $inDevMode, $proxyDir, $cache,
            $useSimpleAnnotationReader);

        $conn = [
            'dbname'   => $_ENV['DB_NAME'],
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'host'     => $_ENV['DB_HOST'],
            'driver'   => 'pdo_mysql',
        ];

        return EntityManager::create($conn, $config);
    }
}