<?php
namespace a15lam\PhpWemo;

use Clue\React\Ssdp\Client;
use React\EventLoop\Factory;

class Discovery
{
    protected static $output = [];

    public static function find()
    {
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

        return static::getDeviceInfo(static::$output);
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
                'ip'           => $ip,
                'deviceType'   => $info['deviceType'],
                'friendlyName' => $info['friendlyName'],
                'modelName'    => $info['modelName'],
                'UDN'          => $info['UDN'],
                'binaryState'  => $info['binaryState']
            ];
        }

        return $infos;
    }
}