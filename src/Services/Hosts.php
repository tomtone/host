<?php
namespace Neusta\MageHost\Services;

class Hosts
{
const DEFAULT_CONFIG_FILE = '.magehost';
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $fs;

    public function __construct()
    {
        $this->fs = new \Symfony\Component\Filesystem\Filesystem();
        $this->configuration = $this->getConfig();
    }

    public function update($name, $host, $user)
    {
        $fileName = './'. self::DEFAULT_CONFIG_FILE;
        $this->getConfig($fileName);
        $config = $this->configuration;
        $config[] = [
            'name' => $name,
            'host' => $host,
            'user' => $user,
            'port' => 22
        ];
        $this->fs->dumpFile($fileName, json_encode($config));
    }

    private function getConfig($fileName = null)
    {
        if(is_null($fileName)) {
            $fileName = './' . self::DEFAULT_CONFIG_FILE;
        }
        if(!$this->fs->exists($fileName)){
            // generate a minimal version for global packer configuration packer
            $defaults = [];
            $this->fs->dumpFile($fileName, json_encode($defaults));
        }

        $config = json_decode(file_get_contents('./'. self::DEFAULT_CONFIG_FILE), true);

        return $config;
    }

    public function getHosts($scope = 'global')
    {
        $config = $this->configuration;
        return $config;
    }

    public function getHostsForQuestionhelper()
    {
        $config = $this->configuration;
        $hosts = [];
        foreach ($config as $host) {
            $hosts[] = $host['name'];
        }
        return $hosts;
    }

    public function getConnectionStringByName($host)
    {
        $config = $this->configuration;

        $hostKey = array_search($host, array_column($config, 'name'));

        $sshCommand = $config[$hostKey]['user'] . '@' . $config[$hostKey]['host'];
        return $sshCommand;
    }
}