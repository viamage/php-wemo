<?php
namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;

class Bulb implements DeviceInterface
{
    protected $bridgeIp = null;

    protected $deviceId = null;

    protected $deviceName = null;

    public function __construct()
    {

    }

    public function On()
    {
        // TODO: Implement On() method.
    }

    public function Off()
    {
        // TODO: Implement Off() method.
    }
}