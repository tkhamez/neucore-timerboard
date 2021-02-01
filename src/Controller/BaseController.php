<?php
namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\Repository\EventRepository;
use Brave\TimerBoard\Repository\SystemRepository;
use Brave\TimerBoard\Security;
use Brave\TimerBoard\View;
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
     * @var SystemRepository
     */
    protected $systemRepository;

    /**
     * @var Security
     */
    protected $security;

    /**
     * @var View
     */
    protected $head;

    /**
     * @var View
     */
    protected $foot;

    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->eventRepository = $container->get(EventRepository::class);
        $this->systemRepository = $container->get(SystemRepository::class);
        $this->security = $container->get(Security::class);
        $settings = $container->get('settings');

        $this->head = new View(ROOT_DIR . '/views/_head.php');
        $this->head->addVar('isAdmin', $this->security->isAdmin());
        $this->head->addVar('authName', $this->security->getAuthorizedName());
        $this->head->addVar('appName', $settings['app.name']);
        $this->head->addVar('appHeadJs', $settings['app.head_js']);

        $this->foot = new View(ROOT_DIR . '/views/_foot.php');
        $this->foot->addVar('appFooter', $settings['app.footer']);
    }
}
