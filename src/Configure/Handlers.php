<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Configure;

use Przeslijmi\Siwiki\Exceptions\ErrorException;
use Exception;

/**
 * Catches warning and returns exception.
 *
 * @phpcs:disable Squiz.Commenting.ClassComment
 *
 * @codeCoverageIgnore
 */
class Handlers
{

    /**
     * Catches warning and returns exception.
     *
     * @param integer $errno   Error level.
     * @param string  $errstr  Error description.
     * @param string  $errfile In which file error has occured.
     * @param integer $errline In which line error has occured.
     *
     * @throws ErrorException Every time is called.
     * @return void
     */
    public static function errorHandler(int $errno, string $errstr, string $errfile, int $errline) : void
    {

        throw new ErrorException($errno, $errstr, $errfile, $errline);
    }

    /**
     * Handles uncaught exception (when `$_ENV['PRZESLIJMI_SIWIKI_HANDLE_EXCEPTIONS']` is `true`).
     *
     * @param Exception $exc Exception that was thrown.
     *
     * @return void
     */
    public static function exceptionHandler(Exception $exc) : void
    {

        echo PHP_EOL;
        echo 'Uncaught exception: ' . $exc->getMessage();
        echo PHP_EOL;
        echo PHP_EOL;
        echo 'WORK DISRUPTED';
        echo PHP_EOL;
        echo PHP_EOL;
    }
}
