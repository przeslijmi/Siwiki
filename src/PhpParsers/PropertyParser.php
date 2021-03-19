<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\PhpParsers;

use Przeslijmi\Siwiki\PhpParsers\CommentsParser;
use stdClass;

/**
 * Parses one property of a PHP class into JSON documentation.
 */
class PropertyParser
{

    /**
     * Contents of a PHP class file.
     *
     * @var string
     */
    private $contentsFile;

    /**
     * Parsed JSON with documentation of this property.
     *
     * @var stdClass
     */
    private $json;

    /**
     * Constructor.
     *
     * @param string  $contentsFile Contents of a PHP class file.
     * @param integer $posStart     Position of start of this property inside PHP class file.
     * @param string  $scope        Is this property `private`, `protected` or `public`.
     * @param boolean $isStatic     Is this property `static`.
     * @param string  $name         Name of this property.
     */
    public function __construct(
        string $contentsFile,
        int $posStart,
        string $scope,
        bool $isStatic,
        string $name
    ) {

        // Save.
        $this->contentsFile = $contentsFile;

        // Prepare JSON.
        $this->json           = new stdClass();
        $this->json->scope    = $scope;
        $this->json->isStatic = $isStatic;
        $this->json->name     = $name;
        $this->json->posStart = $posStart;
    }

    /**
     * Parse this property documentation.
     *
     * @return stdClass
     */
    public function parse() : stdClass
    {

        // Find documentation for this property.
        $this->findDocumentation();

        return $this->json;
    }

    /**
     * Find documentation for this property.
     *
     * @return void
     */
    private function findDocumentation() : void
    {

        // Lvd.
        $negOffset   = ( $this->json->posStart - strlen($this->contentsFile) );
        $posDocStart = strrpos($this->contentsFile, '/**', $negOffset);
        $posDocEnd   = strrpos($this->contentsFile, ' */', $negOffset);

        // This comment ends too early - it is not a doc for this property.
        if (( $posDocEnd + 10 ) < $this->json->posStart) {
            return;
        }

        // Get comments.
        $comments = substr($this->contentsFile, $posDocStart, ( $posDocEnd - $posDocStart ));
        $comments = explode('[newLine]', $comments);
        $comments = array_slice($comments, 1, ( count($comments) - 2 ));

        // Create parser.
        $parser = new CommentsParser($comments);

        // Save its results.
        $this->json->comments = $parser->parse();
    }
}
