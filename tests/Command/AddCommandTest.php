<?php

namespace Neusta\MageHost\Tests\Command;

use Neusta\MageHost\Command\AddCommand;
use Neusta\MageHost\Console\Application;
use Neusta\MageHost\Services\HostService;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

class AddCommandTest extends \PHPUnit_Framework_TestCase
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
            ->setMethods(['getHostsForQuestionhelper','getConnectionStringByName'])
            ->getMock()
        ;

        $this->hostServiceMock->method('getHostsForQuestionhelper')
            ->willReturn([
                'SomeHost'
            ])
        ;
    }

    public function getEnvironmentDataProvider()
    {
        return [
            'project scope' => [1, 'project'],
            'local scope' => [0, 'local']
        ];
    }

    /**
     * @test
     * @dataProvider getEnvironmentDataProvider
     *
     * @return void
     */
    public function testAddWillAddHostAndReturnAddedHost($parameter, $expectation)
    {
        $baseApplication =  new Application(null,null,'dev');
        $baseApplication->add(new AddCommand(null, $this->hostServiceMock));

        $command = $baseApplication->find('host:add');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['Test Host','some.weired.host','username',$parameter]);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Added Entry: username@some.weired.host for '.$expectation.' scope.', $output);
    }
}