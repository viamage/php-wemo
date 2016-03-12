<?php
namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;
use a15lam\PhpWemo\Traits\Dimmable;

class WemoBulb implements DeviceInterface
{
    use Dimmable;

    /** @type \a15lam\PhpWemo\Devices\Bridge|null  */
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