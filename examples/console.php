<?php
require __DIR__ . '/../vendor/autoload.php';

echo "\n\n-------------------------------------------------".PHP_EOL;
echo "| PHP-WEMO Console ".PHP_EOL;
echo "-------------------------------------------------".PHP_EOL;

$refresh = (isset($argv[1]))? $argv[1] : false;

if($refresh){
    echo "Searching for Wemo devices...".PHP_EOL;
}
$devices = \a15lam\PhpWemo\Discovery::find($refresh);

if(count($devices)>0) {
    while(true) {
        echo "Your Wemo devices...\n" . PHP_EOL;

        $list = [];
        foreach ($devices as $device) {
            if ($device['deviceType'] === 'urn:Belkin:device:bridge:1') {
                foreach ($device['device'] as $d) {
                    $list[] = $device['id'] . '.' . $d['id'];
                }
            } else {
                $list[] = $device['id'];
            }
        }

        foreach ($list as $i => $l) {
            echo "[$i] $l" . PHP_EOL;
        }

        $choice = -1;

        while (!isset($list[$choice])) {
            if ($choice !== -1) {
                echo "Invalid choice. Please select from 0 to " . (count($list) - 1) . PHP_EOL;
            }
            $choice =
                trim(readline("\nPlease select a Wemo device that you would like to control (0..." .
                    (count($list) - 1) .
                    "): "));
        }

        $chosen = explode('.', $list[$choice]);
        $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $chosen[0]);
        $deviceClass = $device['class_name'];
        $myDevice = null;

        if ($deviceClass === \a15lam\PhpWemo\Devices\Bridge::class) {
            $bridgeDevices = $device['device'];
            $bridgeDevice = [];
            foreach ($bridgeDevices as $bd) {
                if ($bd['id'] === $chosen[1]) {
                    $bridgeDevice = $bd;
                    break;
                }
            }

            $deviceType = $bridgeDevice['productName'];
            if ('Lighting' === $deviceType) {
                $myDevice = new \a15lam\PhpWemo\Devices\WemoBulb($chosen[0], $chosen[1]);
            } else {
                echo "\nYour Wemo Link device $chosen[1] is not currently supported by PHP-WEMO" . PHP_EOL;
                exit();
            }

            echo "\nOptions..." . PHP_EOL;

            echo "[On]" . PHP_EOL;
            echo "[Off]" . PHP_EOL;
            echo "[Dim:%]" . PHP_EOL;

            $success = false;
            while (!$success) {
                $operation = explode(':', trim(strtolower(readline("\nWhat would you like to do with $chosen[1]? "))));

                if ('on' === $operation[0]) {
                    $success = ($myDevice->On() === false)? false : true;
                } else if ('off' === $operation[0]) {
                    $success = ($myDevice->Off() === false)? false : true;
                } else if ('dim' === $operation[0] && isset($operation[1])) {
                    $success = $myDevice->dim($operation[1]);
                }
            }
        } else {
            /** @type \a15lam\PhpWemo\Contracts\DeviceInterface $myDevice */
            $myDevice = new $deviceClass($chosen[0]);

            echo "\nOptions..." . PHP_EOL;

            echo "[On]" . PHP_EOL;
            echo "[Off]" . PHP_EOL;

            $success = false;
            while (!$success) {
                $operation = trim(strtolower(readline("\nWhat would you like to do with $chosen[0]? ")));

                if ('on' === $operation) {
                    $success = ($myDevice->On() === false)? false : true;
                } else if ('off' === $operation) {
                    $success = ($myDevice->Off() === false)? false : true;
                }
            }
        }

        echo "\nOperation successful.".PHP_EOL;
        echo "-------------------------------------------------\n".PHP_EOL;
    }
} else {
    echo "Could not find any Wemo devices in your network. You may try again.";
}
