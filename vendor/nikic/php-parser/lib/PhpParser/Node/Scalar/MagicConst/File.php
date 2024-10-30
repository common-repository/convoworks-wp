<?php

namespace Convoworks\PhpParser\Node\Scalar\MagicConst;

use Convoworks\PhpParser\Node\Scalar\MagicConst;
class File extends MagicConst
{
    public function getName()
    {
        return '__FILE__';
    }
}
