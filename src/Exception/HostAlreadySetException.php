<?php
/**
 * This file is part of the teamneusta/codeception-docker-chrome package.
 * Copyright (c) 2017 neusta GmbH | Ein team neusta Unternehmen
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 */

namespace Neusta\Hosts\Exception;

use Exception;

/**
 * Class HostAlreadySet
 * @codeCoverageIgnore
 * @package Neusta\Hosts\Exception
 */
class HostAlreadySetException extends \Exception
{
    private $_value;

    public function __construct($value = "", $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }
}