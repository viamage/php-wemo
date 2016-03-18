<?php

namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;

/**
 * Class LightSwitch
 *
 * @package a15lam\PhpWemo\Devices
 */
class LightSwitch extends BaseDevice implements DeviceInterface
{
    protected $services = [
        'BridgeService' => [
            'serviceType' => 'urn:Belkin:service:basicevent:1',
            'serviceId'   => 'urn:Belkin:serviceId:basicevent1',
            'controlURL'  => '/upnp/control/basicevent1',
            'eventSubURL' => '/upnp/event/basicevent1',
            'SCPDURL'     => '/eventservice.xml'
        ]
    ];

    /**
     * Turns on switch
     *
     * @return bool|string
     * @throws \Exception
     */
    public function On()
    {
        return ($this->setBinaryState(1))? '1' : false;
    }

    /**
     * Turns off switch
     *
     * @return bool|string
     * @throws \Exception
     */
    public function Off()
    {
        return ($this->setBinaryState(0))? '0' : false;
    }

    /**
     * Returns switch state
     * 
     * @return mixed
     * @throws \Exception
     */
    public function state()
    {
        return $this->getBinaryState();
    }
}