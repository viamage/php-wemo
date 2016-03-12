<?php
include_once('Config.php');
include_once('WemoClient.php');
include_once('Devices/BaseDevice.php');
include_once('Devices/Bridge.php');
include_once('Contracts/DeviceInterface.php');
include_once('Devices/Bulb.php');


$bridge = new \a15lam\PhpWemo\Devices\Bridge('192.168.1.69');
$bulb1 = new \a15lam\PhpWemo\Devices\Bulb($bridge, 'Media room 1');
$bulb2 = new \a15lam\PhpWemo\Devices\Bulb($bridge, 'Media room 2');

$bulb1->On();
sleep(2);
$bulb2->On();
sleep(2);
$bulb1->Off();
sleep(2);
$bulb2->Off();

