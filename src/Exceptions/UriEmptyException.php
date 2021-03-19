<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Exceptions;

use Exception;
use Throwable;

/**
 * Exception of empty uri (source or destination).
 */
class UriEmptyException extends Exception
{

    /**
     * Construct.
     *
     * @param string         $type  Source or destination.
     * @param Throwable|null $cause Cause.
     */
    public function __construct(string $type, Throwable $cause = null)
    {

        parent::__construct('Given ' . $type . ' dir uri is empty.', 0, $cause);
    }
}
