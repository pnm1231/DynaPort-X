<?php

/**
 * DynaPort X.
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 *
 * @link       http://www.dynamiccodes.com/dynaportx/doc
 * @since      File available since Release 0.2.0
 */
if (!isset($_SERVER['ENVIRONMENT']) && preg_match('@localhost|\.local@', $_SERVER['HTTP_HOST'])) {
    $_SERVER['ENVIRONMENT'] = 'sandbox';
} else {
    $_SERVER['ENVIRONMENT'] = 'production';
}

// In Sandbox environment, display all errors
if ($_SERVER['ENVIRONMENT'] == 'sandbox') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Take the current directory path as the global path for all includes
define('GLBL_PATH', __DIR__);

// Load global configurations.
if (file_exists(GLBL_PATH.'/application/config/global.php')) {
    //Load the URL helper in case it's needed.
    if (file_exists(GLBL_PATH.'/system/helpers/url.php')) {
        require GLBL_PATH.'/system/helpers/url.php';
    }
    require GLBL_PATH.'/application/config/global.php';
} else {
    die('Error: Global configuration file is not available.');
}

// Load the class autoload helper.
if (file_exists(GLBL_PATH.'/system/helpers/autoload.php')) {
    require GLBL_PATH.'/system/helpers/autoload.php';
} else {
    die('Error: Auto-load helper is not available.');
}

// Load the exception handle helper.
if (file_exists(GLBL_PATH.'/system/helpers/exceptions.php')) {
    require GLBL_PATH.'/system/helpers/exceptions.php';
} else {
    die('Error: Exception helper is not available.');
}

// Run DynaPort X!
new DynaPortX();
