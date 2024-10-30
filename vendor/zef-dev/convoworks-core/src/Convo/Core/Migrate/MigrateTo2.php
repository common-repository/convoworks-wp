<?php

declare (strict_types=1);
namespace Convo\Core\Migrate;

class MigrateTo2 extends \Convo\Core\Migrate\AbstractMigration
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getVersion()
    {
        return 2;
    }
    protected function _migrateComponent($componentData)
    {
        $rename = [
            // GOOGLE
            'Convoworks\\Adm\\Nlp\\Alexax\\GoogleRequestFilter' => '\\Convo\\Pckg\\Gnlp\\GoogleNlpRequestFilter',
            'Convoworks\\Adm\\Nlp\\Filter\\OrFilter' => '\\Convo\\Pckg\\Gnlp\\Filters\\OrFilter',
            'Convoworks\\Adm\\Nlp\\Filter\\AndFilter' => '\\Convo\\Pckg\\Gnlp\\Filters\\AndFilter',
            'Convoworks\\Adm\\Nlp\\Filter\\NopFilter' => '\\Convo\\Pckg\\Gnlp\\Filters\\NopFilter',
            'Convoworks\\Adm\\Nlp\\Filter\\NumberFilter' => '\\Convo\\Pckg\\Gnlp\\Filters\\NumberFilter',
            'Convoworks\\Adm\\Nlp\\Filter\\PartOfSpeechValueFilter' => '\\Convo\\Pckg\\Gnlp\\Filters\\PartOfSpeechValueFilter',
            'Convoworks\\Adm\\Nlp\\Filter\\PriceRangeFilter' => '\\Convo\\Pckg\\Gnlp\\Filters\\PriceRangeFilter',
            'Convoworks\\Adm\\Nlp\\Filter\\RelationFilter' => '\\Convo\\Pckg\\Gnlp\\Filters\\RelationFilter',
            // AMAZON
            'Convoworks\\Adm\\Alexax\\Amz\\AmazonIntentRequestFilter' => '\\Convo\\Pckg\\Alexa\\AmazonIntentRequestFilter',
            'Convoworks\\Adm\\Alexax\\Amz\\AmazonIntentReader' => '\\Convo\\Pckg\\Alexa\\AmazonIntentReader',
            'Convoworks\\Adm\\Alexax\\Amz\\AmazonConfiguration' => '\\Convo\\Core\\Adapters\\Alexa\\AmazonConfiguration',
            // CORE ELEMS
            'Convoworks\\Adm\\Alexax\\Elem\\AudioPlayer' => '\\Convo\\Pckg\\Core\\Elements\\AudioPlayer',
            'Convoworks\\Adm\\Alexax\\Elem\\CommentElement' => '\\Convo\\Pckg\\Core\\Elements\\CommentElement',
            'Convoworks\\Adm\\Alexax\\Elem\\ConversationBlock' => '\\Convo\\Pckg\\Core\\Elements\\ConversationBlock',
            'Convoworks\\Adm\\Alexax\\Elem\\ElementCollection' => '\\Convo\\Pckg\\Core\\Elements\\ElementCollection',
            'Convoworks\\Adm\\Alexax\\Elem\\ElementRandomizer' => '\\Convo\\Pckg\\Core\\Elements\\ElementRandomizer',
            'Convoworks\\Adm\\Alexax\\Elem\\ElementsSubroutine' => '\\Convo\\Pckg\\Core\\Elements\\ElementsSubroutine',
            'Convoworks\\Adm\\Alexax\\Elem\\EndSessionElement' => '\\Convo\\Pckg\\Core\\Elements\\EndSessionElement',
            'Convoworks\\Adm\\Alexax\\Elem\\FileReader' => '\\Convo\\Pckg\\Core\\Elements\\FileReader',
            'Convoworks\\Adm\\Alexax\\Elem\\GoogleAnalyticsElement' => '\\Convo\\Pckg\\Core\\Elements\\GoogleAnalyticsElement',
            'Convoworks\\Adm\\Alexax\\Elem\\HttpQueryElement' => '\\Convo\\Pckg\\Core\\Elements\\HttpQueryElement',
            'Convoworks\\Adm\\Alexax\\Elem\\IfElement' => '\\Convo\\Pckg\\Core\\Elements\\IfElement',
            'Convoworks\\Adm\\Alexax\\Elem\\IfElementCase' => '\\Convo\\Pckg\\Core\\Elements\\IfElementCase',
            'Convoworks\\Adm\\Alexax\\Elem\\LoopElement' => '\\Convo\\Pckg\\Core\\Elements\\LoopElement',
            'Convoworks\\Adm\\Alexax\\Elem\\MysqliQueryElement' => '\\Convo\\Pckg\\Core\\Elements\\MysqliQueryElement',
            'Convoworks\\Adm\\Alexax\\Elem\\ReadElementsSubroutine' => '\\Convo\\Pckg\\Core\\Elements\\ReadElementsSubroutine',
            'Convoworks\\Adm\\Alexax\\Elem\\SetParamElement' => '\\Convo\\Pckg\\Core\\Elements\\SetParamElement',
            'Convoworks\\Adm\\Alexax\\Elem\\SetStateElement' => '\\Convo\\Pckg\\Core\\Elements\\SetStateElement',
            'Convoworks\\Adm\\Alexax\\Elem\\SimpleEvalIfTest' => '\\Convo\\Pckg\\Core\\Elements\\SimpleEvalIfTest',
            'Convoworks\\Adm\\Alexax\\Elem\\SimpleTextResponse' => '\\Convo\\Pckg\\Core\\Elements\\SimpleTextResponse',
            // CORE PROCESSORS
            'Convoworks\\Adm\\Alexax\\Proc\\ProcessorSubroutine' => '\\Convo\\Pckg\\Core\\Processors\\ProcessorSubroutine',
            'Convoworks\\Adm\\Alexax\\Proc\\ProcessProcessorSubroutine' => '\\Convo\\Pckg\\Core\\Processors\\ProcessProcessorSubroutine',
            'Convoworks\\Adm\\Alexax\\Proc\\SimpleProcessor' => '\\Convo\\Pckg\\Core\\Processors\\SimpleProcessor',
            'Convoworks\\Adm\\Alexax\\Proc\\YesNoProcessor' => '\\Convo\\Pckg\\Core\\Processors\\YesNoProcessor',
            // CORE FILTERS
            'Convoworks\\Adm\\Alexax\\Txt\\PlainTextRequestFilter' => '\\Convo\\Pckg\\Core\\Filters\\PlainTextRequestFilter',
            'Convoworks\\Adm\\Alexax\\Txt\\Flt\\AndFilter' => '\\Convo\\Pckg\\Core\\Filters\\Flt\\AndFilter',
            'Convoworks\\Adm\\Alexax\\Txt\\Flt\\OrFilter' => '\\Convo\\Pckg\\Core\\Filters\\Flt\\OrFilter',
            'Convoworks\\Adm\\Alexax\\Txt\\Flt\\RegexFilter' => '\\Convo\\Pckg\\Core\\Filters\\Flt\\RegexFilter',
            'Convoworks\\Adm\\Alexax\\Txt\\Flt\\StriposFilter' => '\\Convo\\Pckg\\Core\\Filters\\Flt\\StriposFilter',
            'Convoworks\\Adm\\Alexax\\NopRequestFilter' => '\\Convo\\Pckg\\Core\\Filters\\NopRequestFilter',
            // CORE INIT
            'Convoworks\\Adm\\Alexax\\Init\\MysqlConnectionComponent' => '\\Convo\\Pckg\\Core\\Init\\MysqlConnectionComponent',
        ];
        if (isset($rename[$componentData['class']])) {
            $this->_logger->debug('Changing component class [' . $componentData['class'] . '] to [' . $rename[$componentData['class']] . ']');
            $componentData['class'] = $rename[$componentData['class']];
        }
        if ($componentData['namespace'] === 'amazon') {
            $componentData['namespace'] = 'convo-alexa';
        } else {
            if ($componentData['namespace'] === 'google-nlp') {
                $componentData['namespace'] = 'convo-gnlp';
            }
        }
        return $componentData;
    }
}
