<?php

declare (strict_types=1);
namespace Convoworks\League\HTMLToMarkdown;

interface PreConverterInterface
{
    public function preConvert(ElementInterface $element) : void;
}
