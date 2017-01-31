<?php
namespace Neusta\Hosts\Console;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * @var string
     */
    private $environment;

    private static $_logo = <<<LOGO
               ,:',:`,:'
            __||_||_||_||__
       ____["""""""""""""""]____
       \ " '''''''''''''''''''' |
~^~^~^^~^~^~^~^~^~^~^~~^~^~^^~~^~^~^~^

LOGO;

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

    /**
     * @return string
     */
    public function getHelp()
    {
        return static::$_logo . parent::getHelp();
    }
}