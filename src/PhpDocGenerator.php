<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki;

use Przeslijmi\Siwiki\Configure\PhpDocGenerator\App;
use Przeslijmi\Siwiki\Configure\PhpDocGeneratorConfigure as Configure;
use Przeslijmi\Siwiki\PhpParsers\ClassParser;
use stdClass;

/**
 * Tool to create JSON from SRC PHP files.
 */
class PhpDocGenerator extends Configure
{

    /**
     * Json object with all data.
     *
     * @var stdClass
     */
    private $json;

    /**
     * Creates json.
     *
     * @return self
     */
    public function create() : self
    {

        // Create empty json.
        $this->json = new stdClass();

        // Work on each app.
        foreach ($this->getApps() as $app) {

            // Create empty json for app.
            $this->json->{$app->getName()}           = new stdClass();
            $this->json->{$app->getName()}->composer = new stdClass();
            $this->json->{$app->getName()}->classes  = new stdClass();

            // Add composer.
            if (empty($app->getComposerJson()) === false) {
                $this->addComposer($app);
            }

            // Scan all files.
            $this->scanPhpFiles($app, $app->getSrcUri());
        }

        // Save json.
        file_put_contents(
            $this->getDstUri() . '_docs.json',
            json_encode($this->json, JSON_PRETTY_PRINT)
        );

        return $this;
    }

    /**
     * Getter for result json.
     *
     * @return stdClass
     */
    public function getJson() : stdClass
    {

        return $this->json;
    }

    /**
     * Fills up `composer` nod with composer JSON - if the file `composer.json` exists.
     *
     * @param App $app App for which composer have to be added.
     *
     * @return void
     */
    private function addComposer(App $app) : void
    {

        // Shortcut.
        if (file_exists($app->getComposerJson()) === false) {
            return;
        }

        // Save.
        $this->json->{$app->getName()}->composer = json_decode(file_get_contents($app->getComposerJson()));
    }

    /**
     * Scan all `php` files into JSON.
     *
     * @param App    $app    App for which composer have to be added.
     * @param string $dirUri Uri to be scanned for PHP files.
     *
     * @return void
     */
    private function scanPhpFiles(App $app, string $dirUri = null) : void
    {

        // Scan files.
        foreach (glob($dirUri . '*') as $element) {

            // If this is a directory.
            if (is_dir($element) === true) {

                // Scan this directory.
                $this->scanPhpFiles($app, $element . '/');

                // Go to next element.
                continue;
            }

            if (is_file($element) === true && substr($element, -4) === '.php') {

                // Create parser.
                $parser = new ClassParser($element);

                // Get json.
                $parsedJson = $parser->parse();

                // Lvd.
                $fullName = $parsedJson->namespace . '\\' . $parsedJson->class->name;

                // Save json.
                $this->json->{$app->getName()}->classes->{$fullName} = $parsedJson;

                // Free memory.
                unset($parser);

                // Go to next element.
                continue;

            }//end if
        }//end foreach
    }
}
