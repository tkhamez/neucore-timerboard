<?php
namespace Brave\TimerBoard;

use Dotenv\Dotenv;
use Interop\Container\Exception\ContainerException;
use Psr\Container\ContainerInterface;
use Slim\App;
use Tkhamez\Slim\RoleAuth\RoleMiddleware;
use Tkhamez\Slim\RoleAuth\SecureRouteMiddleware;

class Bootstrap
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string prod or env
     */
    private $appEnv = 'prod';

    public function __construct()
    {
        error_reporting(E_ALL);
        date_default_timezone_set('UTC');

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
     * @return void
     */
    public function run()
    {
        try {
            $appEnv = (string) $this->container->get('settings')['app.env'];
            $this->appEnv = $appEnv === 'dev' ? 'dev' : 'prod';

            if ($this->appEnv === 'dev') {
                ini_set('display_errors', '1');
            } else {
                ini_set('display_errors', '0');
            }

            $app = $this->enableRoutes();
            $this->addMiddleware($app);
            $app->run();
        } catch(ContainerException $e) {
            $this->handleException($e);
        } catch(\Exception $e) {
            $this->handleException($e);
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

    private function handleException(\Exception $e)
    {
        if ($this->appEnv === 'dev') {
            echo '<pre>' . (string)$e . '</pre>';
        } else {
            error_log((string)$e);
            echo 'A website error has occurred. Sorry for the temporary inconvenience.';
        }
    }
}
