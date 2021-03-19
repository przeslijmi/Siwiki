<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\MdGenerators;

use stdClass;

/**
 * Generates md text for index of all pages.
 */
class MdTextForAllPagesIndex
{

    /**
     * List of all pages in wiki.
     *
     * @var string[]
     */
    private $allPages = [];

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
     * @param string[] $allPages List of all pages.
     */
    public function __construct(array $allPages)
    {

        // Save.
        $this->allPages = $allPages;
        sort($this->allPages);

        // Generate md name.
        $this->mdName = 'index-all-pages';
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
        $this->mdContents .= '# Index of all pages';
        $this->mdContents .= PHP_EOL . PHP_EOL;

        // Add pages.
        foreach ($this->allPages as $page) {
            $this->mdContents .= '  - [`' . $page . '`](' . $page . '.!) ' . PHP_EOL;
        }

        // Add footer.
        $this->mdContents .= PHP_EOL . PHP_EOL . '@{html.include::file:elements/footer.docs.html}';

        return $this->mdContents;
    }
}
