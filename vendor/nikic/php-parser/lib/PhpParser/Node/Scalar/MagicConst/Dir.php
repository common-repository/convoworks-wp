<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class Dir extends MagicConst
{
    public function getName()
    {
        return '__DIR__';
    }
}
