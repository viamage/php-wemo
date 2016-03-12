<?php
include_once('Config.php');
include_once('WemoClient.php');
include_once('Devices/BaseDevice.php');
include_once('Devices/Bridge.php');
include_once('Contracts/DeviceInterface.php');
include_once('Traits/Dimmable.php');
include_once('Devices/Bulb.php');
include_once('Devices/_Switch.php');


$bridge = new \a15lam\PhpWemo\Devices\Bridge('192.168.1.69');
$bulb1 = new \a15lam\PhpWemo\Devices\Bulb($bridge, 'media room 1');
$bulb2 = new \a15lam\PhpWemo\Devices\Bulb($bridge, 'media room 2');

$bulb1->dim(10);
sleep(1);
$bulb1->dim(20);
sleep(1);
$bulb1->dim(50);
sleep(1);
$bulb1->dim(80);
sleep(1);
$bulb1->dim(100);
sleep(1);
$bulb1->Off();

//$bulb1->On();
//sleep(2);
//$bulb2->On();
//sleep(2);
//$bulb1->Off();
//sleep(2);
//$bulb2->Off();
//sleep(2);
//
//$switch = new \a15lam\PhpWemo\Devices\_Switch('192.168.1.68');
//$switch->On();
//sleep(2);
//$switch->Off();
