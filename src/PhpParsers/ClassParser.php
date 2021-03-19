<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\PhpParsers;

use Przeslijmi\Siwiki\PhpParsers\CommentsParser;
use Przeslijmi\Siwiki\PhpParsers\MethodParser;
use Przeslijmi\Siwiki\PhpParsers\PropertyParser;
use stdClass;

/**
 * Parses PHP class and calls to parse all its properties and methods.
 */
class ClassParser
{

    /**
     * Uri of the file with PHP class.
     *
     * @var string
     */
    private $fileUri;

    /**
     * Full contents of the file without new lines (changed to `[newLine]`).
     *
     * @var string
     */
    private $contents;

    /**
     * Parsed JSON with documentation of this class.
     *
     * @var stdClass
     */
    private $json;

    /**
     * Constructor.
     *
     * @param string $fileUri Uri to be parsed.
     */
    public function __construct(string $fileUri)
    {

        // Save.
        $this->fileUri  = $fileUri;
        $this->contents = file_get_contents($this->fileUri);
        $this->contents = str_replace([ "\r", "\n" ], [ '[newLine]', '[newLine]' ], $this->contents);

        // Generate json specimen.
        $this->json             = new stdClass();
        $this->json->uri        = $fileUri;
        $this->json->namespace  = null;
        $this->json->uses       = [];
        $this->json->class      = new stdClass();
        $this->json->properties = [];
        $this->json->methods    = [];

        // Find class name.
        $this->json->class->name = basename($this->fileUri, '.php');
    }

    /**
     * Parse file.
     *
     * @return stdClass
     */
    public function parse() : stdClass
    {

        // Parse meta info.
        $this->parseNamespace();
        $this->parseUses();
        $this->parseClassName();
        $this->parseClassComments();

        // Find properties.
        $this->findProperties();

        // Find methods.
        $this->findMethods();

        return $this->json;
    }

    /**
     * Parse namespaces (fills up `json->namespace` node).
     *
     * @return void
     */
    private function parseNamespace() : void
    {

        // Test.
        preg_match('/(namespace )([^;]+)(;)/', $this->contents, $output);

        // Save namespace.
        $this->json->namespace = ( $output[2] ?? null );
    }

    /**
     * Parse uses (fills up `json->uses` node).
     *
     * @return void
     */
    private function parseUses() : void
    {

        // Test.
        preg_match_all('/(\[newLine\])(use )([^ ;]+)( as | AS | aS | As )?([^ ;]+)?(;)/', $this->contents, $uses);

        // Parse.
        for ($i = 0; $i < count($uses[0]); ++$i) {

            // Lvd.
            $full  = explode('\\', $uses[3][$i]);
            $class = array_pop($full);
            $alias = ( ( empty($uses[5][$i]) === false ) ? $uses[5][$i] : $class );

            // Save.
            $this->json->uses[$alias] = $uses[3][$i];
        }
    }

    /**
     * Parse class name (fills up a lot in `json->class->` nodes).
     *
     * @return void
     */
    private function parseClassName() : void
    {

        // Test.
        preg_match(
            '/(\[newLine\])((abstract|final)( ))?(class|interface)( )(' . $this->json->class->name . ')([^{]+)?/',
            $this->contents,
            $output
        );

        // Save class info.
        $this->json->class->type       = ( ( $output[5] === 'class' ) ? 'class' : 'interface' );
        $this->json->class->isFinal    = ( ( $output[2] === 'final' ) ? true : false );
        $this->json->class->isAbstract = ( ( $output[2] === 'abstract' ) ? true : false );
        $this->json->class->extends    = null;
        $this->json->class->implements = [];

        // Shortcut.
        if (isset($output[8]) === false || empty(trim(str_replace('[newLine]', '', $output[8]))) === true) {
            return;
        }

        // Looks for position of `implements` and `extends`.
        $rels    = ' ' . trim(str_replace('[newLine]', '', $output[8])) . ' ';
        $posExt  = strpos($rels, ' extends ');
        $posImpl = strpos($rels, ' implements ');

        // Parse `implements` and `extends` if they are present.
        if ($posImpl === false && $posExt !== false) {
            // There is only extends.
            $this->json->class->extends = trim(substr($rels, ( $posExt + 9 )));
        } elseif ($posImpl !== false && $posExt === false) {
            // There is only implements.
            $this->json->class->implements = explode(',', trim(substr($rels, ( $posImpl + 12 ))));
        } else {
            // There are both.
            $this->json->class->extends    = trim(substr($rels, ( $posExt + 9 ), ( $posImpl - $posExt - 9 )));
            $this->json->class->implements = explode(',', trim(substr($rels, ( $posImpl + 12 ))));
        }

        // Correct implements.
        foreach ($this->json->class->implements as $classId => $className) {
            $this->json->class->implements[$classId] = trim($className);
        }
    }

    /**
     * Parse class comments (fills up `json->class->comments` node).
     *
     * @return void
     */
    private function parseClassComments() : void
    {

        // Lvd.
        $posStart = strpos($this->contents, '[newLine]/**');
        $posEnd   = ( strpos($this->contents, '[newLine] */') + strlen('[newLine] */') );

        // Shortlane.
        if ($posStart === false) {
            return;
        }

        // Cut out.
        $comments = substr($this->contents, $posStart, ( $posEnd - $posStart ));
        $comments = explode('[newLine]', $comments);
        $comments = array_slice($comments, 2, ( count($comments) - 3 ));

        // Create parser.
        $parser = new CommentsParser($comments);

        // Save its results.
        $this->json->class->comments = $parser->parse();
    }

    /**
     * Finds and parses all properties (fills up `json->properties` array).
     *
     * @return void
     */
    private function findProperties() : void
    {

        // Test.
        preg_match_all(
            '/(\[newLine\]    )(protected |public |private )(static )?(\$)([a-zA-Z0-9]+)/',
            $this->contents,
            $props,
            PREG_OFFSET_CAPTURE
        );

        // Parse.
        for ($i = 0; $i < count($props[0]); ++$i) {

            // Create parser.
            $parser = new PropertyParser(
                $this->contents,
                $props[0][$i][1],
                trim($props[2][$i][0]),
                ( ( trim($props[3][$i][0]) === 'static' ) ? true : false ),
                trim($props[5][$i][0])
            );

            // Save its results.
            $this->json->properties[] = $parser->parse();
        }
    }

    /**
     * Finds and parses all methods (fills up `json->methods` array).
     *
     * @return void
     */
    private function findMethods() : void
    {

        // Test.
        preg_match_all(
            '/(\[newLine\]    )(protected |public |private )(static )?(function )([a-zA-Z0-9_]+)/',
            $this->contents,
            $methods,
            PREG_OFFSET_CAPTURE
        );

        // Parse.
        for ($i = 0; $i < count($methods[0]); ++$i) {

            // Create parser.
            $parser = new MethodParser(
                $this->contents,
                $methods[0][$i][1],
                trim($methods[2][$i][0]),
                ( ( trim($methods[3][$i][0]) === 'static' ) ? true : false ),
                trim($methods[5][$i][0])
            );

            // Save its results.
            $this->json->methods[] = $parser->parse();
        }
    }
}
