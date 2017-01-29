<?php

namespace Neusta\MageHost\Tests\Command;

use Neusta\MageHost\Command\ListCommand;
use Neusta\MageHost\Console\Application;
use Neusta\MageHost\Services\HostService;
use Symfony\Component\Console\Tester\CommandTester;

class ListCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var HostService | \PHPUnit_Framework_MockObject_MockObject
     */
    private $hostServiceMock;

    public function setUp()
    {
        parent::setUp();
        $this->hostServiceMock = $this->getMockBuilder("\\Neusta\\MageHost\\Services\\HostService")
            ->disableOriginalConstructor()
            ->setMethods(['getHosts'])
            ->getMock()
            ;
    }

    /**
     * @test
     *
     * @return void
     */
    public function testListHostWillReturnListOfAvailableHosts()
    {
        $this->hostServiceMock->method('getHosts')
            ->willReturn([
                [
                    'name' => 'SomeHost',
                    'host' => 'weired.host.tld',
                    'user' => 'jon.doe',
                    'scope' => 'local',
                ]
            ])
        ;
        $baseApplication =  new Application(null,null,'dev');
        $baseApplication->add(new ListCommand(null, $this->hostServiceMock));

        $command = $baseApplication->find('host:list');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([0]);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains(
            "| SomeHost | weired.host.tld | jon.doe | local |",
            $output
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testListHostWillReturnNoticeOfNoHostsFound()
    {
        $this->hostServiceMock->method('getHosts')
            ->willReturn([])
        ;

        $baseApplication =  new Application(null,null,'dev');
        $baseApplication->add(new ListCommand(null, $this->hostServiceMock));

        $command = $baseApplication->find('host:list');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([0]);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains(
            "| No entries found.             |",
            $output
        );
    }
}