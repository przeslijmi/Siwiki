<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\PhpParsers;

use stdClass;

/**
 * Parses comment for PHP class / property / method.
 */
class CommentsParser
{

    /**
     * Array (divided by lines) contents of a comment for PHP class / property / method.
     *
     * @var array
     */
    private $contents;

    /**
     * Parsed JSON with this documentation exploded.
     *
     * @var stdClass
     */
    private $json;

    /**
     * Constructor.
     *
     * @param array $contents Contents of comments to parse.
     */
    public function __construct(array $contents)
    {

        // Save.
        $this->contents = $contents;

        // Prepare json.
        $this->json           = new stdClass();
        $this->json->title    = '';
        $this->json->contents = '';
    }

    /**
     * Parse this property documentation.
     *
     * @return stdClass
     */
    public function parse() : stdClass
    {

        // Clean up the syntax.
        $this->cleanUp();

        // Define found title and rest of the contents.
        $this->json->title    = ( $this->contents[0] ?? '' );
        $this->json->contents = implode(PHP_EOL, array_slice($this->contents, 1));

        return $this->json;
    }

    /**
     * Cleans up the code - ie. removes asterixes from the beginning and empty lines - resulting in clean MD text.
     *
     * @return void
     */
    private function cleanUp() : void
    {

        // For every line.
        foreach ($this->contents as $lineId => $line) {

            // Test.
            preg_match('/( +)(\*)( )(.+)/', $line, $output);

            // First option - this is proper comment line.
            // Second option - sth is wrong - ignore this line.
            if (isset($output[4]) === true && empty($output[4]) === false) {
                $this->contents[$lineId] = $output[4];
            } else {
                unset($this->contents[$lineId]);
            }
        }
    }
}
