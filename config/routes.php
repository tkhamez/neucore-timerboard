<?php

return function (\Psr\Container\ContainerInterface $container)
{
    /** @var \Slim\App $app */
    $app = $container[\Slim\App::class];

    // SSO via sso-basics package
    $app->get('/login', \Brave\Sso\Basics\AuthenticationController::class . ':index');
    $app->get('/auth',  \Brave\TimerBoard\Controller\Authentication::class . ':callback');

    // app routes
    $app->get('/logout', \Brave\TimerBoard\Controller\Authentication::class . ':logout');
    $app->get('/',       \Brave\TimerBoard\Controller\Index::class . ':board');
    $app->get('/admin',  \Brave\TimerBoard\Controller\Admin::class . ':index');

    return $app;
};
