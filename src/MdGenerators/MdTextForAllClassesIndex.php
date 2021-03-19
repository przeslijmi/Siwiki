<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\MdGenerators;

use stdClass;

/**
 * Generates md text for index of all classes.
 */
class MdTextForAllClassesIndex
{

    /**
     * Full docs of app.
     *
     * @var stdClass
     */
    private $docs;

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
     * @param stdClass $docs Full docs of app.
     */
    public function __construct(stdClass $docs)
    {

        // Save.
        $this->docs = $docs;

        // Generate md name.
        $this->mdName = 'index-all-classes';
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

        // Add header.
        $this->mdContents .= '@{md.include::file:elements/header.docs.md}' . PHP_EOL;

        // Add title and first line.
        $this->mdContents .= '# Index of all classes';
        $this->mdContents .= PHP_EOL . PHP_EOL;

        $this->mdContents .= '| Namespace | Class | Description |' . PHP_EOL;
        $this->mdContents .= '| --------- | ----- | ----------- |' . PHP_EOL;

        // Add classes.
        foreach ($this->getAllClasses() as $className => $class) {

            // Lvd.
            $classLink     = 'class-' . str_replace('\\', '_', $className);
            $namespaceLink = 'namespace-' . str_replace('\\', '_', $class->namespace);

            // Add this class.
            $this->mdContents .= '| [`' . $class->namespace . '`](' . $namespaceLink . '.html) ';
            $this->mdContents .= '| **[`' . $class->class->name . '`](' . $classLink . '.html)** ';
            $this->mdContents .= '| ' . ( $class->class->comments->title ?? '' ) . ' |' . PHP_EOL;
        }

        // Add footer.
        $this->mdContents .= PHP_EOL . PHP_EOL . '@{html.include::file:elements/footer.docs.html}';

        return $this->mdContents;
    }

    /**
     * Delivers all classes.
     *
     * @return stdClass[]
     */
    private function getAllClasses() : array
    {

        // Lvd.
        $result = [];

        // Add all classes.
        foreach ((array) $this->docs as $appName => $appDocs) {
            foreach ((array) $appDocs->classes as $className => $class) {
                $result[$className] = $class;
            }
        }

        // Sort.
        ksort($result);

        return $result;
    }
}
