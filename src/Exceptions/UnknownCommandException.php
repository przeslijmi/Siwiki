<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Exceptions;

use Exception;
use Throwable;

/**
 * A command that is unknown is called.
 */
class UnknownCommandException extends Exception
{

    /**
     * Construct.
     *
     * @param string $mdFile  MD file in which command has been called.
     * @param string $command Unknown command.
     */
    public function __construct(string $mdFile, string $command)
    {

        parent::__construct(sprintf('Unknow command `%s` is called in `%s`', $command, $mdFile));
    }
}
