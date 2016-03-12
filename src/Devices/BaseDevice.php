<?php

namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\WemoClient;
use a15lam\PhpWemo\Config;

class BaseDevice
{
    protected $ip = null;

    protected $port = null;
    /** @type \a15lam\PhpWemo\WemoClient|null  */
    protected $client = null;

    protected $services = [];

    public function __construct($ip, $port = Config::PORT)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->client = new WemoClient($this->ip);
    }

    protected function setBinaryState($state)
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

    protected function unwrapResponse(array $response)
    {
        try{
            return $response['s:Envelope']['s:Body'];
        } catch (\Exception $e){
            throw new \Exception('Failed to unwrap response. '.$e->getMessage().' Response: '.print_r($response, true));
        }
    }
}