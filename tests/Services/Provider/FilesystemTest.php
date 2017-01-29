<?php
namespace Neusta\Hosts\Test\Services\Provider;

use Neusta\Hosts\Services\Provider\Filesystem;

/**
 * Class FilesystemTest
 *
 * @package Neusta\Hosts\Test\Services\Provider
 */
class FilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem | \PHPUnit_Framework_MockObject_MockObject
     */
    private $fileSystem;

    /**
     * @var \Neusta\Hosts\Services\Provider\File | \PHPUnit_Framework_MockObject_MockObject
     */
    private $file;

    /**
     * @var string
     */
    private $_originalHomePath;

    public function setUp()
    {
        $this->_originalHomePath = getenv('HOME');
        parent::setUp();

        putenv('HOME=/some/home/path/');

        $this->fileSystem = $this->getMockBuilder("\\Symfony\\Component\\Filesystem\\Filesystem")
            ->disableOriginalConstructor()
            ->setMethods(['exists', 'dumpFile'])
            ->getMock();
        $this->file = $this->getMockBuilder("\\Neusta\\Hosts\\Services\\Provider\\File")
            ->disableOriginalConstructor()
            ->setMethods(['getContents'])
            ->getMock();
    }

    public function tearDown()
    {
        parent::tearDown();
        putenv("HOME=" . $this->_originalHomePath);
    }

    public function testGetHomeDirWillRelyOnServerIfEnvironmentIsMissing()
    {
        putenv("HOME=");
        $_SERVER['HOMEPATH'] = 'homePath/';
        $_SERVER['HOMEDRIVE'] = '/homeDrive/';

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $result = $filesystem->getHomeDir();

        $this->assertSame('/homeDrive/homePath',$result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetConfigurationFileWillCreateDefaultFileIfNotExist()
    {
        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->willReturn(false);
        $this->fileSystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                'someFile.txt',
                json_encode([])
            );
        $this->file->expects($this->once())
            ->method('getContents')
            ->with('someFile.txt')
            ->willReturn(json_encode([]));

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $result = $filesystem->getConfigurationFile('someFile.txt', true);

        $this->assertSame([], $result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetConfigurationFileWillReturnFalseOnJsonDecodeIssue()
    {
        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->willReturn(false);
        $this->fileSystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                'someFile.txt',
                json_encode([])
            );
        $this->file->expects($this->once())
            ->method('getContents')
            ->with('someFile.txt')
            ->willReturn('{noneJsonString]');

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $result = $filesystem->getConfigurationFile('someFile.txt', true);

        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetLocalConfigurationWillReturnEntriesWithScopeValueSet()
    {
        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->willReturn(false);
        $this->fileSystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                '/some/home/path/.hosts',
                json_encode([])
            );
        $this->file->expects($this->once())
            ->method('getContents')
            ->with('/some/home/path/.hosts')
            ->willReturn(json_encode([['name' => 'HostName']]));

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $result = $filesystem->getLocalConfiguration();

        $this->assertSame([['name' => 'HostName', 'scope' => 'local']], $result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetProjectConfigurationWillReturnEntriesWithScopeValueSet()
    {
        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->willReturn(false);
        $this->fileSystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                './.hosts',
                json_encode([])
            );
        $this->file->expects($this->once())
            ->method('getContents')
            ->with('./.hosts')
            ->willReturn(json_encode([['name' => 'HostName']]));

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $result = $filesystem->getProjectConfiguration();

        $this->assertSame([['name' => 'HostName', 'scope' => 'project']], $result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetGlobalConfigurationWillReturnEntriesWithScopeValueSet()
    {
        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->willReturn(false);

        $this->fileSystem->expects($this->never())
            ->method('dumpFile')
            ;
        $this->file->expects($this->once())
            ->method('getContents')
            ->with('http://127.0.0.1:8080/hosts/hosts.json')
            ->willReturn(json_encode([['name' => 'HostName']]));

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $result = $filesystem->getGlobalConfiguration();

        $this->assertSame([['name' => 'HostName', 'scope' => 'global']], $result);
    }

    public function getScopePathsAndValuesDataProvider()
    {
        return [
            'local/default Scope' => [
                'local',
                '/some/home/path/.hosts',
                [[
                    'name' => 'HostName',
                    'host' => '',
                    'user' => '',
                    'port' => 22
                ]]
            ],
            'project Scope' => [
                'project',
                './.hosts',
                [[
                    'name' => 'HostName',
                    'host' => '',
                    'user' => '',
                    'port' => 22
                ]]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getScopePathsAndValuesDataProvider
     *
     * @return void
     */
    public function testAddHostToConfigurationWillAddHostDependingOnGivenScope($scope, $expectedFileName, $expectedData)
    {
        $this->fileSystem
            ->method('exists')
            ->willReturn(true);

        $this->fileSystem->expects($this->any())
            ->method('dumpFile')
            ->with(
                $expectedFileName,
                json_encode($expectedData)
            )
        ;
        $this->file->expects($this->any())
            ->method('getContents')
            ->with($expectedFileName)
            ->willReturn(json_encode([]));

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $filesystem->addHostToConfiguration(['name' => 'HostName'], $scope);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testAddHostToConfigurationWillPassExceptionWithCatchedMassage()
    {
        $this->fileSystem
            ->method('exists')
            ->willReturn(true);

        $this->fileSystem->expects($this->any())
            ->method('dumpFile')
            ->with(
                '/some/home/path/.hosts',
                json_encode([[
                    'name' => 'HostName',
                    'host' => '',
                    'user' => '',
                    'port' => 22
                ]])
            )
            ->willThrowException(new \Exception('SomeError'))
        ;
        $this->file->expects($this->any())
            ->method('getContents')
            ->with('/some/home/path/.hosts')
            ->willReturn(json_encode([]));

        $this->expectException("Exception");
        $this->expectExceptionMessage('SomeError');

        $filesystem = new Filesystem($this->fileSystem, $this->file);

        $filesystem->addHostToConfiguration(['name' => 'HostName']);
    }
}