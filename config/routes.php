<?php

use Brave\Sso\Basics\AuthenticationController;
use Brave\TimerBoard\Controller\Authentication;
use Brave\TimerBoard\Controller\Index;

return function (\Psr\Container\ContainerInterface $container)
{
    /** @var \Slim\App $app */
    $app = $container[\Slim\App::class];

    // SSO via sso-basics package
    $app->get('/login', AuthenticationController::class . ':index');
    $app->get('/auth',  Authentication::class . ':callback');

    $app->get('/logout', Authentication::class . ':logout');
    $app->get('/',       Index::class . ':board');

    return $app;
};
