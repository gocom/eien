<?php

error_reporting(E_ALL);

set_error_handler(function ($errno, $errstr, $errfile, $errline)
{
    throw new Exception($errstr . ' in ' . $errfile . ' on line ' . $errline);
});

include dirname(dirname(__FILE__)) . '/vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Rah_Eien_Test_', dirname(__FILE__));
$loader->register();
