<?php

use Brave\NeucoreApi\Api\ApplicationApi;
use Brave\Sso\Basics\AuthenticationProvider;
use Brave\TimerBoard\Entity\Event;
use Brave\TimerBoard\Entity\System;
use Brave\TimerBoard\Provider\RoleProviderInterface;
use Brave\TimerBoard\Repository\EventRepository;
use Brave\TimerBoard\Repository\SystemRepository;
use Brave\TimerBoard\Security;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use League\OAuth2\Client\Provider\GenericProvider;
use Psr\Container\ContainerInterface;
use Slim\App;
use SlimSession\Helper;

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

    Helper::class => function () {
        return new Helper();
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

    RoleProviderInterface::class => function (ContainerInterface $container) {
        $class = $container->get('settings')['app.role_provider'];
        if (! class_exists($class)) {
            throw new Exception("Class '$class' does not exists.");
        }
        return new $class(
            $container->get(ApplicationApi::class),
            $container->get(Helper::class)
        );
    },

    EntityManagerInterface::class => function (ContainerInterface $container) {
        $config = Setup::createAnnotationMetadataConfiguration(
            [ROOT_DIR . '/src/Entity'],
            true
        );
        return EntityManager::create(
            ['url' => $container->get('settings')['DB_URL']],
            $config
        );
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
            $container->get(RoleProviderInterface::class),
            $container->get(Helper::class)
        );
    },
];
