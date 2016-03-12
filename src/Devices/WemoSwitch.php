<?php

namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;

class WemoSwitch extends BaseDevice implements DeviceInterface
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

    public function On()
    {
        return ($this->setBinaryState(1))? '1' : false;
    }

    public function Off()
    {
        return ($this->setBinaryState(0))? '0' : false;
    }
}