<?php

declare (strict_types=1);
namespace Convo\Core\Workflow;

interface IPredispatchableBlock extends \Convo\Core\Workflow\IRunnableBlock
{
    public function preDispatch(\Convo\Core\Workflow\IConvoRequest $request, \Convo\Core\Workflow\IConvoResponse $response);
}
