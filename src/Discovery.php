<?php
namespace a15lam\PhpWemo;

use a15lam\PhpWemo\Devices\Bridge;
use Clue\React\Ssdp\Client;
use React\EventLoop\Factory;

class Discovery
{
    protected static $output = [];

    public static function find($refresh = false)
    {
        if($refresh === false){
            $devices = static::getDevicesFromStorage();
            if(!empty($devices)){
                return $devices;
            } else {
                $refresh = true;
            }
        }

        if($refresh) {
            $loop = Factory::create();
            $client = new Client($loop);
            $client->search('urn:Belkin:service:basicevent:1', 2)->then(
                function (){
                    //echo 'Search completed' . PHP_EOL;
                },
                function ($e){
                    throw new \Exception('Device discovery failed: ' . $e);
                },
                function ($progress){
                    static::$output[] = $progress;
                }
            );
            $loop->run();
        }

        $devices = static::getDeviceInfo(static::$output);
        static::setDevicesInStorage($devices);
        return $devices;
    }

    protected static function getDeviceInfo($devices)
    {
        $infos = [];
        foreach ($devices as $device) {
            $sender = $device['_sender'];
            $ip = substr($sender, 0, strpos($sender, ':'));
            $wc = new WemoClient($ip);
            $info = $wc->info('setup.xml');
            $info = $info['root']['device'];
            $data = [
                'id'           => str_replace(' ', '_', strtolower($info['friendlyName'])),
                'ip'           => $ip,
                'deviceType'   => $info['deviceType'],
                'friendlyName' => $info['friendlyName'],
                'modelName'    => $info['modelName'],
                'UDN'          => $info['UDN']
            ];

            if(static::isBridge($info['UDN'])){
                $bridge = new Bridge($ip);
                $devices = $bridge->getPairedDevices();

                foreach($devices as $i => $device){
                    $device['id'] = str_replace(' ', '_', strtolower($device['FriendlyName']));
                    $devices[$i] = $device;
                }

                $data['device'] = $devices;
            }

            $infos[] = $data;
        }

        return $infos;
    }

    protected static function setDevicesInStorage($devices)
    {
        try {
            $file = Config::get('device_storage');
            $json = json_encode($devices, JSON_UNESCAPED_SLASHES);
            @file_put_contents($file, $json);

            return true;
        } catch(\Exception $e) {
            return false;
        }
    }

    protected static function getDevicesFromStorage()
    {
        try {
            $file = Config::get('device_storage');
            $content = @file_get_contents($file);
            if(!empty($content)){
                $devices = json_decode($content, true);
                return $devices;
            } else {
                return null;
            }

        } catch (\Exception $e){
            return null;
        }
    }

    protected static function isBridge($udn){
        if(strpos($udn, 'uuid:Bridge-1') !== false){
            return true;
        }
        return false;
    }

    protected static function isLightSwitch($udn){
        if(strpos($udn, 'uuid:Lightswitch-1') !== false){
            return true;
        }
        return false;
    }

    protected static function isWemoSwitch($udn){
        if(strpos($udn, 'uuid:Socket-1') !== false){
            return true;
        }
        return false;
    }
}