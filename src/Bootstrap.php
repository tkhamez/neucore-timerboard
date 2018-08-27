<?php
namespace Brave\TimerBoard;

use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Slim\App;
use Tkhamez\Slim\RoleAuth\RoleMiddleware;
use Tkhamez\Slim\RoleAuth\SecureRouteMiddleware;

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
        date_default_timezone_set('UTC');

        if (is_readable(ROOT_DIR . '/.env')) {
            $dotEnv = new Dotenv(ROOT_DIR);
            $dotEnv->load();
        }

        $this->container = new \Slim\Container(require_once(ROOT_DIR . '/config/container.php'));

        error_reporting(E_ALL);
        if ($this->container->get('settings')['app.env'] === 'dev') {
            ini_set('display_errors', '1');
        }
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function run()
    {
        try {
            $app = $this->enableRoutes();
            $this->addMiddleware($app);
            $app->run();
        } catch(\Exception $e) {
            if ($this->container->get('settings')['app.env'] === 'dev') {
                echo '<pre>' . (string)$e . '</pre>';
            } else {
                echo 'Error.';
            }
            // TODO log?
        }
    }

    /**
     * @return \Slim\App
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function enableRoutes()
    {
        /** @var \Slim\App $app */
        $routesConfigurator = require_once(ROOT_DIR . '/config/routes.php');
        $app = $routesConfigurator($this->container);

        return $app;
    }

    /**
     * @param App $app
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function addMiddleware(App $app)
    {
        $security = $this->container->get(Security::class);
        $app->add(new SecureRouteMiddleware($security->readConfig(), ['redirect_url' => '/login']));
        $app->add(new RoleMiddleware($this->container->get(RoleProvider::class)));

        $app->add(new \Slim\Middleware\Session([
            'name' => 'brave_service',
            'autorefresh' => true,
            'lifetime' => '1 hour'
        ]));
    }
}
