<?php

return [
    // Slim
    'displayErrorDetails' => getenv('APP_ENV') === 'dev',
    'determineRouteBeforeAppMiddleware' => true,

    // SSO CONFIGURATION
    'SSO_CLIENT_ID' => getenv('SSO_CLIENT_ID'),
    'SSO_CLIENT_SECRET' => getenv('SSO_CLIENT_SECRET'),
    'SSO_REDIRECTURI' => getenv('SSO_REDIRECTURI'),
    'SSO_URL_AUTHORIZE' => 'https://login.eveonline.com/oauth/authorize',
    'SSO_URL_ACCESSTOKEN' => 'https://login.eveonline.com/oauth/token',
    'SSO_URL_RESOURCEOWNERDETAILS' => 'https://esi.evetech.net/verify/',
    'SSO_SCOPES' => getenv('SSO_SCOPES'),

    // App
    'brave.serviceName' => 'Brave Collective TimerBoard',

    // NEUCORE
    'CORE_URL' => getenv('CORE_URL'),
    'CORE_APP_ID' => getenv('CORE_APP_ID'),
    'CORE_APP_TOKEN' => getenv('CORE_APP_TOKEN'),

    // DB
    'DB_URL' => getenv('DB_URL'),

    // timer board
    'app.env' => getenv('APP_ENV'),
    'app.groups.read' => getenv('APP_GROUPS_READ'),
    'app.groups.write' => getenv('APP_GROUPS_WRITE'),
];
