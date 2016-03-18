<?php
namespace a15lam\PhpWemo\Contracts;

interface DeviceInterface{
    public function On();

    public function Off();
    
    public function state();
}