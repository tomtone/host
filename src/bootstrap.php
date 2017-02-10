<?php
//@codeCoverageIgnoreStart
require dirname(__DIR__) . "/vendor/autoload.php";
use Neusta\Hosts\Console\Application;

$app = new Application('Hosts', '1.0.0-stable');
$app->add(new \Neusta\Hosts\Command\ConnectCommand());
$app->add(new \Neusta\Hosts\Command\ListCommand());
$app->add(new \Neusta\Hosts\Command\AddCommand());
$app->add(new \Neusta\Hosts\Command\UpdateCommand());
$app->add(new \Neusta\Hosts\Command\InitCommand());
$app->run();
//@codeCoverageIgnoreEnd