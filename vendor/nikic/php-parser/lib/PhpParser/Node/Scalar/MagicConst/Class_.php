<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class Class_ extends MagicConst
{
    public function getName()
    {
        return '__CLASS__';
    }
}
