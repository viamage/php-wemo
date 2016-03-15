<?php
require __DIR__ . '/../vendor/autoload.php';

//Run Discovery::find() to get device info. Use id to init devices.
//
//$bulb1 = new \a15lam\PhpWemo\Devices\WemoBulb('wemo_link', 'media_room_1');
//$bulb2 = new \a15lam\PhpWemo\Devices\WemoBulb('192.168.1.69', 'media_room_2');
//
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
//
//$bulb1->On();
//sleep(2);
//$bulb2->On();
//sleep(2);
//$bulb1->Off();
//sleep(2);
//$bulb2->Off();
//sleep(2);
//
//$switch = \a15lam\PhpWemo\Discovery::getBaseDeviceByName('Foyer Light');
////$switch = new \a15lam\PhpWemo\Devices\LightSwitch('192.168.1.68');
////$switch = new \a15lam\PhpWemo\Devices\LightSwitch('foyer_light');
//$switch->On();
//sleep(2);
//$switch->Off();
//
$switch = new \a15lam\PhpWemo\Devices\WemoSwitch('192.168.1.71');
$switch->On();
sleep(2);
$switch->Off();
sleep(2);
$switch->On();
