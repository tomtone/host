<?php
/**
 * Created by PhpStorm.
 * User: tgostomski
 * Date: 29.01.17
 * Time: 07:43
 */

namespace Neusta\MageHost\Services\Provider;


class Cli
{
    public static function passthruSsh($string)
    {
        return passthru("ssh " . $string);
    }
}