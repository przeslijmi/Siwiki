<?php declare(strict_types=1);

namespace Przeslijmi\SiwikiFake;

use Vendor\App\Class;

/**
 * Fake class just to test parsers.
 *
 * Extra contents to show up in Wiki.
 */
final class FakeClassThree implements Implementation
{

    /**
     * Property of nothing.
     */
    private $nothing = false;

    /**
     * Do nothing.
     *
     * @return void
     */
    private function methodOne() : void
    {

        $this->nothing = true;
    }
}
