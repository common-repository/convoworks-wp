<?php

namespace Convo\Core\Workflow;

interface IConvoAudioRequest extends \Convo\Core\Workflow\IIntentAwareRequest
{
    public function getOffset();
    public function getAudioItemToken();
}
