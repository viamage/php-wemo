<?php
include_once('WemoClient.php');
use a15lam\PhpWemo\WemoClient;

$wc = new WemoClient('192.168.1.69');
$rs = $wc->request(
    'upnp/control/bridge1',
    'urn:Belkin:service:bridge:1',
    'SetDeviceStatus',
    ['DeviceStatusList' => '&lt;?xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot;?&gt;&lt;DeviceStatusList&gt;&lt;DeviceStatus&gt;&lt;IsGroupAction&gt;NO&lt;/IsGroupAction&gt;&lt;DeviceID
available=&quot;YES&quot;&gt;B4750E1B95783E51&lt;/DeviceID&gt;&lt;CapabilityID&gt;10006,10008,30008,30009,3000A&lt;/CapabilityID&gt;&lt;CapabilityValue&gt;0,0:0,,,&lt;/CapabilityValue&gt;&lt;LastEventTimeStamp&gt;0&lt;/LastEventTimeStamp&gt;&lt;/DeviceStatus&gt;&lt;/DeviceStatusList&gt;']
);
print $rs;