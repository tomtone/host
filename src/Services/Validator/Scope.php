<?php
/**
 * Created by PhpStorm.
 * User: tgostomski
 * Date: 28.01.17
 * Time: 15:58
 */

namespace Neusta\Hosts\Services\Validator;


class Scope
{
    const SCOPE_LOCAL = 'local';
    const SCOPE_GLOBAL = 'global';
    const SCOPE_PROJECT = 'project';

    public static function validateScope($scope)
    {
        if($scope != self::SCOPE_GLOBAL && $scope != self::SCOPE_LOCAL && $scope != self::SCOPE_PROJECT && $scope != null){
            throw new \InvalidArgumentException(printf('Scope "%s" not defined.', $scope));
        }
    }
}