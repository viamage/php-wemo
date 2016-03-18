# php-wemo
PHP library to control Wemo devices (Work in progress)

Currently supports...

1. Wemo light bulb - on/off/dimming 
2. Wemo light switch - on/off
3. Wemo switch (socket) - on/off
4. Device discovery
5. Grouped devices under wemo bridge


Install:

Use composer...

<pre>
require:{
    "a15lam/php-wemo" : "dev-develop as dev-master"
}
</pre>

Usage:

<pre>
$lightSwitch = \a15lam\PhpWemo\Discovery::getDeviceByName('Bed Room Light'); // Use your wemo device name as they show on your wemo app. Supports grouped devices
$lightSwitch->On();
sleep(2); // Allow a moment to see the light turning on.
$lightSwitch->Off();
// Get switch status
echo $lightSwitch->status();
</pre>

Check the example directory for more usage. Run example/console.php to control your devices.