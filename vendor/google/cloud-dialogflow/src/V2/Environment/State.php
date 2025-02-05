<?php

# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/environment.proto
namespace Google\Cloud\Dialogflow\V2\Environment;

use UnexpectedValueException;
/**
 * Represents an environment state. When an environment is pointed to a new
 * agent version, the environment is temporarily set to the `LOADING` state.
 * During that time, the environment keeps on serving the previous version of
 * the agent. After the new agent version is done loading, the environment is
 * set back to the `RUNNING` state.
 *
 * Protobuf type <code>google.cloud.dialogflow.v2.Environment.State</code>
 */
class State
{
    /**
     * Not specified. This value is not used.
     *
     * Generated from protobuf enum <code>STATE_UNSPECIFIED = 0;</code>
     */
    const STATE_UNSPECIFIED = 0;
    /**
     * Stopped.
     *
     * Generated from protobuf enum <code>STOPPED = 1;</code>
     */
    const STOPPED = 1;
    /**
     * Loading.
     *
     * Generated from protobuf enum <code>LOADING = 2;</code>
     */
    const LOADING = 2;
    /**
     * Running.
     *
     * Generated from protobuf enum <code>RUNNING = 3;</code>
     */
    const RUNNING = 3;
    private static $valueToName = [self::STATE_UNSPECIFIED => 'STATE_UNSPECIFIED', self::STOPPED => 'STOPPED', self::LOADING => 'LOADING', self::RUNNING => 'RUNNING'];
    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(\sprintf('Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }
    public static function value($name)
    {
        $const = __CLASS__ . '::' . \strtoupper($name);
        if (!\defined($const)) {
            throw new UnexpectedValueException(\sprintf('Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return \constant($const);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Cloud\Dialogflow\V2\Environment\State::class, \Google\Cloud\Dialogflow\V2\Environment_State::class);
