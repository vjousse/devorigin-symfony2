<?php

require_once __DIR__.'/../devorigin/DevoriginKernel.php';
use Symfony\Component\HttpFoundation\Request;

$kernel = new DevoriginKernel('prod', false);
$kernel->handle(new Request())->send();
