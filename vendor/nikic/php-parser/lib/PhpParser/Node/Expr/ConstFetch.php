<?php

namespace Convoworks\PhpParser\Node\Expr;

use Convoworks\PhpParser\Node\Expr;
use Convoworks\PhpParser\Node\Name;
class ConstFetch extends Expr
{
    /** @var Name Constant name */
    public $name;
    /**
     * Constructs a const fetch node.
     *
     * @param Name  $name       Constant name
     * @param array $attributes Additional attributes
     */
    public function __construct(Name $name, array $attributes = array())
    {
        parent::__construct($attributes);
        $this->name = $name;
    }
    public function getSubNodeNames()
    {
        return array('name');
    }
}
