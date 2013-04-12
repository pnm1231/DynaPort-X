<?php

/**
 * Use this file to define all routing rules
 **

/**
 * Routing Method #1
 **

Router::add('testing/(:num)','test/$1');

/**
 * Routing Method #2
 **

Router::add(array(
                    'testing/(:num)'      => 'test/$1',
                    'posts/(:any)-(:num)' => 'news/$1/$2'
        ));

/**
 * Following matching criterias are available
 **

:any    - anything
:num    - numerics
:nonum  - non-numerics
:alpha  - alpha
:alnum  - alpha-numeric
:hex    - hexadecimal

 */

Router::add('blog/post/(:num)','blog/post/index/$1');

?>