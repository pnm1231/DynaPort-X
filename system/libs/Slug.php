<?php

/**
 * DynaPort X
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    DynaPort X
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 * @version    2.0.0
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * Slug Class
 *
 * Convert strings to search engine friendly strings which can be used for URLs.
 *
 * @package     DynaPort X
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/libs/slug
 */
class Slug {

    /**
     * Fix unicode characters
     * 
     * @param string $text String to fix
     * @return string Unicode-fixed string
     */
    private function unicode_fix($text){
        $badwordchars = array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6");
        $fixedwordchars = array("'", "'", '"', '"', '-', '--', '...');
        return str_replace($badwordchars,$fixedwordchars,$text);
    }

    /**
     * Create a slug
     * 
     * @param string $string The string you to convert
     * @param array @filter Characters to ignore
     */
    static function create($string,$filter=array(' '=>'-','.'=>'-','_'=>'-','/'=>'-','['=>'-',']'=>'')){
        $str = self::unicode_fix($string);
        $str = strtolower(strtr($str,$filter));

        // Valid characters in a CSS identifier are:
        // - the hyphen (U+002D)
        // - a-z (U+0030 - U+0039)
        // - A-Z (U+0041 - U+005A)
        // - the underscore (U+005F)
        // - 0-9 (U+0061 - U+007A)
        // - ISO 10646 characters U+00A1 and higher
        // We strip out any character not in the above list.
        $str = preg_replace('/&#039;/',"'",$str);
        $str = preg_replace('/[^\x{002D}\x{0030}-\x{0039}\x{0041}-\x{005A}\x{005F}\x{0061}-\x{007A}\x{00A1}-\x{FFFF}]/u', '', $str);
        $str = preg_replace('/-+/', '-', $str);
        $str = preg_replace('/^-|-$/', '', $str);
        return $str;
    }

}

?>