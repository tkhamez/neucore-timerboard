<?php

namespace Brave\TimerBoard\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

    public function board(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        echo 'TimerBoard<br><a href="/logout">logout</a>';

        return $response;
    }
}