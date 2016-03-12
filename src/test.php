<?php
include_once('Config.php');
include_once('WemoClient.php');
include_once('Devices/BaseDevice.php');
include_once('Devices/Bridge.php');
include_once('Contracts/DeviceInterface.php');
include_once('Traits/Dimmable.php');
include_once('Devices/WemoBulb.php');
include_once('Devices/LightSwitch.php');
include_once('Devices/WemoSwitch.php');


$bridge = new \a15lam\PhpWemo\Devices\Bridge('192.168.1.69');
$bulb1 = new \a15lam\PhpWemo\Devices\WemoBulb($bridge, 'media room 1');
$bulb2 = new \a15lam\PhpWemo\Devices\WemoBulb($bridge, 'media room 2');

//$bulb1->dim(10);
//sleep(1);
//$bulb1->dim(20);
//sleep(1);
//$bulb1->dim(50);
//sleep(1);
//$bulb1->dim(80);
//sleep(1);
//$bulb1->dim(100);
//sleep(1);
//$bulb1->Off();
//sleep(2);

//$bulb1->On();
//sleep(2);
//$bulb2->On();
//sleep(2);
//$bulb1->Off();
//sleep(2);
//$bulb2->Off();
//sleep(2);

//$switch = new \a15lam\PhpWemo\Devices\LightSwitch('192.168.1.68');
//$switch->On();
//sleep(2);
//$switch->Off();

$switch = new \a15lam\PhpWemo\Devices\WemoSwitch('192.168.1.71');
$switch->On();
sleep(2);
$switch->Off();
