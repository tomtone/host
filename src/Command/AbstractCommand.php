<?php
/**
 * Created by PhpStorm.
 * User: tgostomski
 * Date: 29.01.17
 * Time: 08:08
 */

namespace Neusta\MageHost\Command;


use Neusta\MageHost\Services\HostService;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 * @codeCoverageIgnore
 * @package Neusta\MageHost\Command
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var HostService
     */
    protected $_hostService;

    /**
     * Constructor.
     *
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     *
     * @param HostService $hostService
     */
    public function __construct($name = null, HostService $hostService = null)
    {
        parent::__construct($name);
        if(is_null($hostService)){
            $hostService = new HostService();
        }
        $this->_hostService = $hostService;
    }
}