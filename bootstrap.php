<?php declare(strict_types=1);

/**
 * Simple env reader.
 *
 * @param array $array Env data from requiring file.
 *
 * @return void
 */
$readInEnv = function($envData) : void
{
    foreach ($envData as $envKey => $envValue) {
        $_ENV[$envKey] = $envValue;
    }
};

// Readin standard env.
$readInEnv(require(dirname(__FILE__) . '/.env.php'));

// Call autoloader.
require 'vendor/autoload.php';
