<?php
namespace Neusta\Hosts\Console;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @param string $name    The name of the application
     * @param string $version The version of the application
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN', $environment = 'prod')
    {
        $this->environment = $environment;
        parent::__construct($name, $version);
    }

    public function getEnvironment()
    {
        return $this->environment;
    }
}