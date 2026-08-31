<?php
declare(strict_types=1);

namespace App\Base\Controller;

use App\Base\Router\Route;

class Request
{
    public function __construct(
        private readonly string $uri,
        private readonly string $method,
        private array $parameters = []
    ){}

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParameters(): array{
        return $this->parameters;
    }

    public function getParameter(string $key){
        return $this->parameters[$key] ?? null;
    }

    public function addParameters(array $params){
        $this->parameters = array_merge($this->parameters, $params);
    }
}