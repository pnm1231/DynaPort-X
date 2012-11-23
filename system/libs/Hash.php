<?php

/*
 * This file is part of the DynaPort X package.
 *
 * (c) Prasad Nayanajith <prasad.n@dynamiccodes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

class Hash {    

    /**
     * Generate a HASH string
     * 
     * @param string $data The string to make a hash
     * @return string The hash
     */
    public static function create($data){
        $context = hash_init(HASH_ALGO,HASH_HMAC,HASH_SALT);
        hash_update($context,$data);
        return hash_final($context);
    }

}

?>