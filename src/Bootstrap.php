<?php
namespace Brave\TimerBoard;

use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

/**
 *
 */
class Bootstrap
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Bootstrap constructor
     */
    public function __construct()
    {
        if (is_readable(ROOT_DIR . '/.env')) {
            $dotEnv = new Dotenv(ROOT_DIR);
            $dotEnv->load();
        }

        $this->container = new \Slim\Container(require_once(ROOT_DIR . '/config/container.php'));
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return \Slim\App
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function enableRoutes()
    {
        /** @var \Slim\App $app */
        $routesConfigurator = require_once(ROOT_DIR . '/config/routes.php');
        $app = $routesConfigurator($this->container);

        // uncomment these if you need groups from Brave Core to secure routes
        $app->add(new \Tkhamez\Slim\RoleAuth\SecureRouteMiddleware(
            include ROOT_DIR . '/config/security.php',
            ['redirect_url' => '/login']
        ));
        $app->add(new \Tkhamez\Slim\RoleAuth\RoleMiddleware($this->container->get(RoleProvider::class)));

        $app->add(new \Slim\Middleware\Session([
            'name' => 'brave_service',
            'autorefresh' => true,
            'lifetime' => '1 hour'
        ]));

        return $app;
    }
}
