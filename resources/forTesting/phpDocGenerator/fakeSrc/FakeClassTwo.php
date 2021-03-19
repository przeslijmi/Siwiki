<?php declare(strict_types=1);

namespace Przeslijmi\SiwikiFake;

use Vendor\App\Class;

/**
 * Fake class just to test parsers.
 */
final class FakeClassTwo extends Extension
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
    public function methodOne() : void
    {

        $this->nothing = true;
    }
}
