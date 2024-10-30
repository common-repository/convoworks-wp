<?php

declare (strict_types=1);
namespace Convoworks\League\HTMLToMarkdown;

use Convoworks\League\HTMLToMarkdown\Converter\BlockquoteConverter;
use Convoworks\League\HTMLToMarkdown\Converter\CodeConverter;
use Convoworks\League\HTMLToMarkdown\Converter\CommentConverter;
use Convoworks\League\HTMLToMarkdown\Converter\ConverterInterface;
use Convoworks\League\HTMLToMarkdown\Converter\DefaultConverter;
use Convoworks\League\HTMLToMarkdown\Converter\DivConverter;
use Convoworks\League\HTMLToMarkdown\Converter\EmphasisConverter;
use Convoworks\League\HTMLToMarkdown\Converter\HardBreakConverter;
use Convoworks\League\HTMLToMarkdown\Converter\HeaderConverter;
use Convoworks\League\HTMLToMarkdown\Converter\HorizontalRuleConverter;
use Convoworks\League\HTMLToMarkdown\Converter\ImageConverter;
use Convoworks\League\HTMLToMarkdown\Converter\LinkConverter;
use Convoworks\League\HTMLToMarkdown\Converter\ListBlockConverter;
use Convoworks\League\HTMLToMarkdown\Converter\ListItemConverter;
use Convoworks\League\HTMLToMarkdown\Converter\ParagraphConverter;
use Convoworks\League\HTMLToMarkdown\Converter\PreformattedConverter;
use Convoworks\League\HTMLToMarkdown\Converter\TextConverter;
final class Environment
{
    /** @var Configuration */
    protected $config;
    /** @var ConverterInterface[] */
    protected $converters = [];
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = new Configuration($config);
        $this->addConverter(new DefaultConverter());
    }
    public function getConfig() : Configuration
    {
        return $this->config;
    }
    public function addConverter(ConverterInterface $converter) : void
    {
        if ($converter instanceof ConfigurationAwareInterface) {
            $converter->setConfig($this->config);
        }
        foreach ($converter->getSupportedTags() as $tag) {
            $this->converters[$tag] = $converter;
        }
    }
    public function getConverterByTag(string $tag) : ConverterInterface
    {
        if (isset($this->converters[$tag])) {
            return $this->converters[$tag];
        }
        return $this->converters[DefaultConverter::DEFAULT_CONVERTER];
    }
    /**
     * @param array<string, mixed> $config
     */
    public static function createDefaultEnvironment(array $config = []) : Environment
    {
        $environment = new static($config);
        $environment->addConverter(new BlockquoteConverter());
        $environment->addConverter(new CodeConverter());
        $environment->addConverter(new CommentConverter());
        $environment->addConverter(new DivConverter());
        $environment->addConverter(new EmphasisConverter());
        $environment->addConverter(new HardBreakConverter());
        $environment->addConverter(new HeaderConverter());
        $environment->addConverter(new HorizontalRuleConverter());
        $environment->addConverter(new ImageConverter());
        $environment->addConverter(new LinkConverter());
        $environment->addConverter(new ListBlockConverter());
        $environment->addConverter(new ListItemConverter());
        $environment->addConverter(new ParagraphConverter());
        $environment->addConverter(new PreformattedConverter());
        $environment->addConverter(new TextConverter());
        return $environment;
    }
}
