<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\MdGenerators;

use stdClass;

/**
 * Generates md text for index of all public methods.
 */
class MdTextForAllPublicMethodsIndex
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
        $this->mdName = 'index-all-public-methods';
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
        $this->mdContents .= '# Index of all public methods';
        $this->mdContents .= PHP_EOL . PHP_EOL;

        $this->mdContents .= '| Namespace | Class | Method | Description |' . PHP_EOL;
        $this->mdContents .= '| --------- | ----- | ------ | ----------- |' . PHP_EOL;

        // Add classes.
        foreach ($this->getAllPublicMethods() as $record) {

            // Lvd.
            $classLink     = 'class-' . str_replace('\\', '_', $record['classFullName']);
            $namespaceLink = 'namespace-' . str_replace('\\', '_', $record['namespace']);

            // Add this class.
            $this->mdContents .= '| [`' . $record['namespace'] . '`](' . $namespaceLink . '.html) ';
            $this->mdContents .= '| [`' . $record['className'] . '`](' . $classLink . '.html) ';
            $this->mdContents .= '| **`' . $record['method']->name . '`** ';
            $this->mdContents .= '| ' . ( $record['method']->comments->title ?? '' ) . ' |' . PHP_EOL;
        }

        // Add footer.
        $this->mdContents .= PHP_EOL . PHP_EOL . '@{html.include::file:elements/footer.docs.html}';

        return $this->mdContents;
    }

    /**
     * Delivers all public methods.
     *
     * @return stdClass[]
     */
    private function getAllPublicMethods() : array
    {

        // Lvd.
        $result = [];

        // Add all classes.
        foreach ((array) $this->docs as $appName => $appDocs) {
            foreach ((array) $appDocs->classes as $className => $class) {
                foreach ((array) $class->methods as $method) {

                    // Ignore non-public.
                    if ($method->scope !== 'public') {
                        continue;
                    }

                    // Lvd.
                    $sortKey = $class->namespace . '\\' . $className . '::' . $method->name;

                    // Save.
                    $result[$sortKey] = [
                        'classFullName' => $className,
                        'className' => $class->class->name,
                        'namespace' => $class->namespace,
                        'method' => $method,
                    ];
                }
            }
        }//end foreach

        // Sort.
        ksort($result);

        return $result;
    }
}
