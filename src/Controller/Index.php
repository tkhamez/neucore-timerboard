<?php

namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\View;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Index
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function board(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $view = new View(ROOT_DIR . '/views/index.php');
        $response->write($view->getContent());

        return $response;
    }
}
