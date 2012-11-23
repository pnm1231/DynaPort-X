<?php

/**
 * Load global configurations
 */
if(file_exists('application/config/global.php')){
    require 'application/config/global.php';
}else{
    die('Error: Global configuration file is not available.');
}

/**
 * Include the class autload helper
 */
if(file_exists('system/helpers/autoload.php')){
    require 'system/helpers/autoload.php';
}else{
    die('Error: Class autoload helper is not available.');
}

/**
 * Include the extend helper
 */
if(file_exists('system/helpers/extends.php')){
    require 'system/helpers/extends.php';
}

/**
 * Initialize a PHP session
 */
Session::init();

/**
 * Include routes
 */
if(file_exists('application/config/routes.php')){
    require 'application/config/routes.php';
}

/**
 * Call the Bootstrap class
 */
new Bootstrap();

?>