<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki;

use Parsedown;
use Przeslijmi\Siwiki\Configure\Handlers;
use Przeslijmi\Siwiki\Configure\SiwikiConfigure as Configure;
use Przeslijmi\Siwiki\Exceptions\CleaningDstDirFopException;
use Przeslijmi\Siwiki\Exceptions\DocsJsonDonoexException;
use Przeslijmi\Siwiki\Exceptions\UnknownCommandException;
use Przeslijmi\Siwiki\Exceptions\UriDonoexException;
use Przeslijmi\Siwiki\Exceptions\UriEmptyException;
use Przeslijmi\Siwiki\MdGenerators\MdGenerator;
use Przeslijmi\Siwiki\MdGenerators\MdTextForAllPagesIndex;
use Throwable;

/**
 * Main crafting Siwiki class.
 */
class Siwiki extends Configure
{

    /**
     * List of all pages - to create index.
     *
     * @var string[]
     */
    private $allPages = [];

    /**
     * Crafts wiki.
     *
     * @throws CleaningDstDirFopException When cleaning destination dir failed.
     * @return self
     */
    public function craft() : self
    {

        // Create temporary exception handler.
        // @codeCoverageIgnoreStart
        // This functionality is only for dev.
        if ($_ENV['PRZESLIJMI_SIWIKI_HANDLE_EXCEPTIONS'] === true) {
            set_exception_handler([ Handlers::class, 'exceptionHandler' ]);
        }

        // @codeCoverageIgnoreEnd
        // Test if all data is given and is proper.
        $this->testEnvironment();

        // Clean destination directory.
        // @codeCoverageIgnoreStart
        // This can't be tested easily (locking problems).
        try {
            set_error_handler([ Handlers::class, 'errorHandler' ]);
            $this->cleanDstUri();
            restore_error_handler();
        } catch (Throwable $thr) {
            throw new CleaningDstDirFopException($this->getDstUri(), $thr);
        }

        // @codeCoverageIgnoreEnd
        // Copy attachments.
        $this->copyAttachments();

        // Generate htmls.
        $this->generateOwnDocsHtmls();
        $this->generatePhpDocsHtmls();

        // Clean up handler if was set up.
        // @codeCoverageIgnoreStart
        // This functionality is only for dev.
        if ($_ENV['PRZESLIJMI_SIWIKI_HANDLE_EXCEPTIONS'] === true) {
            restore_exception_handler();
        }

        // @codeCoverageIgnoreEnd
        return $this;
    }

    /**
     * Test if all data is given and is proper.
     *
     * @throws UriEmptyException When source or destination uri is empty.
     * @throws UriDonoexException When source or destination uri does not exists.
     * @return void
     */
    private function testEnvironment() : void
    {

        // Test uris.
        foreach ([ 'source' => $this->getSrcUri(), 'destination' => $this->getDstUri() ] as $type => $uri) {

            // Check if is empty.
            if (empty($uri) === true) {
                throw new UriEmptyException($type);
            }

            // Check if is not existing.
            if (file_exists($uri) === false) {
                throw new UriDonoexException($type);
            }
        }
    }

    /**
     * Clears destination directory.
     *
     * @param string|null $uri Uri to be cleared (when null - then destination dir is cleared as default).
     *
     * @return void
     */
    private function cleanDstUri(string $uri = null) : void
    {

        // Lvd.
        $deleteDir = ( ( $uri === null ) ? false : true );
        $uri       = ( $uri ?? $this->getDstUri() );

        // Scan directory.
        foreach (glob($uri . '*') as $element) {

            // If this is directory - run deeper.
            if (is_dir($element) === true) {
                $this->cleanDstUri($element . '/');
            } else {
                unlink($element);
            }
        }

        // Delete dir if needed.
        if ($deleteDir === true) {
            rmdir($uri);
        }
    }

    /**
     * Copy attachments from source to destination uri.
     *
     * @param string|null $uri Source uri to copy (if null source uri of crafting is used).
     *
     * @return void
     */
    private function copyAttachments(string $uri = null) : void
    {

        // Lvd.
        $uri = ( $uri ?? ( $this->getSrcUri() . 'attachments/' ) );

        // Scan directory.
        foreach (glob($uri . '*') as $element) {

            // Number 12 comes from strlen of `attachments/`.
            $destinationUri = $this->getDstUri() . substr($element, ( strlen($this->getSrcUri()) + 12 ));

            // If this is directory - create that directory and go deeper.
            if (is_dir($element) === true) {
                mkdir($destinationUri);
                $this->copyAttachments($element . '/');
            } else {
                copy($element, $destinationUri);
            }
        }
    }

    /**
     * Generate HTML contents.
     *
     * @return void
     */
    private function generateOwnDocsHtmls() : void
    {

        // Lvd.
        $sources = glob($this->getSrcUri() . '*.md');

        // Work on every file.
        foreach ($sources as $source) {

            // Find destination.
            $name             = substr(basename($source), 0, -3);
            $destination      = $this->getDstUri() . $name . '.html';
            $this->allPages[] = $name;

            // Get md and convert to html.
            $md   = file_get_contents($source);
            $html = $this->convertMdToHtml($source, $md);

            // Save html file.
            file_put_contents($destination, $html);
        }//end foreach
    }

    /**
     * Include app documentation on generation (from `_docs.json`).
     *
     * @throws DocsJsonDonoexException When `_docs.json` is missing.
     * @return void
     */
    private function generatePhpDocsHtmls() : void
    {

        // Shortcut.
        if ($this->getInclDoc() === false) {
            return;
        }

        // Throw when `_docs.json` is missing.
        if (file_exists($this->getSrcUri() . '_docs.json') === false) {
            throw new DocsJsonDonoexException();
        }

        // Include json.
        $json = json_decode(file_get_contents($this->getSrcUri() . '_docs.json'));
        $mds  = ( new MdGenerator($json) )->generate();

        // Fill up all pages property.
        foreach ($mds as $name => $md) {
            $this->allPages[] = $name;
        }

        // Create index of all pages.
        $mdIndex                    = new MdTextForAllPagesIndex($this->allPages);
        $mds[$mdIndex->getMdName()] = $mdIndex->getMdContents();

        // Convert md's to html's.
        foreach ($mds as $name => $md) {

            // Find destination.
            $destination = $this->getDstUri() . $name . '.html';

            // Convert to html.
            $html = $this->convertMdToHtml($name, $md);

            // Save html file.
            file_put_contents($destination, trim($html));
        }
    }

    /**
     * Helper method converting `MD` into `HTML` format.
     *
     * @param string $sourceName Name of source (used to point error location) - file URI or PHPDoc element.
     * @param string $md         MD contents to convert.
     *
     * @return string
     */
    private function convertMdToHtml(string $sourceName, string $md) : string
    {

        // Convert md.
        $md = str_replace('.!)', '.html)', $md);
        $md = $this->workOnMdCommands($sourceName, $md);

        // Get html.
        $parsedown = new Parsedown();
        $html      = $parsedown->text($md);
        $html      = $this->workOnHtmlCommands($sourceName, $html);

        // Unescape escaped.
        $html = str_replace('@\\\\{md.', '@{md.', $html);
        $html = str_replace('@\\\\{html.', '@{html.', $html);

        // Clear memory.
        unset($parsedown);

        return trim($html);
    }

    /**
     * Call MD commands - return MD after commands.
     *
     * @param string $sourceName Name of source (used to point error location) - file URI or PHPDoc element.
     * @param string $md         MD contents before calling commands.
     *
     * @throws UnknownCommandException When unknown command is sent.
     * @return string
     */
    private function workOnMdCommands(string $sourceName, string $md) : string
    {

        // Find commands inside text - by using this only one (first) command is found - but this method
        // is recalled until no command is present.
        preg_match('/(<p>)?(@{)(md)(\.)([a-zA-Z0-9]+)(::)?(.+)?(})(<\/p>)?/', $md, $command);

        // If command has been found.
        if (count($command) >= 9) {

            // Command unknown.
            if (isset($this->commands['md.' . $command[5]]) === false) {
                throw new UnknownCommandException($sourceName, 'md.' . $command[5]);
            }

            // Get command class and parse it.
            $className = $this->commands['md.' . $command[5]];
            $class     = new $className($this, $md, $command[0], $command[7]);
            $md        = $class->parse();

            // Call next iteration (if there is any other command present).
            $md = $this->workOnMdCommands($sourceName, $md);
        }

        return $md;
    }

    /**
     * Call HTML commands - return HTML after commands.
     *
     * @param string $sourceName Name of source (used to point error location) - file URI or PHPDoc element.
     * @param string $html       HTML contents before calling commands.
     *
     * @throws UnknownCommandException When unknown command is sent.
     * @return string
     */
    private function workOnHtmlCommands(string $sourceName, string $html) : string
    {

        // Find commands inside text - by using this only one (first) command is found - but this method
        // is recalled until no command is present.
        preg_match('/(<p>)?(@{)(html)(\.)([a-zA-Z0-9]+)(::)?(.+)?(})(<\/p>)?/', $html, $command);

        // If command has been found.
        if (count($command) >= 9) {

            // Command unknown.
            if (isset($this->commands['html.' . $command[5]]) === false) {
                throw new UnknownCommandException($sourceName, 'html.' . $command[5]);
            }

            // Get command class and parse it.
            $className = $this->commands['html.' . $command[5]];
            $class     = new $className($this, $html, $command[0], $command[7]);
            $html      = $class->parse();

            // Call next iteration (if there is any other command present).
            $html = $this->workOnHtmlCommands($sourceName, $html);
        }

        return $html;
    }
}
