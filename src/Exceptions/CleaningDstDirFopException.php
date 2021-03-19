<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Exceptions;

use Exception;
use Throwable;

/**
 * Cleaning destination dir somewhow failed (see cause).
 *
 * @phpcs:disable Squiz.Commenting.ClassComment
 *
 * @codeCoverageIgnore
 */
class CleaningDstDirFopException extends Exception
{

    /**
     * Construct.
     *
     * @param string         $uri   Source or destination.
     * @param Throwable|null $cause Cause.
     */
    public function __construct(string $uri, Throwable $cause = null)
    {

        parent::__construct('Cleaning destination dir `' . $uri . '` could not be finished.', 0, $cause);
    }
}
