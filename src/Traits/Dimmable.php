<?php

namespace a15lam\PhpWemo\Traits;

trait Dimmable{
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