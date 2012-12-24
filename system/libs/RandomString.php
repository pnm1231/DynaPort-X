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

/**
 * Generate a string with random characters
 */
class RandomString {

    /**
     * Generate
     * 
     * @param int $length Length of the string
     * @param array $filter Characters to ignore
     * @return string Random string
     */
    public static function generate($length=6,$filter=array()){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if(count($filter)>0){
            $filter = implode('|',$filter);
            $characters = preg_replace('/('.$filter.')/i','',$characters);
        }
        $randomString = '';
        for($i=0;$i<$length;$i++){
            $randomString .= $characters[rand(0,strlen($characters)-1)];
        }
        return $randomString;
    }

}

?>