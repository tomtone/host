<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 18.03.2017
 * Time: 17:37
 */
$file = 'https://raw.githubusercontent.com/tomtone/test/master/hosts/manifest.json';
$content = file_get_contents($file);

print_r(json_decode($content, true, 512, 0));