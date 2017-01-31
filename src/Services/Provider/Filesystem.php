<?php
namespace Neusta\Hosts\Services\Provider;

use Neusta\Hosts\Exception\HostAlreadySet;

/**
 * Class Filesystem
 *
 * @ToDo move config to .hosts folder, add config file and hosts file
 *
 * @package Neusta\Hosts\Services\Provider
 */
class Filesystem
{
    /**
     * global configuration for all devs.
     */
    const GLOBAL_CONFIGURATION_FILE_PATH = 'http://127.0.0.1:8080/hosts/hosts.json';

    /**
     * configuration file name.
     */
    const CONFIGURATION_FILE_NAME = '.hosts';

    /**
     * Determines if currently adding new Data to avoid setting scope.
     * Scope will only be added if not updating.
     *
     * @var bool
     */
    private $_isUpdate = false;

    /**
     * Minimal Configuration used to save and connect.
     *
     * @var array
     */
    private $_defaultConfig = [
        'name' => '',
        'host' => '',
        'user' => '',
        'port' => 22
    ];

    /**
     * Filesystem constructor.
     * @codeCoverageIgnore
     *
     * @param \Symfony\Component\Filesystem\Filesystem $fs
     * @param File $file
     */
    public function __construct(
        \Symfony\Component\Filesystem\Filesystem $fs = null,
        File $file = null
    )
    {
        if(is_null($fs)){
            $fs = new \Symfony\Component\Filesystem\Filesystem();
        }
        if(is_null($file)){
            $file = new File();
        }
        $this->fs = $fs;
        $this->file = $file;
    }

    /**
     * Retrieve home dir.
     *
     * @return null|string
     */
    public function getHomeDir()
    {
        // Cannot use $_SERVER superglobal since that's empty during UnitUnishTestCase
        // getenv('HOME') isn't set on Windows and generates a Notice.
        $home = getenv('HOME');
        if (!empty($home)) {
            // home should never end with a trailing slash.
            $home = rtrim($home, '/');
        }
        elseif (!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
            // home on windows
            $home = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
            // If HOMEPATH is a root directory the path can end with a slash. Make sure
            // that doesn't happen.
            $home = rtrim($home, '\\/');
        }
        return empty($home) ? NULL : $home;
    }

    /**
     * Retrieve local configuration.
     *
     * @return bool|array
     */
    public function getLocalConfiguration()
    {
        $fileName = $this->getFilename(self::getHomeDir());
        $config = $this->getConfigurationFile($fileName);
        $config = $this->addScope($config, 'local');
        return $config;
    }

    /**
     * Retrieve project configuration.
     *
     * @return bool|array
     */
    public function getProjectConfiguration()
    {
        $filename = $this->getFilename();
        $config = $this->getConfigurationFile($filename);
        $config = $this->addScope($config, 'project');
        return $config;
    }

    /**
     * Retrieve local configuration.
     *
     * @return bool|array
     */
    public function getGlobalConfiguration()
    {
        $fileName = $this->getGlobalUrlFromConfig();
        $config = [];
        if($fileName !== false) {
            $config = $this->getConfigurationFile($fileName, false);
            $config = $this->addScope($config, 'global');
        }
        return $config;
    }

    /**
     * @param $fileName
     * @return bool|mixed
     */
    public function getConfigurationFile($fileName, $createIfNotExist = true)
    {
        if (!$this->fs->exists($fileName) && $createIfNotExist) {
            // generate a empty array for local configuration
            $defaults = ['hosts' => []];
            $this->fs->dumpFile($fileName, json_encode($defaults));
        }
        $config = json_decode($this->file->getContents($fileName), true);
        if (is_null($config)) {
            $config = false;
            return $config;
        }
        return $config;
    }

    public function addHostToConfiguration($hostConfig, $scope = 'local')
    {
        try {
            $this->_isUpdate = true;
            switch ($scope) {
                case 'project':
                    $fileName = $this->getFilename();
                    $config = $this->getProjectConfiguration();
                    break;
                default:
                    $fileName = $this->getFilename(self::getHomeDir());
                    $config = $this->getLocalConfiguration();
                    break;
            }
            $config['hosts'][] = array_merge($this->_defaultConfig, $hostConfig);
            $this->fs->dumpFile($fileName, json_encode($config));
            $this->_isUpdate = false;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }finally{
            $this->_isUpdate = false;
        }
    }

    /**
     * @param $hostUrl
     * @param bool $override
     *
     * @throws HostAlreadySet
     */
    public function setGlobalHostsUrl($hostUrl, $override = false)
    {
        $fileName = $this->getFilename(self::getHomeDir());
        $config = $this->getConfigurationFile($fileName);

        if(array_key_exists('hosts_url', $config) && $override){
            $config['hosts_url'] = $hostUrl;
        }elseif(array_key_exists('hosts_url', $config)){
            throw new HostAlreadySet($config['hosts_url']);
        }else{
            $config['hosts_url'] = $hostUrl;
        }
        $this->fs->dumpFile($fileName, json_encode($config));
    }

    /**
     * Get Filename with given location.
     *
     * @param string $baseDir
     * @return string
     */
    public function getFilename($baseDir = '.')
    {
        $fileName = $baseDir . DIRECTORY_SEPARATOR . self::CONFIGURATION_FILE_NAME;
        return $fileName;
    }

    /**
     * Add scope to each entry in config.
     *
     * @param $config
     * @param $scope
     *
     * @return mixed
     */
    private function addScope($config, $scope)
    {
        // do not add Scope during update. Scope will always be set when reading configuration.
        if(!$this->_isUpdate && is_array($config)) {
            foreach ($config['hosts'] as $key => $entry) {
                $config['hosts'][$key]['scope'] = $scope;
            }
        }
        if(!is_array($config)) $config = ['hosts' => []];
        return $config;
    }

    /**
     * Get global URL from config.
     *
     * @return bool | string
     */
    private function getGlobalUrlFromConfig()
    {
        $fileName = $this->getFilename(self::getHomeDir());
        $config = $this->getConfigurationFile($fileName, false);

        $hostUrl = false;
        if(array_key_exists('hosts_url',$config)) {
            $hostUrl = $config['hosts_url'];
        }

        return $hostUrl;
    }
}