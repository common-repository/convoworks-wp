<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class Trait_ extends MagicConst
{
    public function getName()
    {
        return '__TRAIT__';
    }
}
