<?php
require dirname(__DIR__) . "/vendor/autoload.php";
use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new \Neusta\MageHost\Command\ConnectCommand());
$app->add(new \Neusta\MageHost\Command\ListCommand());
$app->add(new \Neusta\MageHost\Command\AddCommand());
$app->run();