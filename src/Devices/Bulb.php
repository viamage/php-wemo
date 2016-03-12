<?php
namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;

class Bulb extends  BaseDevice implements DeviceInterface
{
    protected $bridge = null;

    protected $deviceId = null;

    protected $deviceName = null;

    public function __construct(Bridge $bridge, $deviceName = null, $deviceId = null)
    {
        $this->bridge = $bridge;
        if(!empty($deviceId)){
            $this->deviceId = $deviceId;
        } else if(!empty($deviceName)) {
            $this->deviceId = $this->bridge->getDeviceIdByFriendlyName($deviceName);
        } else {
            throw new \Exception('No device name or id provided.');
        }
    }

    public function On()
    {
        $this->bridge->bulbOn($this->deviceId);
    }

    public function Off()
    {
        $this->bridge->bulbOff($this->deviceId);
    }
}