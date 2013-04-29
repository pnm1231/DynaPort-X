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
 * @link       http://www.dynamiccodes.com/dynaportx/doc/libs/redirect
 * @since      File available since Release 0.2.0
 */

/**
 * Redirect Class
 *
 * The redirect class which handles all header redirects.
 *
 * @package     DynaPort X
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/libs/hash
 */
class Redirect {

    /**
     * Redirect to a specific URL
     * 
     * @param string $uri The URL or the Path
     */
    public static function go($uri){
        if(!preg_match('/^(http|https)\:/',$uri)){
            $uri = ltrim($uri,'/');
            $uri = GLBL_URL.'/'.$uri;
        }
        if(!empty($uri) && filter_var($uri,FILTER_SANITIZE_URL)){
            if(!headers_sent()){
                header('location: '.$uri);
                exit;
            }else{
                new Error('Unable to redirect',500,'DPX.Lib.Redirect.go: Cannot redirect since the header is already sent');
            }
        }else{
            new Error('Unable to redirect',500,'DPX.Lib.Redirect.go: The provided redirect URL is invalid');
        }
    }

}

?>