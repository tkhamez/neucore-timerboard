<?php
/**
 * Required configuration for vendor/bin/doctrine.
 */

require __DIR__ . '/../vendor/autoload.php';

define('ROOT_DIR', realpath(__DIR__ . '/../'));
$bootstrap = new \Brave\TimerBoard\Bootstrap();

$em = $bootstrap->getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class);

$helpers = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
