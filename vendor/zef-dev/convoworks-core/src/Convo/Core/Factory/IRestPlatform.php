<?php

declare (strict_types=1);
namespace Convo\Core\Factory;

use Psr\Http\Server\RequestHandlerInterface;
interface IRestPlatform extends \Convo\Core\Factory\IPlatform
{
    /**
     * @return RequestHandlerInterface
     */
    public function getPublicRestHandler();
}
