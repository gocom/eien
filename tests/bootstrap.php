<?php

/**
 * Bootstrap file for tests.
 *
 * Initializes autoloader basically.
 * So that we can PSR-0 autoload the test
 * classes, and the library itself.
 */

error_reporting(E_ALL);

include dirname(dirname(__FILE__)) . '/vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Rah_Eien_Test_', dirname(__FILE__));
$loader->register();