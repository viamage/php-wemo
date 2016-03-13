<?php

namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Discovery;
use a15lam\PhpWemo\WemoClient;
use a15lam\PhpWemo\Config;

class BaseDevice
{
    protected $ip = null;

    protected $port = null;
    /** @type \a15lam\PhpWemo\WemoClient|null  */
    protected $client = null;

    protected $services = [];

    public function __construct($id, $port = null)
    {
        $this->ip = (static::isIp($id))? $id : static::getDeviceIpById($id);
        $this->port = (!empty($port))? $port : Config::get('port');
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

    protected static function isIp($ip)
    {
        $segments = explode('.', $ip);

        if(count($segments) === 4){
            foreach($segments as $segment){
                if(!is_numeric($segment)){
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    protected static function getDeviceIpById($id)
    {
        $devices = Discovery::find();

        foreach($devices as $device){
            if($id === $device['id']){
                return $device['ip'];
            }
        }

        throw new \Exception('Invalid device id supplied. No device found by id '.$id);
    }
}