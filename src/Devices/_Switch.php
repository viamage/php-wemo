<?php

namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;
use a15lam\PhpWemo\WemoClient;
use a15lam\PhpWemo\Config;

class _Switch extends BaseDevice implements DeviceInterface
{
    protected $ip = null;

    protected $port = null;

    protected $client = null;

    protected $services = [
        'BridgeService' => [
            'serviceType' => 'urn:Belkin:service:basicevent:1',
            'serviceId'   => 'urn:Belkin:serviceId:basicevent1',
            'controlURL'  => '/upnp/control/basicevent1',
            'eventSubURL' => '/upnp/event/basicevent1',
            'SCPDURL'     => '/eventservice.xml'
        ]
    ];

    public function __construct($ip, $port = Config::PORT)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->client = new WemoClient($this->ip);
    }

    public function On()
    {
        return ($this->setState(1))? '1' : false;
    }

    public function Off()
    {
        return ($this->setState(0))? '0' : false;
    }

    protected function setState($state)
    {
        $service = $this->services['BridgeService']['serviceType'];
        $controlUrl = $this->services['BridgeService']['controlURL'];
        $method = 'SetBinaryState';
        $arguments = [
            'BinaryState' => $state
        ];

        $rs = $this->client->request($controlUrl, $service, $method, $arguments);
        $rs = $this->unwrapResponse($rs);

        if(isset($rs['s:Fault'])){
            throw new \Exception('Failed to change switch state. '.print_r($rs, true));
        }

        return true;
    }
}