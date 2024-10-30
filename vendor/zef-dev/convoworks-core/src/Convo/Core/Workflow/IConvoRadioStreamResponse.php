<?php

namespace Convo\Core\Workflow;

use Convo\Core\Media\IRadioStream;
interface IConvoRadioStreamResponse extends \Convo\Core\Workflow\IConvoAudioResponse
{
    public function startRadioStream(IRadioStream $radioStream);
    public function stopRadioStream();
}
