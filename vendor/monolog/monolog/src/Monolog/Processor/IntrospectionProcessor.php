<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Convoworks\Monolog\Processor;

use Convoworks\Monolog\Logger;
/**
 * Injects line/file:class/function where the log message came from
 *
 * Warning: This only works if the handler processes the logs directly.
 * If you put the processor on a handler that is behind a FingersCrossedHandler
 * for example, the processor will only be called once the trigger level is reached,
 * and all the log records will have the same file/line/.. data from the call that
 * triggered the FingersCrossedHandler.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class IntrospectionProcessor implements ProcessorInterface
{
    private $level;
    private $skipClassesPartials;
    private $skipStackFramesCount;
    private $skipFunctions = ['call_user_func', 'call_user_func_array'];
    /**
     * @param string|int $level The minimum logging level at which this Processor will be triggered
     */
    public function __construct($level = Logger::DEBUG, array $skipClassesPartials = [], int $skipStackFramesCount = 0)
    {
        $this->level = Logger::toMonologLevel($level);
        $this->skipClassesPartials = \array_merge(['Monolog\\'], $skipClassesPartials);
        $this->skipStackFramesCount = $skipStackFramesCount;
    }
    public function __invoke(array $record) : array
    {
        // return if the level is not high enough
        if ($record['level'] < $this->level) {
            return $record;
        }
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        // skip first since it's always the current method
        \array_shift($trace);
        // the call_user_func call is also skipped
        \array_shift($trace);
        $i = 0;
        while ($this->isTraceClassOrSkippedFunction($trace, $i)) {
            if (isset($trace[$i]['class'])) {
                foreach ($this->skipClassesPartials as $part) {
                    if (\strpos($trace[$i]['class'], $part) !== \false) {
                        $i++;
                        continue 2;
                    }
                }
            } elseif (\in_array($trace[$i]['function'], $this->skipFunctions)) {
                $i++;
                continue;
            }
            break;
        }
        $i += $this->skipStackFramesCount;
        // we should have the call source now
        $record['extra'] = \array_merge($record['extra'], ['file' => isset($trace[$i - 1]['file']) ? $trace[$i - 1]['file'] : null, 'line' => isset($trace[$i - 1]['line']) ? $trace[$i - 1]['line'] : null, 'class' => isset($trace[$i]['class']) ? $trace[$i]['class'] : null, 'function' => isset($trace[$i]['function']) ? $trace[$i]['function'] : null]);
        return $record;
    }
    private function isTraceClassOrSkippedFunction(array $trace, int $index)
    {
        if (!isset($trace[$index])) {
            return \false;
        }
        return isset($trace[$index]['class']) || \in_array($trace[$index]['function'], $this->skipFunctions);
    }
}
