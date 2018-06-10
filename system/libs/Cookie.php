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
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * Cookie Class.
 *
 * The cookie class which handles Cookies.
 *
 * @category    Libraries
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/Cookie-library
 */
class Cookie
{
    /**
     * Create a cookie.
     *
     * @param string $key       Name
     * @param string $value     Data
     * @param int    $expire    Expire unix timestamp (optional)
     * @param string $path      Path (optional)
     * @param string $domain    Domain (optional)
     * @param bool   $secure    Transmit over https (optional)
     * @param bool   $http_only Accessible only through HTTP (optional)
     *
     * @return bool
     */
    public static function set($key, $value, $expire = null, $path = '/', $domain = null, $secure = null, $http_only = null)
    {
        if ($domain == null) {
            // If the app is running on localhost, keep the domain as null.
            // Otherwise, make it the server name (domain).
            if ($_SERVER['SERVER_NAME'] != 'localhost') {
                $domain = $_SERVER['SERVER_NAME'];
            }
        }
        setcookie($key, $value, $expire, $path, $domain, $secure, $http_only);

        return true;
    }

    /**
     * Get a cookie.
     *
     * @param string $key Cookie name
     *
     * @return string Data
     */
    public static function get($key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        } else {
            return false;
        }
    }

    /**
     * Delete a cookie.
     *
     * @param string $key    Cookie name
     * @param string $path   Path (optional)
     * @param string $domain Domain (optional)
     *
     * @return bool
     */
    public static function delete($key, $path = '/', $domain = null)
    {
        if ($domain == null) {
            $domain = $_SERVER['SERVER_NAME'];
        }
        setcookie($key, '', time() - 3600, $path, $domain);

        return true;
    }
}
