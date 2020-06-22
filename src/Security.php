<?php
namespace Brave\TimerBoard;

use Brave\Sso\Basics\EveAuthentication;
use Brave\TimerBoard\Provider\RoleProviderInterface;
use Slim\Collection;
use SlimSession\Helper;

class Security
{
    /**
     * @var RoleProviderInterface
     */
    private $roleProvider;

    /**
     * @var Helper
     */
    private $session;

    private $groupsRead = [];

    private $groupsWrite = [];

    public function __construct(
        Collection $settings,
        RoleProviderInterface $roleProvider,
        Helper $session
    ) {
        $this->roleProvider = $roleProvider;
        $this->session = $session;

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
        /* @var $eveAuth EveAuthentication */
        $eveAuth = $this->session->get('eveAuth', null);

        return $eveAuth ? $eveAuth->getCharacterName() : '';
    }
}
