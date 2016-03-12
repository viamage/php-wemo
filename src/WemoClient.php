<?php
namespace a15lam\PhpWemo;

class WemoClient
{
    protected $ip = null;

    protected $port = null;

    public function __construct($ip, $port = '49153')
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function request($controlUrl, $service, $method, $arguments = [])
    {
        $controlUrl = ltrim($controlUrl, '/');
        $url = 'http://' . $this->ip . '/' . $controlUrl;
        $action = $service . '#' . $method;

        $xmlHeader = '<?xml version="1.0" encoding="utf-8"?>
                      <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                      <s:Body>
                      <u:' . $method . ' xmlns:u="' . $service . '">';

        $xmlFooter = '</u:' . $method . '></s:Body></s:Envelope>';

        $xmlBody = '';

        foreach ($arguments as $key => $value) {
            $xmlBody .= '<' . $key . '>' . $value . '</' . $key . '>';
        }

        $xml = $xmlHeader . $xmlBody . $xmlFooter;

        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_PORT           => $this->port,
            CURLOPT_POSTFIELDS     => $xml,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE        => false,
            CURLOPT_HTTPHEADER     => [
                'Content-Type:text/xml',
                'SOAPACTION:"' . $action . '"'
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        return curl_exec($ch);
    }
}