<?php

declare (strict_types=1);
namespace Convoworks\Zef\Zel;

interface IValueAdapter
{
    public function keys();
    public function get();
}
