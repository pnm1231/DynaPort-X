<?php

/***************
 * GLOBAL
 ***************/

/**
 * The URL which can be accessed to framwork's main file index.php
 * DO NOT add a trailing slash.
 */
define('GLBL_URL','http://localhost/DynaPort-X');

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
 * Specify whether the framework should start a session automatically
 */
define('GLBL_AUTOSTART_SESSION',false);

/**
 * Error reporting.
 */
error_reporting(E_ALL); // Development level
//error_reporting(E_STRICT); // Pre-production (testing) level
//error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE); // Production level

/**
 * Set the default timezone
 */
date_default_timezone_set('Asia/Colombo');




/***************
 * DATABASE
 ***************/

/**
 * The drive you want to use as the database server. Ex: mysql, mssql
 */
define('DB_TYPE','mysql');

/**
 * Where your database server is located. Usually it is 'localhost'.
 */
define('DB_HOST','localhost');

/**
 * The name of the database you are going to access.
 */
define('DB_NAME','dynaportx');

/**
 * Username of the connection/database.
 */
define('DB_USER','');

/**
 * Password of the connection/database.
 */
define('DB_PASS','');




/***************
 * E-MAIL
 ***************/

/**
 * The method you want to send emails. Possible values: smtp, php
 */
define('MAIL_METHOD','smtp');

/**
 * SMTP server. Usually it's 'mail.yourdomain.com'.
 */
define('MAIL_HOST','smtp.gmail.com');

/**
 * SMTP port. Usually it's 25.
 */
define('MAIL_PORT',587);

/**
 * Username of the email account.
 */
define('MAIL_USER','');

/**
 * Password of the email account.
 */
define('MAIL_PASS','');

/**
 * The name you want to display as 'Sent by'.
 */
define('MAIL_FROM_NAME','');

/**
 * The email address that you want to display as 'From'.
 */
define('MAIL_FROM_EMAIL','');

/**
 * The name you want to display as 'Reply to'.
 */
define('MAIL_REPLY_NAME','');

/**
 * The email address you want to receive replies to.
 */
define('MAIL_REPLY_EMAIL','');




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

?>