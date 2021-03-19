<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Siwiki\Exceptions\CleaningDstDirFopException;
use Przeslijmi\Siwiki\Exceptions\DocsJsonDonoexException;
use Przeslijmi\Siwiki\Exceptions\UnknownCommandException;
use Przeslijmi\Siwiki\Exceptions\UriDonoexException;
use Przeslijmi\Siwiki\Exceptions\UriEmptyException;
use Przeslijmi\Siwiki\Siwiki;

/**
 * Methods for testing Siwiki.
 */
final class SiwikiTest extends TestCase
{

    /**
     * Test standard behaviour for both processes (PhpDocGenerator and Siwiki).
     *
     * @return void
     */
    public function testIfBothProcessesWorksProperly() : void
    {

        // Lvd.
        $dstUri = 'resources/forTesting/siwiki/fakeSrc/';
        $srcUri = 'resources/forTesting/phpDocGenerator/fakeSrc/';

        // Perform generation.
        $json = new PhpDocGenerator();
        $json->setDstUri($dstUri);
        $json->addApp('przeslijmi/siwikiTest', $srcUri)->setComposerJson('missingComposer.json');
        $json->create();

        // Lvd.
        $srcUri = 'resources/forTesting/siwiki/fakeSrc/';
        $dstUri = 'resources/forTesting/siwiki/generated/';

        // Perform generation.
        $siwiki = new Siwiki();
        $siwiki->setSrcUri($srcUri);
        $siwiki->setDstUri($dstUri);
        $siwiki->setInclDoc(true);
        $siwiki->craft();

        // Test.
        $this->assertTrue(true);
        // @todo Tommorow.
    }

    /**
     * Test standard behaviour for only Siwiki process.
     *
     * @return void
     */
    public function testIfOneProcessWorksProperly() : void
    {

        // Lvd.
        $srcUri = 'resources/forTesting/siwiki/fakeSrc/';
        $dstUri = 'resources/forTesting/siwiki/generated/';

        // Perform generation.
        $siwiki = new Siwiki();
        $siwiki->setSrcUri($srcUri);
        $siwiki->setDstUri($dstUri);
        $siwiki->craft();

        // Test.
        // @todo Tommorow.
        $this->assertTrue(true);
    }

    /**
     * Test if missing source throws.
     *
     * @return void
     */
    public function testIfMissingSourceThrows() : void
    {

        // Prepare.
        $this->expectException(UriEmptyException::class);

        // Lvd.
        $srcUri = '';
        $dstUri = '';

        // Perform generation.
        $siwiki = new Siwiki();
        $siwiki->setSrcUri($srcUri);
        $siwiki->setDstUri($dstUri);
        $siwiki->craft();
    }

    /**
     * Test if non-existing source throws.
     *
     * @return void
     */
    public function testIfNonexistingSourceThrows() : void
    {

        // Prepare.
        $this->expectException(UriDonoexException::class);

        // Lvd.
        $srcUri = 'resources/forTesting/siwiki/nonexistingDir/';
        $dstUri = 'resources/forTesting/siwiki/nonexistingDir/';

        // Perform generation.
        $siwiki = new Siwiki();
        $siwiki->setSrcUri($srcUri);
        $siwiki->setDstUri($dstUri);
        $siwiki->craft();
    }

    /**
     * Test if unknown MD command throws.
     *
     * @return void
     */
    public function testIfUnknownMdCommandThrows() : void
    {

        // Prepare.
        $this->expectException(UnknownCommandException::class);

        // Lvd.
        $srcUri = 'resources/forTesting/siwiki/unknownMdCommandSrc/';
        $dstUri = 'resources/forTesting/siwiki/generated/';

        // Perform generation.
        $siwiki = new Siwiki();
        $siwiki->setSrcUri($srcUri);
        $siwiki->setDstUri($dstUri);
        $siwiki->craft();
    }

    /**
     * Test if unknown HTML command throws.
     *
     * @return void
     */
    public function testIfUnknownHtmlCommandThrows() : void
    {

        // Prepare.
        $this->expectException(UnknownCommandException::class);

        // Lvd.
        $srcUri = 'resources/forTesting/siwiki/unknownHtmlCommandSrc/';
        $dstUri = 'resources/forTesting/siwiki/generated/';

        // Perform generation.
        $siwiki = new Siwiki();
        $siwiki->setSrcUri($srcUri);
        $siwiki->setDstUri($dstUri);
        $siwiki->craft();
    }

    /**
     * Test if nonexisting docs JSON throws.
     *
     * @return void
     */
    public function testIfNonexistingDocsJsonThrows() : void
    {

        // Prepare.
        $this->expectException(DocsJsonDonoexException::class);

        // Lvd.
        $srcUri = 'resources/forTesting/siwiki/fakeSrc/';
        $dstUri = 'resources/forTesting/siwiki/generated/';

        // Delete `_docs.json` to make exception.
        if (file_exists($srcUri . '_docs.json') === true) {
            unlink($srcUri . '_docs.json');
        }

        // Perform generation.
        $siwiki = new Siwiki();
        $siwiki->setSrcUri($srcUri);
        $siwiki->setDstUri($dstUri);
        $siwiki->setInclDoc(true);
        $siwiki->craft();
    }
}
