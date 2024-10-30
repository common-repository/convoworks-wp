<?php

namespace Convo\Services;

use Convoworks\Monolog\Handler\NullHandler;
use Convoworks\Monolog\Handler\StreamHandler;
use Convoworks\Monolog\Logger;
use Convoworks\Zef\Monolog\MonologFormatter;
class LoggerHandlerFactory
{
    public static function createHandler($path, $filename, $level)
    {
        if (empty($path) || empty($filename) || empty($level)) {
            return new NullHandler();
        } else {
            $handler = new StreamHandler($path . '/' . $filename, Logger::toMonologLevel($level));
            $handler->setFormatter(new MonologFormatter());
            return $handler;
        }
    }
}
