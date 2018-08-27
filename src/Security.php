<?php
namespace Brave\TimerBoard;

use Brave\Sso\Basics\SessionHandlerInterface;
use Psr\Container\ContainerInterface;
use Slim\Collection;

class Security
{
    /**
     * @var RoleProvider
     */
    private $roleProvider;

    /**
     * @var ContainerInterface
     */
    private $container;

    private $groupsRead = [];

    private $groupsWrite = [];

    public function __construct(Collection $settings, RoleProvider $roleProvider, ContainerInterface $container = null)
    {
        $this->roleProvider = $roleProvider;
        $this->container = $container;

        if (trim($settings['app.groups.read']) !== '') {
            $this->groupsRead = explode(',', $settings['app.groups.read']);
        }
        if (trim($settings['app.groups.write']) !== '') {
            $this->groupsWrite = explode(',', $settings['app.groups.write']);
        }
    }

    public function readConfig()
    {
        $securityConfig = [];
        foreach (include ROOT_DIR . '/config/security.php' as $path => $roles) {
            if ($roles === '{APP_GROUPS_READ}') {
                $securityConfig[$path] = $this->groupsRead;
            } elseif ($roles === '{APP_GROUPS_WRITE}') {
                $securityConfig[$path] = $this->groupsWrite;
            } else {
                $securityConfig[$path] = $roles;
            }
        }

        return $securityConfig;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        $roles = $this->roleProvider->getRoles();

        return count(array_intersect($this->groupsWrite, $roles)) > 0;
    }

    /**
     * @return string
     */
    public function getAuthorizedName()
    {
        /* @var $eveAuth \Brave\Sso\Basics\EveAuthentication */
        $session = $this->container->get(SessionHandlerInterface::class);
        $eveAuth = $session ? $session->get('eveAuth', null) : null;

        return $eveAuth ? $eveAuth->getCharacterName() : '';
    }
}
