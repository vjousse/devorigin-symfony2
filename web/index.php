<?php

require_once __DIR__.'/../devorigin/DevoriginKernel.php';

$kernel = new DevoriginKernel('prod', false);
$kernel->handle()->send();
