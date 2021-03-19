<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\MdGenerators;

use stdClass;

/**
 * Generates md text for namespace.
 */
class MdTextForNamespace
{

    /**
     * Full docs of app.
     *
     * @var stdClass
     */
    private $appDocs;

    /**
     * Namespace for which MD has to be generated.
     *
     * @var string
     */
    private $namespace;

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
     * @param stdClass $appDocs   Full docs of app.
     * @param string   $namespace Namespace for which MD has to be generated.
     */
    public function __construct(stdClass $appDocs, string $namespace)
    {

        // Save.
        $this->namespace = $namespace;
        $this->appDocs   = $appDocs;

        // Generate md name.
        $this->mdName = 'namespace-' . str_replace('\\', '_', $this->namespace);
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
        $namespace     = substr($this->namespace, 0, strrpos($this->namespace, '\\'));
        $namespaceLink = 'namespace-' . str_replace('\\', '_', $namespace) . '.html';

        // Add header.
        $this->mdContents .= '@{md.include::file:elements/header.docs.md}' . PHP_EOL;

        // Add title and first line.
        $this->mdContents .= '# Namespace ' . $this->namespace;
        $this->mdContents .= PHP_EOL . PHP_EOL;
        $this->mdContents .= 'This is app\'s namespace.';
        $this->mdContents .= PHP_EOL . PHP_EOL;

        // Add information about  parent namespace (if exists).
        if (strpos($namespace, '\\') !== false) {
            $this->mdContents .= 'Belongs to namespace: **[`' . $namespace . '`](' . $namespaceLink . ')**.';
            $this->mdContents .= PHP_EOL . PHP_EOL;
        }

        $this->mdContents .= '## Classes in this namespace' . PHP_EOL;
        $this->mdContents .= '| Class | Description |' . PHP_EOL;
        $this->mdContents .= '| ---- | ----------- |' . PHP_EOL;

        // Add classes.
        foreach ($this->getClassesOfThisNamespace() as $className => $class) {

            // Lvd.
            $link = 'class-' . str_replace('\\', '_', $className);

            // Add this class.
            $this->mdContents .= '| **[`' . $class->class->name . '`](' . $link . '.html)** ';
            $this->mdContents .= '| ' . ( $class->class->comments->title ?? '' ) . ' |' . PHP_EOL;
        }

        $this->mdContents .= '## Namespaces in this namespace' . PHP_EOL;
        $this->mdContents .= '| Namespace |' . PHP_EOL;
        $this->mdContents .= '| --------- |' . PHP_EOL;

        // Add namespaces.
        foreach ($this->getNamespacesOfThisNamespace() as $namespace) {

            // Lvd.
            $link = 'namespace-' . str_replace('\\', '_', $namespace);

            // Add this class.
            $this->mdContents .= '| **[`' . $namespace . '`](' . $link . '.html)** |' . PHP_EOL;
        }

        // Add footer.
        $this->mdContents .= PHP_EOL . PHP_EOL . '@{html.include::file:elements/footer.docs.html}';

        return $this->mdContents;
    }

    /**
     * Delivers all classes of this namespace.
     *
     * @return stdClass[]
     */
    private function getClassesOfThisNamespace() : array
    {

        // Lvd.
        $result = [];

        // Add classes.
        foreach ((array) $this->appDocs->classes as $className => $class) {

            // Ignore.
            if ($class->namespace !== $this->namespace) {
                continue;
            }

            // Add to result.
            $result[$className] = $class;
        }

        // Sort.
        ksort($result);

        return $result;
    }

    /**
     * Delivers all namespaces of this namespace.
     *
     * @return stdClass[]
     */
    private function getNamespacesOfThisNamespace() : array
    {

        // Lvd.
        $result = [];

        // Add classes.
        foreach ((array) $this->appDocs->classes as $className => $class) {

            // Ignore.
            if (substr($class->namespace, 0, strlen($this->namespace)) !== $this->namespace
                || $class->namespace === $this->namespace
            ) {
                continue;
            }

            // Add to result.
            $result[] = $class->namespace;
        }

        // Delete duplicates.
        $result = array_unique($result);
        sort($result);

        return $result;
    }
}
