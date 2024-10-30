<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class Namespace_ extends MagicConst
{
    public function getName()
    {
        return '__NAMESPACE__';
    }
}
