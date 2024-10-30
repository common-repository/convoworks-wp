<?php

namespace Convoworks\PhpParser\ErrorHandler;

use Convoworks\PhpParser\Error;
use Convoworks\PhpParser\ErrorHandler;
/**
 * Error handler that handles all errors by throwing them.
 *
 * This is the default strategy used by all components.
 */
class Throwing implements ErrorHandler
{
    public function handleError(Error $error)
    {
        throw $error;
    }
}
