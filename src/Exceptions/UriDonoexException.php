<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Exceptions;

use Exception;
use Throwable;

/**
 * Exception of not existing uri (source or destination).
 */
class UriDonoexException extends Exception
{

    /**
     * Construct.
     *
     * @param string         $type  Source or destination.
     * @param Throwable|null $cause Cause.
     */
    public function __construct(string $type, Throwable $cause = null)
    {

        parent::__construct('Given ' . $type . ' dir uri is not existing.', 0, $cause);
    }
}
