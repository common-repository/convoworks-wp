<?php

namespace Convoworks\PhpParser\Node\Stmt;

use Convoworks\PhpParser\Node;
abstract class TraitUseAdaptation extends Node\Stmt
{
    /** @var Node\Name Trait name */
    public $trait;
    /** @var string Method name */
    public $method;
}
