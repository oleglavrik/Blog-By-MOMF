<?php

namespace vendor\core;

// Configuration directory
define('CONFIG_DIR', realpath(__DIR__ . '/../../app/config') . '/');

class Config
{
    public static function get($configFile){
        try{
            if($config = require_once CONFIG_DIR . $configFile . '.php')
                return $config;
            else
                throw new \Exception('Can not include the file, file name is wrong, check your configuration filename.');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
}