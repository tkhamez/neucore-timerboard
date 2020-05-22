<?php

namespace Brave\TimerBoard\Provider;

interface RoleProviderInterface extends \Tkhamez\Slim\RoleAuth\RoleProviderInterface
{
    /**
     * This role is always added.
     */
    const ROLE_ANY = 'role:any';

    /**
     * @return void
     */
    public function clear();
}
