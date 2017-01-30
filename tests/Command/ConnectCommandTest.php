<?php

namespace Neusta\Hosts\Tests\Command;

use Neusta\Hosts\Command\ConnectCommand;
use Neusta\Hosts\Console\Application;
use Neusta\Hosts\Services\HostService;
use Symfony\Component\Console\Tester\CommandTester;

class ConnectCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var HostService | \PHPUnit_Framework_MockObject_MockObject
     */
    private $hostServiceMock;

    public function setUp()
    {
        parent::setUp();
        $this->hostServiceMock = $this->getMockBuilder("\\Neusta\\Hosts\\Services\\HostService")
            ->disableOriginalConstructor()
            ->setMethods(['getHostsForQuestionhelper','getConnectionStringByName','getHosts'])
            ->getMock()
            ;

        $this->hostServiceMock->method('getHostsForQuestionhelper')
            ->willReturn([
                'SomeHost'
            ])
        ;
    }

    /**
     * @test
     *
     * @return void
     */
    public function testConnectToHostWillCreateCliConnection()
    {
        $baseApplication =  new Application(null,null,'dev');
        $baseApplication->add(new ConnectCommand(null, $this->hostServiceMock));

        $command = $baseApplication->find('connect');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([0]);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("You have selected: SomeHost\nestablishing connection...", $output);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testConnectToHostWillExitOnChoosingExitOption()
    {
        $baseApplication =  new Application(null,null,'dev');
        $baseApplication->add(new ConnectCommand(null, $this->hostServiceMock));

        $command = $baseApplication->find('connect');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['exit']);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("exiting.\nhave a nice day :-)", $output);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testInvalidHostWillRaiseErrorOfInvalidHost()
    {
        $this->markTestSkipped('This test is not supported on OSX.');

        $baseApplication =  new Application(null,null,'dev');
        $baseApplication->add(new ConnectCommand(null, $this->hostServiceMock));

        $command = $baseApplication->find('connect');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([2]);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("Host #2 is invalid.", $output);
    }
}