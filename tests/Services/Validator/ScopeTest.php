<?php
namespace Neusta\Hosts\Test\Services\Validator;


use Neusta\Hosts\Services\Validator\Scope;

class ScopeTest extends \PHPUnit_Framework_TestCase
{

    public function getScopesDataProvider()
    {
        return [
            'valid local scope' => [
                'local',
                false
            ],
            'valid project scope' => [
                'project',
                false
            ],
            'valid global scope' => [
                'global',
                false
            ],
            'valid NULL scope' => [
                null,
                false
            ],
            'invalid scope' => [
                'pim',
                true
            ],
        ];
    }
    /**
     * @test
     * @dataProvider getScopesDataProvider
     *
     * @return void
     */
    public function testValidateScopeWillThrowExceptionOnInvalidScopeValue($scope, $throwsException)
    {
        if($throwsException) {
            $this->expectException("InvalidArgumentException");
        }
        Scope::validateScope($scope);
    }
}