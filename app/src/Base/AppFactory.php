<?php
declare(strict_types=1);

namespace App\Base;

use App\Base\Controller\ControllerFactory;
use App\Base\Controller\Request;
use App\Base\Db\DbConnection;
use App\Base\Db\IDbConnection;
use App\Base\Router\RouteResolver;

class AppFactory
{
    private Config $config;
    private ?IDbConnection $dbConnection;

    public  function __construct(
        private readonly string $configPath,
        private readonly string $routerPath,

    ){
        $this->config = $this->createConfig();
        $this->dbConnection = null;
    }

    public static function createByDefault(): self{
        $config = getenv('ROOT_DIR').getenv('CONFIG_PATH');
        $route = getenv('ROOT_DIR').getenv('ROUTE_PATH');
        return new self($config, $route);
    }
    public function createApp(): App{
        return new App(
            $this->config,
            $this->createRouteResolver(),
            $this->createControllerFactory()
        );
    }

    public function createRequest():Request{
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestParameters = array_merge($_POST, $_GET);
        return new Request($requestUri, $requestMethod, $requestParameters);
    }

    private function createRouteResolver(): RouteResolver{
        $routes = include $this->routerPath;
        return new RouteResolver($routes);
    }

    private function createConfig(): Config{
        return Config::createFromFile($this->configPath);
    }

    public function createDbConnection(): DbConnection{
        if($this->dbConnection !== null){
            return $this->dbConnection;
        }
        $config = $this->config->getConfigPart('db');
        $this->dbConnection = new DbConnection($config);

        return $this->dbConnection;
    }

    public function createServiceFactory(): ServiceFactory{
        return new ServiceFactory($this->createDbConnection());
    }

    private function createControllerFactory(): ControllerFactory{
        return new ControllerFactory($this->createServiceFactory());
    }
}
