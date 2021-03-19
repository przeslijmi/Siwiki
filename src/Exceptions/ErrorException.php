<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Exceptions;

use Exception;
use Throwable;

/**
 * Exception just to pack-up error.
 *
 * @phpcs:disable Squiz.Commenting.ClassComment
 *
 * @codeCoverageIgnore
 */
class ErrorException extends Exception
{

    /**
     * Construct.
     *
     * @param integer $errno   Error level.
     * @param string  $errstr  Error description.
     * @param string  $errfile In which file error has occured.
     * @param integer $errline In which line error has occured.
     */
    public function __construct(int $errno, string $errstr, string $errfile, int $errline)
    {

        // Lvd.
        $message  = 'Error ' . $errstr . ' (#' . $errno . ') has been generated ';
        $message .= 'in `' . $errfile . '` (#' . $errline . ').';

        // Set.
        parent::__construct($message);
    }
}
