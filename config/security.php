<?php

use Tkhamez\Slim\RoleAuth\RoleMiddleware;

/**
 * Required roles (one of them) for routes.
 *
 * First route match will be used, matched by "starts-with"
 */
return [
    '/login' => [RoleMiddleware::ROLE_ANY],
    '/auth'  => [RoleMiddleware::ROLE_ANY],
    '/'      => ['auto.bni'],
];
