<?php

namespace a15lam\PhpWemo\Devices;

class BaseDevice
{
    protected function unwrapResponse(array $response)
    {
        try{
            return $response['s:Envelope']['s:Body'];
        } catch (\Exception $e){
            throw new \Exception('Failed to unwrap response. '.$e->getMessage().' Response: '.print_r($response, true));
        }
    }
}