<?php
namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;
use a15lam\PhpWemo\Traits\Dimmable;

/**
 * Class WemoBulb
 *
 * @package a15lam\PhpWemo\Devices
 */
class WemoBulb implements DeviceInterface
{
    use Dimmable;

    /** @type \a15lam\PhpWemo\Devices\Bridge|null */
    protected $bridge = null;

    /** @type string null */
    protected $deviceId = null;

    /**
     * WemoBulb constructor.
     *
     * @param string $bridgeId Wemo bridge ip or id
     * @param string $deviceId Bridge device id from discovery.
     * @param string $port
     *
     * @throws \Exception
     */
    public function __construct($bridgeId, $deviceId = null, $port = null)
    {
        $this->bridge = new Bridge($bridgeId, $port);

        if (!empty($deviceId)) {
            $this->deviceId = $this->bridge->getDeviceIdByCustomId($deviceId);

            if (empty($this->deviceId)) {
                throw new \Exception('No bridge device found using id ' . $deviceId);
            }
        }

        if (empty($this->deviceId)) {
            throw new \Exception('No device name or id provided.');
        }
    }

    /**
     * Turns on bulb
     *
     * @return bool|string
     */
    public function On()
    {
        return $this->bridge->bulbOn($this->deviceId);
    }

    /**
     * Turns off bulb
     *
     * @return bool|string
     */
    public function Off()
    {
        return $this->bridge->bulbOff($this->deviceId);
    }

    /**
     * Returns bulb state
     * 
     * @return mixed
     */
    public function state(){
        $currentState = $this->bridge->getBulbState($this->deviceId);
        $state = $currentState[0];
        return $state;
    }

    /**
     * Indicates if device is dimmable or not.
     *
     * @return bool
     */
    public function isDimmable()
    {
        return true;
    }
}