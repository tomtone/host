<?php
namespace Neusta\MageHost\Services;
/**
 * Created by PhpStorm.
 * User: tgostomski
 * Date: 27.01.17
 * Time: 18:24
 */
class File
{
const DEFAULT_CONFIG_FILE = '.magehost';
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $fs;

    public function __construct()
    {
        $this->fs = new \Symfony\Component\Filesystem\Filesystem();
    }

    public function update($name, $host, $user)
    {
        $fileName = './'. self::DEFAULT_CONFIG_FILE;
        $this->create($fileName);
        $config = json_decode(file_get_contents('./'. self::DEFAULT_CONFIG_FILE));
        $config[] = [
            'name' => $name,
            'host' => $host,
            'user' => $user
        ];

        var_dump($config);
        $this->fs->dumpFile($fileName, json_encode($config));
    }

    private function create($fileName = null)
    {
        if(is_null($fileName)) {
            $fileName = './' . self::DEFAULT_CONFIG_FILE;
        }
        if(!$this->fs->exists($fileName)){
            // generate a minimal version for global packer configuration packer
            $defaults = [];
            $this->fs->dumpFile($fileName, json_encode($defaults));
        }
    }

    public function getHosts()
    {
        $config = json_decode(file_get_contents('./'. self::DEFAULT_CONFIG_FILE), true);
        return $config;
    }

    public function getHostsForQuestionhelper()
    {
        $config = json_decode(file_get_contents('./'. self::DEFAULT_CONFIG_FILE), true);
        $hosts = [];
        foreach ($config as $host) {
            $hosts[] = $host['name'];
        }
        return $hosts;
    }
}

#$home = self::getHomeDir();
#$fileName = $home . DIRECTORY_SEPARATOR . '.packer';
#$fs = new Filesystem();
#if(!$fs->exists($fileName)){
#    // generate a minimal version for global packer configuration packer
#    $defaults = [
#        'output' => sys_get_temp_dir()
#    ];
#    $fs->dumpFile($fileName, json_encode($defaults));
#}
#$config = json_decode(file_get_contents($fileName), true);
#if(is_null($config)){
#    $config = false;
#}
#return $config;