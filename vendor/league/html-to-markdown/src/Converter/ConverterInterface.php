<?php

declare (strict_types=1);
namespace Convoworks\League\HTMLToMarkdown\Converter;

use Convoworks\League\HTMLToMarkdown\ElementInterface;
interface ConverterInterface
{
    public function convert(ElementInterface $element) : string;
    /**
     * @return string[]
     */
    public function getSupportedTags() : array;
}
