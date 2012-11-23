<?php

/***************
 * GLOBAL
 ***************/

// The URL which can be accessed to framwork's main file index.php
// DO NOT add a trailing slash.
define('GLBL_URL','http://localhost/MVC2');

// Display only Errors while hiding notices and warnings
//error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
//error_reporting(E_STRICT);
error_reporting(E_ALL);

// Timezone
date_default_timezone_set('Asia/Colombo');




/***************
 * DATABASE
 ***************/

// The drive you want to use as the database server. Ex: mysql, mssql
define('DB_TYPE','mysql');

// Where your database server is located. Usually it is 'localhost'.
define('DB_HOST','localhost');

// The name of the database you are going to access.
define('DB_NAME','');

// Username of the connection/database.
define('DB_USER','');

// Password of the connection/database.
define('DB_PASS','');




/***************
 * E-MAIL
 ***************/

// The method you want to send emails. Possible values: smtp, php
define('MAIL_METHOD','smtp');

// SMTP server. Usually it's 'mail.yourdomain.com'.
define('MAIL_HOST','smtp.gmail.com');

// SMTP port. Usually it's 25.
define('MAIL_PORT',587);

// Username of the email account.
define('MAIL_USER','');

// Password of the email account.
define('MAIL_PASS','');

// The email address that you want to display as 'From'.
define('MAIL_FROM_NAME','');

// The name you want to display as 'Sent by'.
define('MAIL_FROM_EMAIL','');




/***************
 * HASH
 ***************/

// The encryption method you want to use. Ex: md5, sha256
define('HASH_ALGO','md5');

// The salt you want to add.
// Use differnent salts for different applications.
// IMPORTANT: Make sure you change this only once, at the beginning.
// Once you change this, previously hashed contents (passwords) won't match anymore.
define('HASH_SALT','MySalt');




/***************
 * Dencrypt Lib
 ***************/

// The encryption key
define('DENCRYPT_KEY','MyKey');

?>