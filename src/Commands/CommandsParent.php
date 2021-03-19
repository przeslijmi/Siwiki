<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Commands;

use Przeslijmi\Siwiki\Siwiki;

/**
 * Parent for all Comands defining common elements of them.
 */
abstract class CommandsParent
{

    /**
     * Parent Siwiki object.
     *
     * @var Siwiki.
     */
    protected $siwiki;

    /**
     * Text in which command has to be served (md or html contents).
     *
     * @var string
     */
    protected $text;

    /**
     * Full command from md or html contents.
     *
     * @var string
     */
    protected $fullCommand;

    /**
     * Only middle part of command - params list.
     *
     * @var string
     */
    protected $command;

    /**
     * Constructor - defines properties.
     *
     * @param Siwiki $siwiki      Parent Siwiki object.
     * @param string $text        Text in which command has to be served (md or html contents).
     * @param string $fullCommand Full command from md or html contents.
     * @param string $command     Only middle part of command - params list.
     */
    public function __construct(Siwiki $siwiki, string $text, string $fullCommand, string $command)
    {

        // Saves.
        $this->siwiki      = $siwiki;
        $this->text        = $text;
        $this->fullCommand = $fullCommand;
        $this->command     = $command;
    }

    /**
     * Return `command` params as an array.
     *
     * Return pairs of key => value with params.
     *
     * @return array
     */
    public function getCommandArray() : array
    {

        // Lvd.
        $result = [];

        // Find params.
        preg_match_all('/([a-z]{3,})(:)([^;]+)(;?)/', $this->command, $elements);

        // Define them in result array.
        for ($i = 0; $i < count($elements[0]); ++$i) {
            $result[$elements[1][$i]] = $elements[3][$i];
        }

        return $result;
    }
}
