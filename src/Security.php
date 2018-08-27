<?php
namespace Brave\TimerBoard;

use Slim\Collection;

class Security
{
    /**
     * @var RoleProvider
     */
    private $roleProvider;

    private $groupsRead = [];

    private $groupsWrite = [];

    public function __construct(Collection $settings, RoleProvider $roleProvider)
    {
        $this->roleProvider = $roleProvider;

        if (trim($settings['brave.groups.read']) !== '') {
            $this->groupsRead = explode(',', $settings['brave.groups.read']);
        }
        if (trim($settings['brave.groups.write']) !== '') {
            $this->groupsWrite = explode(',', $settings['brave.groups.write']);
        }
    }

    public function readConfig()
    {
        $securityConfig = [];
        foreach (include ROOT_DIR . '/config/security.php' as $path => $roles) {
            if ($roles === '{BOARD_GROUPS_READ}') {
                $securityConfig[$path] = $this->groupsRead;
            } elseif ($roles === '{BOARD_GROUPS_WRITE}') {
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
}
