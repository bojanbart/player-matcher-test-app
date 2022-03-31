<?php

declare(strict_types=1);

namespace Src;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Src\PlayerMatcher\Adapters\Api\RouterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

class App
{
    private \Symfony\Component\Routing\Router $router;
    private ContainerInterface                $container;

    public function run(): void
    {
        $this->registerContainer()
            ->registerRoutes()
            ->dispatch();
    }

    private function registerContainer(): App
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(__DIR__ . '/PlayerMatcher/DependencyInjection/config.php');

        $this->container = $containerBuilder->build();

        return $this;
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
        catch (MethodNotAllowedException $e)
        {
            $this->notAllowed();
        }

        if (empty($parameters))
        {
            $this->notFound();
        }

        $parametersToPass = array_filter($parameters, function ($key)
        {
            return !str_starts_with($key, '_');
        }, ARRAY_FILTER_USE_KEY);

        $controllerInfo  = explode('::', $parameters['_controller']);
        $controllerClass = substr($controllerInfo[0], 1);
        $controller      = $this->container->get($controllerClass);

        if (empty($parametersToPass))
        {
            call_user_func([$controller, $controllerInfo[1]]);
        }
        else
        {
            call_user_func([$controller, $controllerInfo[1]], ...$parametersToPass);
        }
    }

    private function notFound(): void
    {
        (new Response('Not found', Response::HTTP_NOT_FOUND, ['content-type' => 'text/html']))->send();
        exit();
    }

    private function notAllowed(): void
    {
        (new Response('Not allowed', Response::HTTP_METHOD_NOT_ALLOWED, ['content-type' => 'text/html']))->send();
        exit();
    }
}