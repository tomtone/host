<?php
require dirname(__DIR__) . "/vendor/autoload.php";
use Symfony\Component\Console\Application;

$app = new Application('MageHost', '1.0.0');
$app->add(new \Neusta\MageHost\Command\ConnectCommand());
$app->add(new \Neusta\MageHost\Command\ListCommand());
$app->add(new \Neusta\MageHost\Command\AddCommand());
$app->add(new \Neusta\MageHost\Command\UpdateCommand());
$app->run();