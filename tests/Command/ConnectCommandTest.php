<?php

namespace Neusta\MageHost\Tests\Command;

use Neusta\MageHost\Command\ConnectCommand;
use Neusta\MageHost\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConnectCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     *
     * @return void
     */
    public function testConnectToInvalidHostWillRaiseErrorMessage()
    {
        $baseApplication =  new Application(null,null,'dev');
        $baseApplication->add(new ConnectCommand());

        $command = $baseApplication->find('connect');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([3]);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Added Entry: username@some.weired.host for  scope.', $output);
    }
}