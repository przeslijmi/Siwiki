<?php declare(strict_types=1);

namespace Przeslijmi\SiwikiFake\Deeper;

final class DeepClass
{

    private $nothing = false;

    public function methodOne() : void
    {

        $this->nothing = true;
    }
}
