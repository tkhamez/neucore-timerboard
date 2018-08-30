<?php

return function (\Psr\Container\ContainerInterface $container)
{
    /** @var \Slim\App $app */
    $app = $container[\Slim\App::class];

    // SSO via sso-basics package
    $app->get('/login', \Brave\TimerBoard\Controller\Authentication::class . ':index');
    $app->get('/auth',  \Brave\TimerBoard\Controller\Authentication::class . ':callback');

    // app routes
    $app->get('/logout', \Brave\TimerBoard\Controller\Authentication::class . ':logout');
    $app->get('/',       \Brave\TimerBoard\Controller\Index::class . ':board');
    $app->get('/admin/{id}',         \Brave\TimerBoard\Controller\Admin::class . ':index');
    $app->post('/admin/{id}',        \Brave\TimerBoard\Controller\Admin::class . ':save');
    $app->post('/admin/delete/{id}', \Brave\TimerBoard\Controller\Admin::class . ':delete');

    return $app;
};
