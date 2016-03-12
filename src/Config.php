<?php
namespace a15lam\PhpWemo;

class Config
{
    const PORT = '49153';

    private static $config = [
        'port' => Config::PORT
    ];

    public static function get($key, $default = null)
    {
        if(isset(static::$config[$key])){
            return static::$config[$key];
        }
        return $default;
    }
}