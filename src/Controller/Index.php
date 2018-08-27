<?php

namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\Repository\EventRepository;
use Brave\TimerBoard\View;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Index
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->eventRepository = $container->get(EventRepository::class);
    }

    public function board(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $events = $this->eventRepository->findAll();

        $view = new View(ROOT_DIR . '/views/index.php');
        $view->addVar('events', $events);

        $response->write($view->getContent());

        return $response;
    }
}
