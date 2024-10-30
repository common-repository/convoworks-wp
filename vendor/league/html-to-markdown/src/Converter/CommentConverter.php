<?php

declare (strict_types=1);
namespace Convoworks\League\HTMLToMarkdown\Converter;

use Convoworks\League\HTMLToMarkdown\Configuration;
use Convoworks\League\HTMLToMarkdown\ConfigurationAwareInterface;
use Convoworks\League\HTMLToMarkdown\ElementInterface;
class CommentConverter implements ConverterInterface, ConfigurationAwareInterface
{
    /** @var Configuration */
    protected $config;
    public function setConfig(Configuration $config) : void
    {
        $this->config = $config;
    }
    public function convert(ElementInterface $element) : string
    {
        if ($this->shouldPreserve($element)) {
            return '<!--' . $element->getValue() . '-->';
        }
        return '';
    }
    /**
     * @return string[]
     */
    public function getSupportedTags() : array
    {
        return ['#comment'];
    }
    private function shouldPreserve(ElementInterface $element) : bool
    {
        $preserve = $this->config->getOption('preserve_comments');
        if ($preserve === \true) {
            return \true;
        }
        if (\is_array($preserve)) {
            $value = \trim($element->getValue());
            return \in_array($value, $preserve, \true);
        }
        return \false;
    }
}
