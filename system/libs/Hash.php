<?php

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