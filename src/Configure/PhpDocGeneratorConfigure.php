<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Configure;

use Parsedown;
use Przeslijmi\Siwiki\Configure\PhpDocGenerator\App;

/**
 * Collection of getters and setters as abstract class neccesary for PhpDocGenerator
 */
abstract class PhpDocGeneratorConfigure
{

    /**
     * Destination uri - where to put generated `JSON` file.
     *
     * @var string
     */
    private $dstUri = '';

    /**
     * Apps collection.
     *
     * @var App[]
     */
    private $apps = [];

    /**
     * Setter for destination uri.
     *
     * @param string $dstUri Destination uri.
     *
     * @return self
     */
    public function setDstUri(string $dstUri) : self
    {

        // Add ending slash.
        if (empty($dstUri) === false) {
            $dstUri = rtrim($dstUri, '/') . '/';
        }

        // Set.
        $this->dstUri = $dstUri;

        return $this;
    }

    /**
     * Getter for destination uri.
     *
     * @return string
     */
    public function getDstUri() : string
    {

        return $this->dstUri;
    }

    /**
     * Adder of App.
     *
     * @param string $name   Name of app.
     * @param string $srcUri Uri in which PHP files are located (`src/` dir as usual).
     *
     * @return App
     */
    public function addApp(string $name, string $srcUri) : App
    {

        // Create new app.
        $app = new App($name, $srcUri);

        // Save to collection.
        $this->apps[] = $app;

        return $app;
    }

    /**
     * Getter for Apps.
     *
     * @return App[]
     */
    public function getApps() : array
    {

        return $this->apps;
    }
}
