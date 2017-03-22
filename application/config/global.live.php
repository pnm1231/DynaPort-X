<?php

/**
 * Error reporting.
 */
//error_reporting(E_ALL); // Development level
//error_reporting(E_STRICT); // Pre-production (testing) level
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE); // Production level




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
define('DB_HOST','127.0.0.1');

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