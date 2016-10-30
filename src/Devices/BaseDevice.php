<?php

namespace a15lam\PhpWemo\Devices;

use a15lam\PhpWemo\Discovery;
use a15lam\PhpWemo\WemoClient;
use a15lam\PhpWemo\Workspace as WS;

/**
 * Class BaseDevice
 *
 * @package a15lam\PhpWemo\Devices
 */
class BaseDevice
{
    /** @type string */
    protected $ip = null;

    /** @type \a15lam\PhpWemo\WemoClient|null */
    protected $client = null;

    /** @type array */
    protected $services = [];

    /**
     * BaseDevice constructor.
     *
     * @param string $id Device ip or id
     * @param null   $port
     */
    public function __construct($id, $port = null)
    {
        $this->ip = (self::isIp($id)) ? $id : static::getDeviceIpById($id);
        $port = (!empty($port)) ? $port : WS::config()->get('port');
        $this->client = new WemoClient($this->ip, $port);
    }

    /**
     * @param string $resource
     *
     * @return array|string
     */
    public function info($resource = 'setup.xml')
    {
        return $this->client->info($resource);
    }

    /**
     * @param bool $refresh
     *
     * @return mixed
     * @throws \Exception
     */
    public function getUDN($refresh = false)
    {
        if ($refresh === false) {
            $device = Discovery::lookupDevice('ip', $this->ip);
            if (isset($device['UDN'])) {
                return $device['UDN'];
            }
        }

        $rs = $this->client->info('setup.xml');

        if (is_array($rs) && isset($rs['root'])) {
            return $rs['root']['device']['UDN'];
        }

        throw new \Exception('UDN not found for device with ip address ' . $this->ip);
    }

    /**
     * @param $state
     *
     * @return array|string
     * @throws \Exception
     */
    protected function setBinaryState($state)
    {
        $service = $this->services['BridgeService']['serviceType'];
        $controlUrl = $this->services['BridgeService']['controlURL'];
        $method = 'SetBinaryState';
        $arguments = [
            'BinaryState' => $state
        ];

        $rs = $this->client->request($controlUrl, $service, $method, $arguments);
        $rs = $this->unwrapResponse($rs);

        if (isset($rs['s:Fault'])) {
            throw new \Exception('Failed to change switch state. ' . print_r($rs, true));
        }

        return $rs;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getBinaryState()
    {
        $service = $this->services['BridgeService']['serviceType'];
        $controlUrl = $this->services['BridgeService']['controlURL'];
        $method = 'GetBinaryState';

        $rs = $this->client->request($controlUrl, $service, $method);
        $rs = $this->unwrapResponse($rs);

        if (isset($rs['s:Fault'])) {
            throw new \Exception('Failed to change switch state. ' . print_r($rs, true));
        }

        return $rs['u:GetBinaryStateResponse']['BinaryState'];
    }

    /**
     * @param array $response
     *
     * @return mixed
     * @throws \Exception
     */
    protected function unwrapResponse(array $response)
    {
        try {
            return $response['s:Envelope']['s:Body'];
        } catch (\Exception $e) {
            throw new \Exception('Failed to unwrap response. ' .
                $e->getMessage() .
                ' Response: ' .
                print_r($response, true));
        }
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    protected static function isIp($ip)
    {
        $segments = explode('.', $ip);

        if (count($segments) === 4) {
            foreach ($segments as $segment) {
                if (!is_numeric($segment)) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    protected static function getDeviceIpById($id)
    {
        $device = Discovery::lookupDevice('id', $id);
        if (isset($device['ip'])) {
            return $device['ip'];
        }
        throw new \Exception('Invalid device id supplied. No device found by id ' . $id);
    }
}