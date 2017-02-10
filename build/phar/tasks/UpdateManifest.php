<?php

/**
 * Class UpdateManifest
 */
class UpdateManifest extends Task
{
    private $_baseUrl = 'http://127.0.0.1:8080/magehost/downloads/';

    /**
     * Basic information to be provided/filled for release;
     *
     * @var array
     */
    private $_baseData = [
        "name" => "hosts.phar",
        "sha1" => "",
        "url" => "http://127.0.0.1:8080/magehost/downloads/hosts-v1.2.0.phar",
        "version" => "1.2.0"
    ];
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
    public function init()
    {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main()
    {
        $sha1 = sha1_file($this->baseDir . 'hosts.phar');

        $targetFileName = $this->getTargetFileName();

        copy($this->baseDir . 'hosts.phar',
            $this->baseDir . $this->downloadPath . DIRECTORY_SEPARATOR . $targetFileName);

        $releaseData = [
            'sha1' => $sha1,
            'url' => $this->_baseUrl . $targetFileName,
            'version' => $this->getVersion()
        ];

        $releaseData = array_merge($this->_baseData, $releaseData);

        $manifest = $this->addReleaseData($releaseData);
        $manifestEncoded = json_encode($manifest);

        file_put_contents($this->manifestPath, $manifestEncoded);
    }

    private function getTargetFileName()
    {
        $version = $this->getVersion();
        $projectName = $this->getProject()->getName();

        $fileName = $projectName . "-" . $version . '.phar';

        return $fileName;
    }

    /**
     * @return mixed
     */
    private function getVersion()
    {
        $composerJson = file_get_contents($this->baseDir . DIRECTORY_SEPARATOR . 'composer.json');
        $composerJson = json_decode($composerJson, true);

        $version = $composerJson['version'];
        return $version;
    }

    /**
     * @param $releaseData
     * @return array|mixed|string
     */
    private function addReleaseData($releaseData)
    {
        $manifest = @file_get_contents($this->manifestPath);

        if (strlen($manifest) > 0) {
            $manifest = json_decode($manifest, true);
        } else {
            $manifest = [];
        }

        $sha = $releaseData['sha1'];
        $version = $releaseData['version'];

        foreach ($manifest as $key => $entry){
            if((array_key_exists('sha1', $entry) && $entry['sha1'] == $sha) || (array_key_exists('version', $entry) && $entry['version'] == $version)){
                unset($manifest[$key]);
            }
        }

        $manifest[] = $releaseData;
        return array_values($manifest);
    }
}