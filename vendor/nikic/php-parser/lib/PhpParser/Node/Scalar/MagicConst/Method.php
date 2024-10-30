<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class Method extends MagicConst
{
    public function getName()
    {
        return '__METHOD__';
    }
}
