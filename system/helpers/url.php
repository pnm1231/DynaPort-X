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
 * @link       http://www.dynamiccodes.com/dynaportx/doc/helpers/url
 * @since      File available since Release 2.0.36
 */

/**
 * Guess the base URL
 */
function guess_base_url(){
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off')?'https':'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = preg_replace('@/index.php$@','',$_SERVER['PHP_SELF']);
    return $protocol.'://'.$host.$path;
}

?>