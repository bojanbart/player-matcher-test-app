<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

class RouterFactory
{
    public function create(): Router
    {
        $fileLocator = new FileLocator([__DIR__ . '/config']);

        $requestContext = new RequestContext();
        $requestContext->fromRequest(Request::createFromGlobals());

        return new Router(new YamlFileLoader($fileLocator), 'routes.yml',
            ['cache_dir' => __DIR__ . '/../../../../tmp/cache'], $requestContext);
    }
}