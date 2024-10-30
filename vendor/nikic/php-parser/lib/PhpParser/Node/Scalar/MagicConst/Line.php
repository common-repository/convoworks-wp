<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class Line extends MagicConst
{
    public function getName()
    {
        return '__LINE__';
    }
}
