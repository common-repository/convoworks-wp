<?php

namespace Convo\Pckg\Forms;

use Convo\Core\Workflow\IConversationElement;
use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
use Convo\Core\Params\IServiceParamsScope;
class LoadEntryElement extends \Convo\Pckg\Forms\AbstractFormsElement
{
    private $_entryId;
    private $_resultVar;
    /**
     * @var IConversationElement[]
     */
    private $_ok = array();
    /**
     * @param array $properties
     */
    public function __construct($properties)
    {
        parent::__construct($properties);
        $this->_entryId = $properties['entry_id'];
        $this->_resultVar = $properties['result_var'];
        foreach ($properties['ok'] as $element) {
            $this->_ok[] = $element;
            $this->addChild($element);
        }
    }
    public function read(IConvoRequest $request, IConvoResponse $response)
    {
        $context = $this->_getFormsContext();
        $entry_id = $this->evaluateString($this->_entryId);
        $params = $this->getService()->getComponentParams(IServiceParamsScope::SCOPE_TYPE_REQUEST, $this);
        $this->_logger->info('Loading entry with id [' . $entry_id . ']');
        $data = [];
        $data['entry'] = $context->getEntry($entry_id);
        $this->_logger->info('Loaded entry with id [' . $entry_id . ']');
        $params->setServiceParam($this->_resultVar, $data);
        foreach ($this->_ok as $elem) {
            $elem->read($request, $response);
        }
    }
    // UTIL
    public function __toString()
    {
        return parent::__toString() . '[' . $this->_entryId . ']';
    }
}
