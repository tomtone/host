<?php
//@codeCoverageIgnoreStart
require dirname(__DIR__) . "/vendor/autoload.php";
use Neusta\Hosts\Console\Application;

$app = new Application('Hosts', '1.0.0');
$app->add(new \Neusta\Hosts\Command\ConnectCommand());
$app->add(new \Neusta\Hosts\Command\ListCommand());
$app->add(new \Neusta\Hosts\Command\AddCommand());
$app->add(new \Neusta\Hosts\Command\UpdateCommand());
$app->run();
//@codeCoverageIgnoreEnd