<?php
namespace a15lam\PhpWemo;

class WemoClient
{
    protected $ip = null;

    protected $port = null;

    public function __construct($ip, $port = '49153')
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function request($controlUrl, $service, $method, $arguments = [])
    {
        $controlUrl = ltrim($controlUrl, '/');
        $url = 'http://'.$this->ip.'/'.$controlUrl;
        $action = $service.'#'.$method;

        $xmlHeader = '<?xml version="1.0" encoding="utf-8"?>
                        <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                            <s:Body>
                                <u:'.$method.' xmlns:u="'.$service.'">';

        $xmlFooter = '</u:'.$method.'></s:Body></s:Envelope>';

        $xmlBody = '';

        foreach($arguments as $key => $value){
            $xmlBody .= '<'.$key.'>'.$value.'</'.$key.'>';
        }

        $xml = $xmlHeader.$xmlBody.$xmlFooter;

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_PORT => $this->port,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $xml,
            CURLOPT_VERBOSE => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type:text/xml',
                'SOAPACTION:"'.$action.'"'
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        return curl_exec($ch);
    }
}

$wc = new WemoClient('192.168.1.69');
$rs = $wc->request(
    'upnp/control/bridge1',
    'urn:Belkin:service:bridge:1',
    'SetDeviceStatus',
    ['DeviceStatusList' => '&lt;?xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot;?&gt;&lt;DeviceStatusList&gt;&lt;DeviceStatus&gt;&lt;IsGroupAction&gt;NO&lt;/IsGroupAction&gt;&lt;DeviceID
available=&quot;YES&quot;&gt;B4750E1B95783E51&lt;/DeviceID&gt;&lt;CapabilityID&gt;10006,10008,30008,30009,3000A&lt;/CapabilityID&gt;&lt;CapabilityValue&gt;0,255:0,,,&lt;/CapabilityValue&gt;&lt;LastEventTimeStamp&gt;0&lt;/LastEventTimeStamp&gt;&lt;/DeviceStatus&gt;&lt;/DeviceStatusList&gt;']
);
print $rs;