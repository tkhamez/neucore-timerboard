<?php

use Tkhamez\Slim\RoleAuth\RoleMiddleware;

/**
 * Required roles (one of them) for routes.
 *
 * First route match will be used, matched by "starts-with"
 *
 * {BOARD_GROUPS_READ} and {BOARD_GROUPS_WRITE} are placeholders,
 * see .env.dist
 */
return [
    '/login' => [RoleMiddleware::ROLE_ANY],
    '/auth'  => [RoleMiddleware::ROLE_ANY],
    '/admin' => '{BOARD_GROUPS_WRITE}',
    '/'      => '{BOARD_GROUPS_READ}',
];
