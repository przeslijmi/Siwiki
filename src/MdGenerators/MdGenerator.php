<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\MdGenerators;

use Przeslijmi\Siwiki\MdGenerators\MdTextForAllClassesIndex;
use Przeslijmi\Siwiki\MdGenerators\MdTextForAllPublicMethodsIndex;
use Przeslijmi\Siwiki\MdGenerators\MdTextForClass;
use Przeslijmi\Siwiki\MdGenerators\MdTextForNamespace;
use stdClass;

/**
 * Gathers all MD to be generated.
 */
class MdGenerator
{

    /**
     * Full docs of app.
     *
     * @var stdClass
     */
    private $docs;

    /**
     * List of MD's that have to be parsed.
     *
     * @var string[]
     */
    private $mds = [];

    /**
     * Constructor.
     *
     * @param stdClass $docs Full docs of app.
     */
    public function __construct(stdClass $docs)
    {

        // Save.
        $this->docs = $docs;
    }

    /**
     * Calls subsuquent generations and returns list of MD's that have to be parsed.
     *
     * @return array
     */
    public function generate() : array
    {

        // For every app in docs.
        foreach ((array) $this->docs as $appName => $appDocs) {
            $this->forEveryClass($appDocs);
            $this->forEveryNamespace($appDocs);
        }

        // For all apps.
        $this->forIndexes();

        return $this->mds;
    }

    /**
     * Generate MD for every class.
     *
     * @param stdClass $appDocs Part of docs for one app.
     *
     * @return void
     */
    private function forEveryClass(stdClass $appDocs) : void
    {

        // Create md page for every class.
        foreach ((array) $appDocs->classes as $className => $class) {

            // Lvd.
            $mdGenerator = new MdTextForClass($className, $class);

            // Get html.
            $this->mds[$mdGenerator->getMdName()] = $mdGenerator->getMdContents();

            // Free memory.
            unset($mdGenerator);
        }
    }

    /**
     * Generate MD for every namespace.
     *
     * @param stdClass $appDocs Part of docs for one app.
     *
     * @return void
     */
    private function forEveryNamespace(stdClass $appDocs) : void
    {

        // Lvd.
        $namespaces = [];

        // Get all namespaces.
        foreach ((array) $appDocs->classes as $className => $class) {
            $namespaces[] = $class->namespace;
        }

        // Ignore duplicates.
        array_unique($namespaces);

        // Create md page for every namespace.
        foreach ($namespaces as $namespace) {

            // Lvd.
            $mdGenerator = new MdTextForNamespace($appDocs, $namespace);

            // Get html.
            $this->mds[$mdGenerator->getMdName()] = $mdGenerator->getMdContents();

            // Free memory.
            unset($mdGenerator);
        }
    }

    /**
     * Generate MD with index of all classes.
     *
     * @return void
     */
    private function forIndexes() : void
    {

        // All classes index.
        $mdGen                          = new MdTextForAllClassesIndex($this->docs);
        $this->mds[$mdGen->getMdName()] = $mdGen->getMdContents();

        // All classes index.
        $mdGen                          = new MdTextForAllPublicMethodsIndex($this->docs);
        $this->mds[$mdGen->getMdName()] = $mdGen->getMdContents();

        // Free memory.
        unset($mdGen);
    }
}
