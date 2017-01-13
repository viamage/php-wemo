<?php

namespace a15lam\PhpWemo\Contracts;

interface ClientInterface
{
    public function info($url);

    public function request($controlUrl, $service = null, $method = null, $arguments = []);
}