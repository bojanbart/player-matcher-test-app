<?php

declare(strict_types=1);

namespace Src;

use Src\PlayerMatcher\Adapters\Api\RouterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

class App
{
    private \Symfony\Component\Routing\Router $router;

    public function run(): void
    {
        $this->registerRoutes()
            ->dispatch();
    }

    private function registerRoutes(): App
    {
        $this->router = (new RouterFactory())->create();

        return $this;
    }

    private function dispatch(): void
    {
        $requestContext = new RequestContext();
        $requestContext->fromRequest(Request::createFromGlobals());

        try
        {
            $parameters = $this->router->match($requestContext->getPathInfo());
        }
        catch (ResourceNotFoundException $e)
        {
            $this->notFound();
        }

        if (empty($parameters))
        {
            $this->notFound();
        }

        $parametersToPass = array_filter($parameters, function ($key)
        {
            return !str_starts_with($key, '_');
        }, ARRAY_FILTER_USE_KEY);

        $controller = explode('::', $parameters['_controller']);

        if (empty($parametersToPass))
        {
            call_user_func([new $controller[0], $controller[1]]);
        }
        else
        {
            call_user_func([new $controller[0], $controller[1]], ...$parametersToPass);
        }
    }

    private function notFound(): void
    {
        (new Response('Not found', Response::HTTP_NOT_FOUND, ['content-type' => 'text/html']))->send();
        exit();
    }
}