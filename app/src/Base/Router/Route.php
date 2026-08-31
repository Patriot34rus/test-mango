<?php
declare(strict_types=1);

namespace App\Base\Router;

class Route
{
    public function __construct(
        private readonly string $method,
        private readonly string $urlPattern,
        private readonly string $controller,
        private readonly string $action,
        private readonly array $parameters = []
    ){

    }

    public function getUrlPattern(): string
    {
        return $this->urlPattern;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

}
