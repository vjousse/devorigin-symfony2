<?php

$loader->import('config_dev.php');

$container->loadFromExtension('kernel', 'config', array(
    'error_handler' => false,
));

$container->loadFromExtension('web', 'config', array(
    'toolbar' => false,
));

$container->loadFromExtension('zend', 'logger', array(
    'priority' => 'debug',
));

$container->loadFromExtension('kernel', 'test');
