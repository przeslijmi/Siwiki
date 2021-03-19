<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Commands;

/**
 * Interface for all Comands.
 */
interface CommandsInterface
{

    /**
     * Parses command.
     *
     * @return string
     */
    public function parse() : string;
}
