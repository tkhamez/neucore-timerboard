<?php

namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Admin
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->eventRepository = $container->get(EventRepository::class);
    }

    public function index(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        echo 'Admin';
        return $response;
    }
}
