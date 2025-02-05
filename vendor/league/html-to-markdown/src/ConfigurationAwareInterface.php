<?php

declare (strict_types=1);
namespace Convoworks\League\HTMLToMarkdown;

interface ConfigurationAwareInterface
{
    public function setConfig(Configuration $config) : void;
}
