<?php
/**
 * This file is part of the teamneusta/codeception-docker-chrome package.
 * Copyright (c) 2017 neusta GmbH | Ein team neusta Unternehmen
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 */

//@codeCoverageIgnoreStart
require dirname(__DIR__) . "/vendor/autoload.php";
use Neusta\Hosts\Console\Application;

$app = new Application('Hosts', '@package_version@');

$app->add(new \Neusta\Hosts\Command\ConnectCommand());
$app->add(new \Neusta\Hosts\Command\ListCommand());
$app->add(new \Neusta\Hosts\Command\AddCommand());
$app->add(new \Neusta\Hosts\Command\UpdateCommand());
$app->add(new \Neusta\Hosts\Command\InitCommand());
$app->run();
//@codeCoverageIgnoreEnd