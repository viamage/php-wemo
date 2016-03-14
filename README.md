# php-wemo
PHP library to control Wemo devices (Work in progress)

Currently supports...

1. Wemo light bulb - on/off/dimming (no group support yet) 
2. Wemo light switch - on/off
3. Wemo switch (socket) - on/off
4. Device discovery
 
Next to support...

1. Wemo light bulb group - on/off/dimming


Install:

Use composer...

<pre>
require:{
    "a15lam/php-wemo" : "dev-develop as dev-master"
}
</pre>

Usage:

<pre>
$lightSwitch = \a15lam\PhpWemo\Discovery::getBaseDeviceByName('Bed Room Light'); // Use your wemo device name as they show on your wemo app
$lightSwitch->On();
sleep(2); // Allow a moment to see the light turning on.
$lightSwitch->Off();
</pre>