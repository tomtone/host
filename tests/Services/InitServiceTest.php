<?php
namespace Neusta\Hosts\Tests\Services;


use Neusta\Hosts\Services\InitService;
use Neusta\Hosts\Services\Provider\Filesystem;
use org\bovigo\vfs\vfsStream;

class InitServiceTest extends \PHPUnit_Framework_TestCase
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
                'getHomeDir',
            ])
            ->getMock();
    }

    /**
     * @test
     *
     * @return void
     */
    public function validateLocalConfigurationExistAndReturnTrue()
    {
        $root = vfsStream::setup('exampleDir');
        vfsStream::newFile(Filesystem::CONFIGURATION_FILE_NAME)->at($root)->setContent('');

        $this->fileSystemMock->method('getHomeDir')
            ->willReturn($root->url());

        $initService = new InitService($this->fileSystemMock);

        self::assertTrue($initService->localConfigurationExist());
    }

    /**
     * @test
     *
     * @return void
     */
    public function validateLocalConfigurationExistAndReturnFalse()
    {
        $root = vfsStream::setup('exampleDir');
        vfsStream::newFile('missing File')->at($root)->setContent('');

        $this->fileSystemMock->method('getHomeDir')
            ->willReturn($root->url());

        $initService = new InitService($this->fileSystemMock);

        self::assertFalse($initService->localConfigurationExist());
    }
}