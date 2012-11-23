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
 * https://github.com/simonhamp/routes
 * 
 * Examples:
 * 
 *  Router::add('testing/(:num)','test/$1');
 * 
 *  Router::add(array(
 *                  'testing/(:num)'      => 'test/$1',
 *                  'posts/(:any)-(:num)' => 'news/$1/$2'
 *  ));
 */

class Router {

    protected static $allow_query = true;
    protected static $routes = array();

    /**
     * Add routing rules
     * 
     * @param string $src Source URI to be matched / An array with both source and dest
     * @param string $dest The destination URI
     */
    public static function add($src,$dest=null){
        if(is_array($src)){
            foreach($src as $key=>$val){
                static::$routes[$key] = $val;
            }
        }else if($dest){
            static::$routes[$src] = $dest;
        }
    }

    /**
     * Apply routing rules
     * 
     * @param string $uri Original URI
     * @return string Routed URI
     */
    public static function route($uri){
        $qs = '';

        if(static::$allow_query && strpos($uri,'?')!==false){
            // Break the query string off and attach later
            $qs = '?'.parse_url($uri,PHP_URL_QUERY);
            $uri = str_replace($qs,'',$uri);
        }

        // Is there a literal match?
        if(isset(static::$routes[$uri])){
            return static::$routes[$uri].$qs;
        }

        // Loop through the route array looking for wild-cards
        foreach(static::$routes as $key=>$val){
            // Convert wild-cards to RegEx
            $key = str_replace(':any','.+',$key);
            $key = str_replace(':num','[0-9]+',$key);
            $key = str_replace(':nonum','[^0-9]+',$key);
            $key = str_replace(':alpha','[A-Za-z]+',$key);
            $key = str_replace(':alnum','[A-Za-z0-9]+',$key);
            $key = str_replace(':hex','[A-Fa-f0-9]+',$key);

            // Does the RegEx match?
            if(preg_match('#^'.$key.'$#',$uri)){
                // Do we have a back-reference?
                if(strpos($val,'$')!==false && strpos($key,'(')!==false){
                    $val = preg_replace('#^'.$key.'$#',$val,$uri);
                }
                return $val.$qs;
            }
        }

        return $uri.$qs;
    }

}
?>