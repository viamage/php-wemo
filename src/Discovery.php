<?php
namespace a15lam\PhpWemo;

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
            $infos[] = [
                'id'           => str_replace(' ', '_', strtolower($info['friendlyName'])),
                'ip'           => $ip,
                'deviceType'   => $info['deviceType'],
                'friendlyName' => $info['friendlyName'],
                'modelName'    => $info['modelName'],
                'UDN'          => $info['UDN']
            ];
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
}