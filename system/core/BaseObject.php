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
 * Object Class.
 *
 * This class provides various hacks and similar functions to manipulate objects.
 *
 * @category    Core
 *
 * @author      Prasad Nayanajith
 *
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/object
 */
class BaseObject
{
    /**
     * Call to a method of a class.
     *
     * This can be used as a hack to fix the slowness of 'call_user_func_array' function.
     *
     * @param string $class  Class name
     * @param string $method Method to call
     * @param array  $params List of parameters
     *
     * @return \BaseObject
     */
    public static function callMethod(&$class, $method, $params = [])
    {
        if (isset($params)) {
            if (!is_array($params)) {
                $params = [$params];
            }
        } else {
            $params = [];
        }
        switch (count($params)) {
            case 0:
                return $class->{$method}();
            case 1:
                return $class->{$method}($params[0]);
            case 2:
                return $class->{$method}($params[0], $params[1]);
            case 3:
                return $class->{$method}($params[0], $params[1], $params[2]);
            case 4:
                return $class->{$method}($params[0], $params[1], $params[2], $params[3]);
            default:
                return call_user_func_array([$class, $method], $params);
        }
    }
}
