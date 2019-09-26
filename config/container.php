<?php

use Brave\NeucoreApi\Api\ApplicationApi;
use Brave\Sso\Basics\AuthenticationProvider;
use Brave\TimerBoard\Entity\Event;
use Brave\TimerBoard\Entity\System;
use Brave\TimerBoard\Repository\EventRepository;
use Brave\TimerBoard\Repository\SystemRepository;
use Brave\TimerBoard\RoleProvider;
use Brave\TimerBoard\Security;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use League\OAuth2\Client\Provider\GenericProvider;
use Psr\Container\ContainerInterface;
use Slim\App;

return [
    'settings' => require_once('config.php'),

    App::class => function (ContainerInterface $container)
    {
        return new Slim\App($container);
    },

    GenericProvider::class => function (ContainerInterface $container)
    {
        $settings = $container->get('settings');

        return new GenericProvider([
            'clientId' => $settings['SSO_CLIENT_ID'],
            'clientSecret' => $settings['SSO_CLIENT_SECRET'],
            'redirectUri' => $settings['SSO_REDIRECTURI'],
            'urlAuthorize' => $settings['SSO_URL_AUTHORIZE'],
            'urlAccessToken' => $settings['SSO_URL_ACCESSTOKEN'],
            'urlResourceOwnerDetails' => $settings['SSO_URL_RESOURCEOWNERDETAILS'],
        ]);
    },

    AuthenticationProvider::class => function (ContainerInterface $container)
    {
        $settings = $container->get('settings');

        return new AuthenticationProvider(
            $container->get(GenericProvider::class),
            explode(' ', $settings['SSO_SCOPES']),
            $settings['SSO_URL_JWT_KEY_SET']
        );
    },

    \Brave\TimerBoard\SessionHandler::class => function (ContainerInterface $container) {
        return new \Brave\TimerBoard\SessionHandler($container);
    },

    \Brave\Sso\Basics\SessionHandlerInterface::class => function (ContainerInterface $container) {
        return $container->get(\Brave\TimerBoard\SessionHandler::class);
    },

    ApplicationApi::class => function (ContainerInterface $container) {
        $apiKey = base64_encode(
            $container->get('settings')['CORE_APP_ID'] .
            ':'.
            $container->get('settings')['CORE_APP_TOKEN']
        );
        $config = Brave\NeucoreApi\Configuration::getDefaultConfiguration();
        $config->setHost($container->get('settings')['CORE_URL']);
        $config->setAccessToken($apiKey);
        $config->setApiKeyPrefix('Authorization', 'Bearer');

        return new ApplicationApi(null, $config);
    },

    RoleProvider::class => function (ContainerInterface $container) {
        return new RoleProvider(
            $container->get(ApplicationApi::class),
            $container->get(\Brave\Sso\Basics\SessionHandlerInterface::class)
        );
    },

    EntityManagerInterface::class => function (ContainerInterface $container) {
        $config = Setup::createAnnotationMetadataConfiguration(
            [ROOT_DIR . '/src/Entity'],
            true
        );
        $em = EntityManager::create(
            ['url' => $container->get('settings')['DB_URL']],
            $config
        );

        return $em;
    },

    EventRepository::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);
        $class = $em->getMetadataFactory()->getMetadataFor(Event::class);
        return new EventRepository($em, $class);
    },

    SystemRepository::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);
        $class = $em->getMetadataFactory()->getMetadataFor(System::class);
        return new SystemRepository($em, $class);
    },

    Security::class => function (ContainerInterface $container) {
        return new Security(
            $container->get('settings'),
            $container->get(RoleProvider::class),
            $container->get(\Brave\Sso\Basics\SessionHandlerInterface::class)
        );
    },
];
