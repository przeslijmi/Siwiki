<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Commands\Html;

use Przeslijmi\Siwiki\Commands\CommandsInterface;
use Przeslijmi\Siwiki\Commands\CommandsParent;

/**
 * Serves for command `html.addClassToTag`.
 */
class AddClassToTag extends CommandsParent implements CommandsInterface
{

    /**
     * Parses command.
     *
     * @return string
     */
    public function parse() : string
    {

        // Lvd.
        $tag   = $this->getCommandArray()['tag'];
        $class = $this->getCommandArray()['class'];

        // Make repleacement.
        $this->text = str_replace($this->fullCommand, '', $this->text);
        $this->text = str_replace('<' . $tag . '>', '<' . $tag . ' class="' . $class . '">', $this->text);

        return $this->text;
    }
}
