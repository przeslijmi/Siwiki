<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\MdGenerators;

use stdClass;

/**
 * Generates md test for class.
 */
class MdTextForClass
{

    /**
     * Full name of class (with namespace) for which MD has to be generated.
     *
     * @var string
     */
    private $className;

    /**
     * Docs of class.
     *
     * @var stdClass
     */
    private $class;

    /**
     * Name of generated MD.
     *
     * @var string
     */
    private $mdName = '';

    /**
     * Contents of generated MD.
     *
     * @var string
     */
    private $mdContents = '';

    /**
     * Constructor.
     *
     * @param string   $className Full name of class (with namespace) for which MD has to be generated.
     * @param stdClass $class     Docs of class.
     */
    public function __construct(string $className, stdClass $class)
    {

        // Save.
        $this->className = $className;
        $this->class     = $class;

        // Generate md name.
        $this->mdName = 'class-' . str_replace('\\', '_', $this->className);
    }

    /**
     * Getter for md name.
     *
     * @return string
     */
    public function getMdName() : string
    {

        return $this->mdName;
    }

    /**
     * Getter for md contents.
     *
     * @return string
     */
    public function getMdContents() : string
    {

        // Lvd.
        $namespaceLink = 'namespace-' . str_replace('\\', '_', $this->class->namespace) . '.html';

        // Add header.
        $this->mdContents .= '@{md.include::file:elements/header.docs.md}' . PHP_EOL;

        // Add title and first line.
        $this->mdContents .= '# Class ' . $this->className;
        $this->mdContents .= PHP_EOL . PHP_EOL;
        $this->mdContents .= ( $this->class->class->comments->title ?? '' );
        $this->mdContents .= PHP_EOL . PHP_EOL;
        $this->mdContents .= 'Belongs to namespace: **[`' . $this->class->namespace . '`](' . $namespaceLink . ')**.';

        // Add other description.
        if (empty($this->class->class->comments->contents) === false) {
            $this->mdContents .= PHP_EOL . PHP_EOL;
            $this->mdContents .= '## Notes' . PHP_EOL;
            $this->mdContents .= ( $this->class->class->comments->contents ?? '' );
        }

        // Add properties heading.
        if (count($this->class->properties) > 0) {
            $this->mdContents .= PHP_EOL . PHP_EOL;
            $this->mdContents .= '## Properties' . PHP_EOL;
            $this->mdContents .= '| Scope | Name | Description |' . PHP_EOL;
            $this->mdContents .= '| ----- | ---- | ----------- |' . PHP_EOL;

            // Add properties.
            foreach ($this->class->properties as $property) {
                $this->mdContents .= '| ' . $property->scope . ' ';
                $this->mdContents .= '| `->' . $property->name . '` ';
                $this->mdContents .= '| ' . ( $property->comments->title ?? '' ) . ' |';
                $this->mdContents .= PHP_EOL;
            }
        }

        // Add methods heading.
        if (count($this->class->methods) > 0) {
            $this->mdContents .= PHP_EOL . PHP_EOL;
            $this->mdContents .= '## Methods' . PHP_EOL;
            $this->mdContents .= '| Scope | Name | Description |' . PHP_EOL;
            $this->mdContents .= '| ----- | ---- | ----------- |' . PHP_EOL;

            // Add methods.
            foreach ($this->class->methods as $method) {
                $this->mdContents .= '| ' . $method->scope . ' ';
                $this->mdContents .= '| `->' . $method->name . '()` ';
                $this->mdContents .= '| ' . ( $method->comments->title ?? '' ) . ' |';
                $this->mdContents .= PHP_EOL;
            }
        }

        // Add footer.
        $this->mdContents .= PHP_EOL . PHP_EOL . '@{html.include::file:elements/footer.docs.html}';

        return $this->mdContents;
    }
}
