<?php

use Tkhamez\Slim\RoleAuth\RoleMiddleware;

/**
 * Required roles (one of them) for routes.
 *
 * First route match will be used, matched by "starts-with"
 *
 * {APP_GROUPS_READ} and {APP_GROUPS_WRITE} are placeholders,
 * see .env.dist
 */
return [
    '/login' => [RoleMiddleware::ROLE_ANY],
    '/auth'  => [RoleMiddleware::ROLE_ANY],
    '/admin' => '{APP_GROUPS_WRITE}',
    '/'      => '{APP_GROUPS_READ}',
];
