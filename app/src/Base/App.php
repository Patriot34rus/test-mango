<?php
declare(strict_types=1);

namespace App\Base;

use App\Base\Controller\ControllerFactory;
use App\Base\Controller\IController;
use App\Base\Controller\Request;
use App\Base\Controller\Response;
use App\Base\Router\Route;
use App\Base\Router\RouteResolver;
use App\Exception\AppRuntimeException;

final class App
{
    public function __construct(
        private readonly Config $config,
        private readonly RouteResolver $routerResolver,
        private readonly ControllerFactory $controllerFactory
    ){}

    public function getConfig():Config
    {
        return $this->config;
    }

    public function run(Request $request): Response
    {
        $route = $this->routerResolver->resolve($request->getUri(), $request->getMethod());
        $action = $route->getAction();
        $controller = $this->createController($route);

        if (!is_callable([$controller, $action])) {
            throw new AppRuntimeException(sprintf(
                'Controller action "%s::%s" is not callable.',
                $controller::class,
                $action
            ), 404);
        }

        $request->addParameters($route->getParameters());

        // тут должен  быть валидатор и защита

        return $controller->{$action}($request);
    }

    private function createController(Route $route): IController
    {
        $controllerClass = $route->getController();

        if (!class_exists($controllerClass)) {
            throw new AppRuntimeException(sprintf(
                'Controller class "%s" does not exist.',
                $controllerClass
            ), 404);
        }

        return $this->controllerFactory->createController($controllerClass);
    }
}
