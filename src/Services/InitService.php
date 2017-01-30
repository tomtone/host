<?php
namespace Neusta\Hosts\Services;

use Neusta\Hosts\Exception\HostAlreadySet;
use Neusta\Hosts\Services\Provider\File;
use Neusta\Hosts\Services\Provider\Filesystem;

/**
 * Class InitService
 * @package Neusta\Hosts\Services
 */
class InitService
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * InitService constructor.
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs = null)
    {
        if (is_null($fs)) {
            $fs = new Filesystem();
        }
        $this->fs = $fs;
    }

    /**
     * Check if .hosts file exist in current user home directory.
     *
     * @return bool
     */
    public function localConfigurationExist()
    {
        $homeDir = $this->fs->getHomeDir();

        return file_exists($homeDir . DIRECTORY_SEPARATOR . Filesystem::CONFIGURATION_FILE_NAME);
    }

    /**
     * Generate empty file.
     */
    public function createLocalConfiguration()
    {
        $this->fs->getLocalConfiguration();
    }

    /**
     * @param $globalHostsUrl
     */
    public function addGlobalHostUrl($globalHostsUrl, $override = false)
    {
        $this->fs->setGlobalHostsUrl($globalHostsUrl, $override);
    }
}