<?php

declare (strict_types=1);
namespace Convo\Core\Workflow;

interface ISpecialRoleRequest extends \Convo\Core\Workflow\IConvoRequest
{
    /**
     *
     * @return string
     */
    public function getSpecialRole();
}
