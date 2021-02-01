<?php

return [
    // Slim
    'displayErrorDetails' => getenv('APP_ENV') === 'dev',
    'determineRouteBeforeAppMiddleware' => true,

    // SSO configuration
    'SSO_CLIENT_ID' => getenv('SSO_CLIENT_ID'),
    'SSO_CLIENT_SECRET' => getenv('SSO_CLIENT_SECRET'),
    'SSO_REDIRECTURI' => getenv('SSO_REDIRECTURI'),
    'SSO_URL_AUTHORIZE' => 'https://login.eveonline.com/v2/oauth/authorize',
    'SSO_URL_ACCESSTOKEN' => 'https://login.eveonline.com/v2/oauth/token',
    'SSO_URL_RESOURCEOWNERDETAILS' => 'https://esi.evetech.net/verify/',
    'SSO_URL_JWT_KEY_SET' => 'https://login.eveonline.com/oauth/jwks',
    'SSO_SCOPES' => getenv('SSO_SCOPES'),

    // Neucore
    'CORE_URL' => getenv('CORE_URL'),
    'CORE_APP_ID' => getenv('CORE_APP_ID'),
    'CORE_APP_TOKEN' => getenv('CORE_APP_TOKEN'),

    // Database
    'DB_URL' => getenv('DB_URL'),

    // Timer board
    'app.env' => getenv('APP_ENV'),
    'app.name' => getenv('APP_NAME'),
    'app.footer' => getenv('APP_FOOTER'),
    'app.login_logo' => getenv('APP_LOGIN_LOGO'),
    'app.login_hint' => getenv('APP_LOGIN_HINT'),
    'app.head_js' => getenv('APP_HEAD_JS'),
    'app.role_provider' => getenv('APP_ROLE_PROVIDER'),
    'app.groups.read' => getenv('APP_GROUPS_READ'),
    'app.groups.write' => getenv('APP_GROUPS_WRITE'),
];
