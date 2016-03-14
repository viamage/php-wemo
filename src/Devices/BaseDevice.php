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

    public function info($resource = 'setup.xml')
    {
        return $this->client->info($resource);
    }

    public function getUDN($refresh = false)
    {
        if($refresh === false) {
            $device = static::lookupDevice('ip', $this->ip);
            if (isset($device['UDN'])) {
                return $device['UDN'];
            }
        }

        $rs = $this->client->info('setup.xml');

        if (is_array($rs) && isset($rs['root'])) {
            return $rs['root']['device']['UDN'];
        }

        throw new \Exception('UDN not found for device with ip address '.$this->ip);
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
        $device = static::lookupDevice('id', $id);
        if(isset($device['ip'])){
            return $device['ip'];
        }
        throw new \Exception('Invalid device id supplied. No device found by id '.$id);
    }

    protected static function lookupDevice($key, $value)
    {
        $devices = Discovery::find();

        foreach($devices as $device){
            if($value === $device[$key]){
                return $device;
            }
        }

        throw new \Exception('Device not found for '.$key.' = '.$value);
    }
}