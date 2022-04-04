<?php

namespace Src\PlayerMatcher\Adapters\DbRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;

class EntityManagerFactory
{
    public function create(): EntityManager
    {
        $inDevMode                 = (bool)$_ENV['DEV_MODE'];
        $proxyDir                  = null;
        $cache                     = new PhpFilesAdapter('doctrine_metadata', 3600, __DIR__ . '/../../../../tmp/cache');
        $useSimpleAnnotationReader = false;

        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entities'], $inDevMode, $proxyDir, null,
            $useSimpleAnnotationReader);
        $config->setMetadataCache($cache);

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