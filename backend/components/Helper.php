<?php
/**
 * Provides a number of helper functions for the audit component
 */

namespace backend\components;

use backend\Audit;
use yii\helpers\ArrayHelper;

/**
 * Helper
 * @package backend\components
 */
class Helper extends \yii\base\Object
{
    /**
     * Convert the given value into a gzip compressed blob so it can be stored in the database
     * @param mixed $data
     * @param bool  $compact true to call the {@link compact()} function first
     * @return string               binary blob of data
     */
    public static function serialize($data, $compact = true)
    {
        if ($compact)
            $data = self::compact($data);

        $data = serialize($data);
        $data = self::compress($data);

        return $data;
    }

    /**
     * Re-inflates and unserializes a blob of compressed data
     * @param string $data
     * @return mixed            false if an error occurred
     */
    public static function unserialize($data)
    {
        $originalData = $data;
        $data = self::uncompress($data);

        if ($data === false)
            $data = $originalData;

        $data = @unserialize($data);
        if ($data === false)
            $data = $originalData;

        return $data;
    }

    /**
     * Compress
     * @param mixed $data
     * @return string binary blob of data
     */
    public static function compress($data)
    {
        if (Audit::getInstance()->compressData)
            $data = gzcompress($data);
        return $data;
    }

    /**
     * Compress
     * @param mixed $data
     * @return string binary blob of data
     */
    public static function uncompress($data)
    {
        if (Audit::getInstance()->compressData)
            $data = gzuncompress($data);
        return $data;
    }

    /**
     * Enumerate an array and get rid of the values that would exceed the $threshold size when serialized
     * @param array $data      Non-array data will be converted to an array
     * @param bool  $simplify  If true, replace single valued arrays by just its value.
     * @param int   $threshold serialized size to use as maximum
     * @return array
     */
    public static function compact($data, $simplify = false, $threshold = 512)
    {
        $data = ArrayHelper::toArray($data);
        if ($simplify)
            array_walk($data, function (&$value) {
                if (is_array($value) && count($value) == 1) $value = reset($value);
            });

        $tooBig = [];
        foreach ($data as $index => $value)
            if (strlen(serialize($value)) > $threshold)
                $tooBig[] = $index;

        if (count($tooBig)) {
            $data = array_diff_key($data, array_flip($tooBig));
            $data['__removed'] = $tooBig;
        }

        return $data;
    }

    /**
     * Normalize a stack trace so that all entries have the same keys and cleanup the arguments (removes anything that
     * cannot be serialized).
     * @param array $trace
     * @return array
     */
    public static function cleanupTrace($trace)
    {
        foreach ($trace as $n => $call) {
            $trace[$n]['file'] = isset($call['file']) ? $call['file'] : 'unknown';
            $trace[$n]['line'] = isset($call['line']) ? $call['line'] : 0;
            $trace[$n]['function'] = isset($call['function']) ? $call['function'] : 'unknown';
            $trace[$n]['file'] = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $trace[$n]['file']);

            // XDebug
            if (isset($trace[$n]['params'])) unset($trace[$n]['params']);

            if (isset($trace[$n]['args']))
                $trace[$n]['args'] = self::cleanupTraceArguments($trace[$n]['args']);
        }

        return $trace;
    }

    /**
     * Cleans up the given data so it can be serialized
     * @param     $args
     * @param int $recurseDepth Amount of recursion cycles before we start replacing data with "Array" etc
     * @return mixed
     */
    public static function cleanupTraceArguments($args, $recurseDepth = 3)
    {
        foreach ($args as $name => $value) {
            if (is_object($value)) {
                $class = get_class($value);
                // By default we just mention the object type
                $args[$name] = 'Object(' . $class . ')';

                if ($recurseDepth > 0) {
                    // Make sure to limit the toArray to non recursive, it's much to easy to get stuck in an infinite loop
                    $object = self::cleanupTraceArguments(ArrayHelper::toArray($value, [], false), $recurseDepth - 1);
                    $object['__class'] = $class;
                    $args[$name] = $object;
                }
            } else if (is_array($value)) {
                if ($recurseDepth > 0)
                    $args[$name] = self::cleanupTraceArguments($value, $recurseDepth - 1);
                else
                    $args[$name] = 'Array';
            }
        }
        return $args;
    }

    /**
     * Hash a long string to a short string.
     * @link http://au1.php.net/crc32#111931
     *
     * @param $data
     * @return string
     */
    public static function hash($data)
    {
        static $map = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = bcadd(sprintf('%u', crc32($data)), 0x100000000);
        $str = '';
        do {
            $str = $map[bcmod($hash, 62)] . $str;
            $hash = bcdiv($hash, 62);
        } while ($hash >= 1);
        return $str;
    }

}