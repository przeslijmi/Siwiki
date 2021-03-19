<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Siwiki\PhpDocGenerator;

/**
 * Methods for testing PhpDocGenerator.
 */
final class PhpDocGeneratorTest extends TestCase
{

    /**
     * Test standard behaviour.
     *
     * @return void
     */
    public function testIfWorksProperly() : void
    {

        // Lvd.
        $dstUri  = 'resources/forTesting/phpDocGenerator/generated/';
        $genUri  = 'resources/forTesting/phpDocGenerator/generated/_docs.json';
        $specUri = 'resources/forTesting/phpDocGenerator/specimen/normal_docs.json';

        // Perform generation.
        $json = new PhpDocGenerator();
        $json->setDstUri($dstUri);
        $json->addApp('przeslijmi/siwiki', 'src/')->setComposerJson('composer.json');
        $json->create();

        // Test.
        $this->assertTrue(file_exists($genUri));
        $this->assertEquals(
            trim(file_get_contents($genUri)),
            trim(file_get_contents($specUri))
        );
        $this->assertEquals(
            trim(file_get_contents($specUri)),
            trim(json_encode($json->getJson(), JSON_PRETTY_PRINT))
        );
    }

    /**
     * When missing file is send to `setComposerJson()` - result should be different.
     *
     * @return void
     */
    public function testIfMissingComposerJsonWorksProperly() : void
    {

        // Lvd.
        $dstUri  = 'resources/forTesting/phpDocGenerator/generated/';
        $genUri  = 'resources/forTesting/phpDocGenerator/generated/_docs.json';
        $specUri = 'resources/forTesting/phpDocGenerator/specimen/missingComposerJson_docs.json';

        // Perform generation.
        $json = new PhpDocGenerator();
        $json->setDstUri($dstUri);
        $json->addApp('przeslijmi/siwiki', 'src/')->setComposerJson('missingComposer.json');
        $json->create();

        // Test.
        $this->assertTrue(file_exists($genUri));
        $this->assertEquals(
            trim(file_get_contents($genUri)),
            trim(file_get_contents($specUri))
        );
        $this->assertEquals(
            trim(file_get_contents($specUri)),
            trim(json_encode($json->getJson(), JSON_PRETTY_PRINT))
        );
    }

    /**
     * Tests all functions of all parsers on fake code to be parsed.
     *
     * @return void
     */
    public function testIfFakeSrcWorksProperly() : void
    {

        // Lvd.
        $dstUri  = 'resources/forTesting/phpDocGenerator/generated/';
        $genUri  = 'resources/forTesting/phpDocGenerator/generated/_docs.json';
        $specUri = 'resources/forTesting/phpDocGenerator/specimen/fakeSrc_docs.json';

        // Perform generation.
        $json = new PhpDocGenerator();
        $json->setDstUri($dstUri);
        $json->addApp('przeslijmi/siwikiFake', 'resources/forTesting/phpDocGenerator/fakeSrc');
        $json->create();

        // Test.
        $this->assertTrue(file_exists($genUri));
        $this->assertEquals(
            trim(file_get_contents($genUri)),
            trim(file_get_contents($specUri))
        );
        $this->assertEquals(
            trim(file_get_contents($specUri)),
            trim(json_encode($json->getJson(), JSON_PRETTY_PRINT))
        );
    }
}
