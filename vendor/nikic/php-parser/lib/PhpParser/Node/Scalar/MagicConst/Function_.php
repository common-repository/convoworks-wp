<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class Function_ extends MagicConst
{
    public function getName()
    {
        return '__FUNCTION__';
    }
}
