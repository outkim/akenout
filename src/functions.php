<?php
/**
 * Created by PhpStorm.
 * User: kimba
 * Date: 08/07/2018
 * Time: 12:45
 */

function akenout_path($file_path){
    if(strpos($file_path,'/') === 0){
        $file_path = substr($file_path,1);
    }
    //$current = __DIR__;
    $current = str_replace('src','',__DIR__) . $file_path;

    return $current;
}
