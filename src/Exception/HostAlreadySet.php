<?php
namespace Neusta\Hosts\Exception;

use Exception;

/**
 * Class HostAlreadySet
 * @codeCoverageIgnore
 * @package Neusta\Hosts\Exception
 */
class HostAlreadySet extends \Exception
{
    private $_value;

    public function __construct($value = "", $message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }
}