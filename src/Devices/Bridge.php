<?php

namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Config;
use a15lam\PhpWemo\WemoClient;

class Bridge extends BaseDevice
{
    protected $ip = null;

    protected $port = null;

    protected $client = null;

    protected $services = [
        'BridgeService' => [
            'serviceType' => 'urn:Belkin:service:bridge:1',
            'serviceId'   => 'urn:Belkin:serviceId:bridge1',
            'controlURL'  => '/upnp/control/bridge1',
            'eventSubURL' => '/upnp/event/bridge1',
            'SCPDURL'     => '/bridgeservice.xml'
        ]
    ];

    public function __construct($ip, $port = Config::PORT)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->client = new WemoClient($this->ip);
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function getUDN()
    {
        $rs = $this->client->info('setup.xml');

        if (is_array($rs) && isset($rs['root'])) {
            return $rs['root']['device']['UDN'];
        }
    }

    public function getPairedDevices()
    {
        $service = $this->services['BridgeService']['serviceType'];
        $controlUrl = $this->services['BridgeService']['controlURL'];
        $method = 'GetEndDevices';
        $arguments = [
            'DevUDN'      => $this->getUDN(),
            'ReqListType' => 'PAIRED_LIST'
        ];

        $rs = $this->client->request($controlUrl, $service, $method, $arguments);
        $rs = $this->unwrapResponse($rs);
        $rs = WemoClient::xmlToArray($rs['u:GetEndDevicesResponse']['DeviceLists']);

        return $rs['DeviceLists']['DeviceList']['DeviceInfos']['DeviceInfo'];
    }

    public function getDeviceIdByFriendlyName($name)
    {
        $devices = $this->getPairedDevices();

        foreach ($devices as $device) {
            if (strtolower($device['FriendlyName']) === strtolower($name)) {
                return $device['DeviceID'];
            }
        }

        throw new \Exception('No device found by name ' . $name);
    }

    public function bulbOn($deviceId)
    {
        return ($this->setBulbState($deviceId, '255'))? '1' : false;
    }

    public function bulbOff($deviceId)
    {
        return ($this->setBulbState($deviceId, '0'))? '0' : false;
    }

    public function setBulbState($deviceId, $level)
    {
        $service = $this->services['BridgeService']['serviceType'];
        $controlUrl = $this->services['BridgeService']['controlURL'];
        $method = 'SetDeviceStatus';
        $arguments = [
            'DeviceStatusList' => '&lt;?xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot;?&gt;&lt;DeviceStatusList&gt;&lt;DeviceStatus&gt;&lt;IsGroupAction&gt;NO&lt;/IsGroupAction&gt;&lt;DeviceID
available=&quot;YES&quot;&gt;'.$deviceId.'&lt;/DeviceID&gt;&lt;CapabilityID&gt;10006,10008,30008,30009,3000A&lt;/CapabilityID&gt;&lt;CapabilityValue&gt;0,'.$level.':0,,,&lt;/CapabilityValue&gt;&lt;LastEventTimeStamp&gt;0&lt;/LastEventTimeStamp&gt;&lt;/DeviceStatus&gt;&lt;/DeviceStatusList&gt;'
        ];

        $rs = $this->client->request($controlUrl, $service, $method, $arguments);
        $rs = $this->unwrapResponse($rs);

        if(isset($rs['s:Fault'])){
            throw new \Exception('Failed to change bulb state. '.print_r($rs, true));
        }

        return true;
    }
}