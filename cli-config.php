<?php

require_once "./vendor/autoload.php";
require_once "./src/PlayerMatcher/Adapters/DbRepository/EntityManagerFactory.php";

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotEnv->safeLoad();

$entityManager = (new \Src\PlayerMatcher\Adapters\DbRepository\EntityManagerFactory())->create();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);