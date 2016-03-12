<?php

$client = new SoapClient(null, ['location'=>'http://192.168.1.68:49153/upnp/control/basicevent1', 'uri'=>'http://192.168.1.68:49153']);

$location = "http://192.168.1.68:49153/upnp/control/basicevent1";
$action = "urn:Belkin:service:basicevent:1#GetBinaryState";
$xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
    <s:Body>
        <u:GetBinaryState xmlns:u="urn:Belkin:service:basicevent:1">
            <BinaryState></BinaryState>
        </u:GetBinaryState>
    </s:Body>
</s:Envelope>
XML;

$response = $client->__doRequest($xml, $location,   $action, 1, false);

print $response;