<?php

$vendorDir = __DIR__.'/vendor';

require_once $vendorDir.'/symfony/src/Symfony/Component/HttpFoundation/UniversalClassLoader.php';

$loader = new Symfony\Component\HttpFoundation\UniversalClassLoader();

$loader->registerNamespaces(array(
    'Symfony' => $vendorDir.'/symfony/src',
    'Application' => __DIR__,
    'Bundle' => __DIR__,
    'Doctrine\DBAL\Migrations' => $vendorDir.'/doctrine-migrations/lib',
    'Doctrine\Common' => $vendorDir.'/doctrine/lib/vendor/doctrine-common/lib',
    'Doctrine\DBAL' => $vendorDir.'/doctrine-dbal/lib',
    'Doctrine' => $vendorDir.'/doctrine/lib',
    'Zend' => $vendorDir.'/zend/library',
));

$loader->registerPrefixes(array(
    'Twig_' => __DIR__.'/vendor/Twig/lib'
));

$loader->register();

