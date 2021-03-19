<?php declare(strict_types=1);

namespace Przeslijmi\SiwikiFake;

use Vendor\App\Class;

final class FakeClassFour extends Extension implements Implementation
{

    private $nothing = false;

    public function methodOne() : void
    {

        $this->nothing = true;
    }
}
