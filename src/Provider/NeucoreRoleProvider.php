<?php
/** @noinspection PhpUnused */

namespace Brave\TimerBoard\Provider;

use Brave\NeucoreApi\Api\ApplicationApi;
use Brave\NeucoreApi\ApiException;
use Brave\Sso\Basics\EveAuthentication;
use Psr\Http\Message\ServerRequestInterface;
use SlimSession\Helper;

/**
 * Provides groups from Brave Core from an authenticated user.
 */
class NeucoreRoleProvider implements RoleProviderInterface
{
    const SESSION_CACHE_KEY = 'coreGroups';

    /**
     * @var ApplicationApi
     */
    private $api;

    /**
     * @var Helper
     */
    private $session;

    /**
     * @param ApplicationApi $api
     * @param Helper $session
     */
    public function __construct(ApplicationApi $api, Helper $session)
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
        $roles = [self::ROLE_ANY];

        /* @var $eveAuth EveAuthentication */
        $eveAuth = $this->session->get('eveAuth', null);
        if ($eveAuth === null) {
            return $roles;
        }

        // try cache
        $cachedGroups = $this->getCachedRoles();
        if (! empty($cachedGroups)) {
            return $cachedGroups;
        }

        // get groups from Core
        try {
            $groups = $this->api->groupsV2($eveAuth->getCharacterId());
        } catch (ApiException $ae) {
            // Don't log "404 Character not found." error from Core.
            if ($ae->getCode() !== 404 || strpos($ae->getMessage(), 'Character not found.') === false) {
                error_log((string)$ae);
            }
            return $roles;
        }
        foreach ($groups as $group) {
            $roles[] = $group->getName();
        }

        // cache roles
        $this->session->set(self::SESSION_CACHE_KEY, [
            'time' => time(),
            'roles' => $roles
        ]);

        return $roles;
    }

    public function clear()
    {
        $this->session->set(self::SESSION_CACHE_KEY, null);
    }

    /**
     * @return string[]
     */
    public function getCachedRoles()
    {
        $coreGroups = $this->session->get(self::SESSION_CACHE_KEY, null);
        if (is_array($coreGroups) && $coreGroups['time'] > (time() - 60*60)) {
            return $coreGroups['roles'];
        } else {
            return [];
        }
    }
}
