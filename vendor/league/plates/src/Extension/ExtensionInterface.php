<?php

namespace Convoworks\League\Plates\Extension;

use Convoworks\League\Plates\Engine;
/**
 * A common interface for extensions.
 */
interface ExtensionInterface
{
    public function register(Engine $engine);
}
