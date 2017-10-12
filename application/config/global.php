<?php

/***************
 * GLOBAL
 ***************/

/**
 * Detect the Environment and load the Environment-dependent config file
 */
if(isset($_SERVER['ENVIRONMENT']) && $_SERVER['ENVIRONMENT']=='sandbox'){
    require_once __DIR__.'/global.sandbox.php';
}else{
    require_once __DIR__.'/global.production.php';
}

/**
 * The URL which can be accessed to framwork's main file index.php
 * DO NOT add a trailing slash.
 */
define('GLBL_URL',guess_base_url());

/**
 * When this is true, the system will break the URI into 4 sections.
 * 1. Module
 * 2. Controller
 * 3. Method
 * 4. Parameters
 * If false, the system will break the URI into last 3 sections.
 */
define('GLBL_MODULARIZED',true);

/**
 * The default module name of the app.
 * If the GLBL_MODULARIZED is set to false, ignore this. 
 */
define('GLBL_DEFAULT_MODULE','common');

/**
 * The default controller name of the app.
 */
define('GLBL_DEFAULT_CONTROLLER','index');

/**
 * The default method name of the app.
 */
define('GLBL_DEFAULT_METHOD','index');

/**
 * Specify whether this app should use hooks if defined.
 * Hooks can be defined by using the config/hooks.php file.
 */
define('GLBL_ENABLE_HOOKS',true);

/**
 * The name of the application folder. Default: application
 */
define('GLBL_FOLDERS_APPLICATION','application');

/**
 * The name of the library folder.
 * (Relative to the GLBL_FOLDERS_APPLICATION folder)
 * Default: libs
 */
define('GLBL_FOLDERS_LIBRARY','libs');

/**
 * Specify whether the framework should start a session automatically
 */
define('GLBL_AUTOSTART_SESSION',false);

/**
 * Error reporting.
 */
// Moved to global.ENVIRONMENT.php

/**
 * Set the default timezone
 */
date_default_timezone_set('Asia/Colombo');




/***************
 * DATABASE
 ***************/

// Moved to global.ENVIRONMENT.php




/***************
 * E-MAIL
 ***************/

// Moved to global.ENVIRONMENT.php




/***************
 * HASH
 ***************/

/**
 * The encryption method you want to use. Ex: md5, sha256
 */
define('HASH_ALGO','sha256');

/**
 * The salt (one-way encryption key) you want to add.
 * Use differnent salts for different applications.
 * IMPORTANT: Make sure you change this only once, at the beginning.
 * Once you change this, previously hashed strings won't match anymore.
 */
define('HASH_SALT','MySalt');




/***************
 * Dencrypt Lib
 ***************/

/**
 * The encryption key (two-way encryption/decryption)
 */
define('DENCRYPT_KEY','MyKey');
