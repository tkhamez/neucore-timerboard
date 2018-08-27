<?php
namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\Repository\EventRepository;
use Brave\TimerBoard\Security;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

abstract class BaseController
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var Security
     */
    protected $security;

    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->eventRepository = $container->get(EventRepository::class);
        $this->security = $container->get(Security::class);
    }
}
