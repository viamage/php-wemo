<?php

namespace a15lam\PhpWemo\Traits;

/**
 * Class Dimmable
 *
 * Dimmable trait for Wemo Bulbs
 *
 * @package a15lam\PhpWemo\Traits
 */
trait Dimmable{
    /**
     * Dims Wemo bulb to specified percentage
     *
     * @param $percent integer
     *
     * @return mixed
     */
    public function dim($percent)
    {
        if($percent < 0){
            $percent = 0;
        } else if($percent > 100){
            $percent = 100;
        }

        $level = round((255*$percent)/100);

        return $this->bridge->setDeviceStatus($this->deviceId, $level);
    }
}