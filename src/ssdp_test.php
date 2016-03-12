<?php
require __DIR__ . '/../vendor/autoload.php';

$devices = \a15lam\PhpWemo\Discovery::find();
print_r($devices);
