<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Configure;

use Parsedown;

/**
 * Collection of getters and setters as abstract class neccesary for Siwiki
 */
abstract class SiwikiConfigure
{

    /**
     * Commands possible to use by Siwiki.
     *
     * For an example see `specimen.env.php`, at `PRZESLIJMI_SIWIKI_COMMANDS`.
     *
     * @var string[]
     */
    protected $commands = [];

    /**
     * Source uri.
     *
     * @var string
     */
    private $srcUri = '';

    /**
     * Destination uri.
     *
     * @var string
     */
    private $dstUri = '';

    /**
     * Am I to include documentation during generation or not.
     *
     * @var boolean
     */
    private $inclDoc = false;

    /**
     * Constructor - reads `commands` from `env`.
     */
    public function __construct()
    {

        // Save commands.
        $this->commands = $_ENV['PRZESLIJMI_SIWIKI_COMMANDS'];
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
     * Setter for include documentation.
     *
     * @param boolean $inclDoc Opt., `true`. Am I to include documentation during generation or not.
     *
     * @return self
     */
    public function setInclDoc(bool $inclDoc = true) : self
    {

        $this->inclDoc = $inclDoc;

        return $this;
    }

    /**
     * Getter for include documentation.
     *
     * @return boolean
     */
    public function getInclDoc() : bool
    {

        return $this->inclDoc;
    }
}
