#!/usr/bin/env php
<?php
Phar::mapPhar('magehost.phar');
$application = require_once 'phar://magehost.phar/src/bootstrap.php';
$application->setPharMode(true);
$application->run();
__HALT_COMPILER();
