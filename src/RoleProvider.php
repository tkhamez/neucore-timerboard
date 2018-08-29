<?php
namespace Brave\TimerBoard;

use Brave\NeucoreApi\Api\ApplicationApi;
use Brave\Sso\Basics\SessionHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tkhamez\Slim\RoleAuth\RoleProviderInterface;

/**
 * Provides groups from Brave Core from an authenticated user.
 */
class RoleProvider implements RoleProviderInterface
{
    /**
     * @var ApplicationApi
     */
    private $api;

    /**
     * @var SessionHandlerInterface
     */
    private $session;

    /**
     * @param ApplicationApi $api
     * @param SessionHandlerInterface $session
     */
    public function __construct(ApplicationApi $api, SessionHandlerInterface $session)
    {
        $this->api = $api;
        $this->session = $session;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string[]
     */
    public function getRoles(ServerRequestInterface $request = null)
    {
        /* @var $eveAuth \Brave\Sso\Basics\EveAuthentication */
        $eveAuth = $this->session->get('eveAuth', null);
        if ($eveAuth === null) {
            return [];
        }

        $coreGroups = $this->session->get('coreGroups', null);
        if (is_array($coreGroups)) {
            return $coreGroups;
        }

        try {
            $groups = $this->api->groupsV1($eveAuth->getCharacterId());
        } catch (\Exception $e) {
            return [];
        }

        $roles = [];
        foreach ($groups as $group) {
            $roles[] = $group->getName();
        }
        $this->session->set('coreGroups', $roles);

        return $roles;
    }

    public function clear()
    {
        $this->session->set('coreGroups', null);
    }
}
