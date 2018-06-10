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
 * Random String Generator.
 *
 * This class generates random strings in any length with custom filters.
 *
 * @category    Libraries
 *
 * @author      Prasad Nayanajith
 *
 * @link        http://www.dynamiccodes.com/dynaportx/doc/libs/randomstring
 */
class RandomString
{
    /**
     * Generate a random string.
     *
     * @param int   $length Length of the string
     * @param array $filter Characters to ignore
     *
     * @return string Random string
     */
    public static function generate($length = 6, $filter = [])
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (count($filter) > 0) {
            $filter = strtoupper(implode('|', $filter));
            $characters = preg_replace('/('.$filter.')/i', '', $characters);
        }
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
