<?php
declare(strict_types=1);

namespace App\Base\Router;

use App\Exception\AppRuntimeException;
use App\Exception\ErrorAppRuntimeException;
use RuntimeException;

class RouteResolver
{
    public function __construct(private readonly array $routes){

    }

    public function resolve(string $requestUri, string $requestMethod): Route
    {
        $requestPath = parse_url($requestUri, PHP_URL_PATH);

        if (!is_string($requestPath)) {
            throw new ErrorAppRuntimeException(sprintf('Invalid request URI "%s".', $requestUri), 400);
        }

        $requestPath = trim($requestPath, '/');
        $requestMethod = strtolower($requestMethod);

        foreach ($this->routes as $urlPattern => $routeDefinition) {
            if (!is_array($routeDefinition) || count($routeDefinition) !== 3) {
                throw new AppRuntimeException(sprintf(
                    'Invalid definition for route "%s".',
                    $urlPattern
                ), 400);
            }

            [$method, $controller, $action] = $routeDefinition;

            if (strtolower((string) $method) !== $requestMethod) {
                continue;
            }

            $parameters = $this->matchPath((string) $urlPattern, $requestPath);

            if ($parameters === null) {
                continue;
            }

            return new Route(
                (string) $method,
                (string) $urlPattern,
                (string) $controller,
                (string) $action,
                $parameters
            );
        }

        throw new ErrorAppRuntimeException(sprintf(
            'Route for "%s %s" was not found.',
            strtoupper($requestMethod),
            $requestPath
        ), 404);
    }

    private function matchPath(string $urlPattern, string $requestPath): ?array
    {
        $patternParts = $this->splitPath($urlPattern);
        $requestParts = $this->splitPath($requestPath);

        if (count($patternParts) !== count($requestParts)) {
            return null;
        }

        $parameters = [];

        foreach ($patternParts as $index => $patternPart) {
            $requestPart = $requestParts[$index];

            if (str_starts_with($patternPart, ':')) {
                $parameterName = substr($patternPart, 1);

                if ($parameterName === '' || $requestPart === '') {
                    return null;
                }

                $parameters[$parameterName] = rawurldecode($requestPart);
                continue;
            }

            if ($patternPart !== $requestPart) {
                return null;
            }
        }

        return $parameters;
    }

    private function splitPath(string $path): array
    {
        $path = trim($path, '/');

        return $path === '' ? [] : explode('/', $path);
    }
}
