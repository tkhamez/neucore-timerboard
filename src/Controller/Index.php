<?php

namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\Repository\EventRepository;
use Brave\TimerBoard\Security;
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

    /**
     * @var Security
     */
    private $security;

    public function __construct(ContainerInterface $container)
    {
        $this->eventRepository = $container->get(EventRepository::class);
        $this->security = $container->get(Security::class);
    }

    public function board(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $events = $this->eventRepository->findAll();

        $view = new View(ROOT_DIR . '/views/index.php');
        $view->addVar('events', $events);
        $view->addVar('isAdmin', $this->security->isAdmin());

        $response->write($view->getContent());

        return $response;
    }
}
