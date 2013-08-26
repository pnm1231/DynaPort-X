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
 * Hash Class
 *
 * The one-way encrypt class.
 *
 * @package     DynaPort X
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/libs/hash
 */
class Hash {    

    /**
     * Generate a HASH string
     * 
     * @param string $data The string to make a hash
     * @param string $salt (optional) The salt it should use
     * @return string The hash
     */
    public static function create($data,$salt=HASH_SALT){
        $context = hash_init(HASH_ALGO,HASH_HMAC,$salt);
        hash_update($context,$data);
        return hash_final($context);
    }

}

?>