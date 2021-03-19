<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Configure\PhpDocGenerator;

/**
 * Definition of PHP application that has to be included in documentation generation.
 */
class App
{

    /**
     * Name of app.
     *
     * @var string
     */
    private $name = '';

    /**
     * Uri in which PHP files are located (`src/` dir as usual).
     *
     * @var string
     */
    private $srcUri = '';

    /**
     * Uri of `composer.json` file.
     *
     * @var string
     */
    private $composerJsonUri = '';

    /**
     * Constructor.
     *
     * @param string $name   Name of app.
     * @param string $srcUri Uri in which PHP files are located (`src/` dir as usual).
     */
    public function __construct(string $name, string $srcUri)
    {

        // Save.
        $this->setName($name);
        $this->setSrcUri($srcUri);
    }

    /**
     * Setter for name of app.
     *
     * @param string $name Name of app.
     *
     * @return self
     */
    public function setName(string $name) : self
    {

        // Set.
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for name of app.
     *
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Setter for source uri.
     *
     * @param string $srcUri Source uri.
     *
     * @return self
     */
    public function setSrcUri(string $srcUri) : self
    {

        // Add ending slash.
        if (empty($srcUri) === false) {
            $srcUri = rtrim($srcUri, '/') . '/';
        }

        // Set.
        $this->srcUri = $srcUri;

        return $this;
    }

    /**
     * Getter for source uri.
     *
     * @return string
     */
    public function getSrcUri() : string
    {

        return $this->srcUri;
    }

    /**
     * Setter for `composer.json` uri..
     *
     * @param string $composerJsonUri Uri of `composer.json` file.
     *
     * @return self
     */
    public function setComposerJson(string $composerJsonUri) : self
    {

        // Set.
        $this->composerJsonUri = $composerJsonUri;

        return $this;
    }

    /**
     * Getter for `composer.json` uri..
     *
     * @return string
     */
    public function getComposerJson() : string
    {

        return $this->composerJsonUri;
    }
}
