<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Commands\Html;

use Przeslijmi\Siwiki\Commands\CommandsInterface;
use Przeslijmi\Siwiki\Commands\CommandsParent;

/**
 * Serves for command `html.addClassToTag`.
 */
class CopyH1 extends CommandsParent implements CommandsInterface
{

    /**
     * Parses command.
     *
     * @return string
     */
    public function parse() : string
    {

        // Lvd.
        $header = '';

        // Find H1 contents.
        $preStart = strpos($this->text, '<h1');
        $posStart = strpos($this->text, '>', $preStart);
        $posEnd   = strpos($this->text, '</h1>', $posStart);

        // Cut header.
        if ($preStart !== false) {
            $header = substr($this->text, ( $posStart + 1 ));
            $header = substr($header, 0, ( $posEnd - $posStart - 1 ));
            $header = htmlentities(trim($header));
        }

        // Make repleacement.
        $this->text = str_replace($this->fullCommand, $header, $this->text);

        return $this->text;
    }
}
