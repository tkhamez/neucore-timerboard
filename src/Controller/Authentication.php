<?php
namespace Brave\TimerBoard\Controller;

use Brave\Sso\Basics\AuthenticationController;
use Brave\TimerBoard\RoleProvider;
use Brave\TimerBoard\SessionHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Authentication extends AuthenticationController
{
    /**
     * @var SessionHandler
     */
    private $sessionHandler;

    /**
     * @var RoleProvider
     */
    private $roleProvider;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->sessionHandler = $this->container->get(SessionHandler::class);
        $this->roleProvider = $this->container->get(RoleProvider::class);
    }

    public function callback(ServerRequestInterface $request, Response $response, array $arguments): ResponseInterface
    {
        try {
            parent::auth($request, $response, $arguments);
        } catch(\Exception $e) {
            # TODO log?
        }

        $this->roleProvider->clear();

        return $response->withRedirect('/');
    }

    public function logout(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        $this->sessionHandler->clear();

        return $response->withRedirect('/login');
    }
}
