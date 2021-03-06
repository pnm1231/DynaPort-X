<?php

/**
 * DynaPort X.
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 *
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * Profiler Class.
 *
 * A simple profiler class to be used in benchmarking and profiling.
 *
 * @category    Libraries
 *
 * @author      Prasad Nayanajith
 *
 * @link        http://www.dynamiccodes.com/dynaportx/doc/libs/profiler
 */
class Profiler
{
    /**
     * @var float Started time
     */
    public static $timeStart = [];

    /**
     * @var float Stopped time
     */
    public static $timeStop = [];

    /**
     * Start the timer.
     *
     * @param string $component Name of the component
     */
    public static function timerStart($component = 'general')
    {
        self::$timeStart[$component] = microtime(true);
    }

    /**
     * Stop the timer.
     *
     * @param string $component Name of the component
     */
    public static function timerStop($component = 'general')
    {
        self::$timeStop[$component] = microtime(true);
    }

    /**
     * Display elapsed time.
     *
     * @param string $component Name of the component
     */
    public static function timerDisplay($component = 'general')
    {
        echo number_format(self::$timeStop[$component] - self::$timeStart[$component], 5).' s';
    }

    /**
     * Display memory usage.
     */
    public static function memoryDisplay()
    {
        $memUsage = memory_get_peak_usage();
        if ($memUsage <= 1024) {
            $memType = 'B';
        } elseif ($memUsage <= 1024 * 1024) {
            $memUsage = $memUsage / 1024;
            $memType = 'KB';
        } elseif ($memUsage <= 1024 * 1024 * 1024) {
            $memUsage = $memUsage / (1024 * 1024);
            $memType = 'MB';
        }
        echo round($memUsage, 2).' '.$memType;
    }
}
