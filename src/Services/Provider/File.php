<?php
namespace Neusta\Hosts\Services\Provider;

/**
 * Class File
 * @codeCoverageIgnore
 * @package Neusta\Hosts\Services\Provider
 */
class File
{
    /**
     * Wrapping file_get_contents method for UnitTesting purposes.
     *
     * @param bool $filename
     * @return string
     */
    public function getContents($filename = false)
    {
        $content = '';
        if($filename){
            $content = @file_get_contents($filename);
        }
        // if anything goes wrong getting the contents, add empty Array to avoid errors during json_decode
        if($content === FALSE) {
            $content = '[]';
        }
        return $content;
    }
}