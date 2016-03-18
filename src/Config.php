<?php
namespace a15lam\PhpWemo;

/**
 * Class Config
 *
 * Retrieves config values from config.php file
 *
 * @package a15lam\PhpWemo
 */
class Config
{
    /**
     * Gets config value.
     *
     * @param string $key
     * @param null   $default
     *
     * @return null
     */
    public static function get($key, $default = null)
    {
        $key = strtolower($key);
        $config = include __DIR__ . '/../config.php';

        if (array_key_exists($key, $config)) {
            return $config[$key];
        } else {
            return $default;
        }
    }
}