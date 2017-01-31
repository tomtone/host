<?php
namespace Neusta\Hosts\Tests\Services;


use Neusta\Hosts\Services\HostService;

class HostServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Neusta\Hosts\Services\Provider\Filesystem | \PHPUnit_Framework_MockObject_MockObject
     */
    private $fileSystemMock;

    /**
     * Public setUp.
     */
    public function setUp()
    {
        parent::setUp();

        $this->fileSystemMock = $this->getMockBuilder("\\Neusta\\Hosts\\Services\\Provider\\Filesystem")
            ->disableOriginalConstructor()
            ->setMethods([
                'addHostToConfiguration',
                'getGlobalConfiguration',
                'getLocalConfiguration',
                'getProjectConfiguration',
            ])
            ->getMock();
    }

    /**
     * @test
     * @return void
     */
    public function testUpdateWillPassConfigArrayToUpdateMethodWithScopeParam()
    {
        $this->fileSystemMock
            ->expects($this->once())
            ->method('addHostToConfiguration')
            ->with([
                'name' => 'Some Identifier',
                'host' => 'some.weired.host',
                'user' => 'jon.doe',
                'port' => 22
            ], 'local');

        $hostService = new HostService($this->fileSystemMock);
        $hostService->setScope('local');
        $hostService->update('Some Identifier', 'some.weired.host', 'jon.doe');
    }

    public function getHostDataProvider()
    {
        return [
            'only local' => [
                'local',
                $this->once(),
                $this->never(),
                $this->never(),
                ['local'],
            ],
            'only project' => [
                'project',
                $this->never(),
                $this->once(),
                $this->never(),
                ['project'],
            ],
            'global' => [
                'global',
                $this->once(),
                $this->once(),
                $this->once(),
                ['local', 'project', 'global'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getHostDataProvider
     *
     * @return void
     */
    public function testGetHostWillCallConfigurationsDependingOnGivenScope($scope, $localCall, $projectCall, $globalCall, $expectation)
    {
        $this->fileSystemMock->method('getGlobalConfiguration')->willReturn(['hosts' => ['global']]);
        $this->fileSystemMock->method('getProjectConfiguration')->willReturn(['hosts' => ['project']]);
        $this->fileSystemMock->method('getLocalConfiguration')->willReturn(['hosts' => ['local']]);

        $this->fileSystemMock
            ->expects($localCall)
            ->method('getLocalConfiguration');
        $this->fileSystemMock
            ->expects($projectCall)
            ->method('getProjectConfiguration');
        $this->fileSystemMock
            ->expects($globalCall)
            ->method('getGlobalConfiguration');

        $hostService = new HostService($this->fileSystemMock);
        $result = $hostService->getHosts($scope);
        $this->assertSame($expectation, $result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetArrayOfHostsForQuestionHelper()
    {

        $this->fileSystemMock
            ->expects($this->once())
            ->method('getLocalConfiguration')
            ->willReturn([
                'hosts' => [
                    ['name' => 'HostName'],
                    ['name' => 'AnotherHostName'],
                ]]);

        $hostService = new HostService($this->fileSystemMock);
        $hostService->setScope('local');
        $result = $hostService->getHostsForQuestionhelper();
        $this->assertSame([0 => 'HostName', 1 => 'AnotherHostName'], $result);
    }

    public function testGetConnectionStringByNameWillReturnExpectedString()
    {
        $this->fileSystemMock
            ->expects($this->once())
            ->method('getLocalConfiguration')
            ->willReturn([
                'hosts' => [
                    [
                        'name' => 'HostName',
                        'user' => 'jon.doe',
                        'host' => 'some.weired.host'
                    ],
                ]
            ]);

        $hostService = new HostService($this->fileSystemMock);
        $hostService->setScope('local');
        $result = $hostService->getConnectionStringByName('HostName');
        $this->assertSame('jon.doe@some.weired.host', $result);
    }
}