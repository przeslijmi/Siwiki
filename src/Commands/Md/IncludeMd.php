<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Commands\Md;

use Przeslijmi\Siwiki\Commands\CommandsInterface;
use Przeslijmi\Siwiki\Commands\CommandsParent;

/**
 * Serves for command `md.include`.
 */
class IncludeMd extends CommandsParent implements CommandsInterface
{

    /**
     * Parses command.
     *
     * @return string
     */
    public function parse() : string
    {

        // Lvd.
        $replaceUri  = $this->siwiki->getSrcUri() . $this->getCommandArray()['file'];
        $replaceWith = file_get_contents($replaceUri);

        // Make repleacement.
        $this->text = str_replace($this->fullCommand, $replaceWith, $this->text);

        return $this->text;
    }
}
