<?php

/**
 * Class UpdateManifest
 */
class UpdateManifest extends Task
{
    /**
     * @var string
     */
    private $baseDir = null;

    /**
     * @var string
     */
    private $manifestPath;

    /**
     * @var string
     */
    private $downloadPath;

    /**
     * The setter for the attribute "basedir"
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * The setter for the attribute "basedir"
     */
    public function setManifestPath($manifestPath)
    {
        $this->manifestPath = $manifestPath;
    }

    /**
     * @param $downloadPath
     */
    public function setDownloadPath($downloadPath)
    {
        $this->downloadPath = $downloadPath;
    }

    /**
     * The init method: Do init steps.
     */
    public function init() {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main() {
        $sha1 = sha1_file($this->baseDir . 'hosts.phar');

        $manifest = file_get_contents($this->manifestPath);

        if(strlen($manifest) > 0){
            $manifest = json_decode($manifest);
        }
    }
}