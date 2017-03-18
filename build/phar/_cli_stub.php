#!/usr/bin/env php
<?php
Phar::mapPhar('hosts.phar');
$application = require_once 'phar://hosts.phar/src/bootstrap.php';
$application->setPharMode(true);
$application->run();
__HALT_COMPILER();
