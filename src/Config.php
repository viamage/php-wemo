<?php
namespace a15lam\PhpWemo;

class Config
{
    public static function get($key, $default = null)
    {
        $key = strtolower($key);
        $config = include __DIR__.'/../config.php';

        if(array_key_exists($key, $config)){
            return $config[$key];
        } else {
            return $default;
        }
    }
}