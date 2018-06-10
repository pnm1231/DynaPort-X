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
 * Session Class.
 *
 * The session class which handles PHP sessions.
 *
 * @category    Libraries
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/Session-library
 */
class Session
{
    /**
     * @var array Session keys and values
     */
    public static $data;

    public function __construct()
    {
        // Check whether session autostart has been set. If not, do nothing.
        if (GLBL_AUTOSTART_SESSION != true) {
            self::$data = null;

            return;
        }

        // Check whether the session has started already. If not, start it.
        if (session_id() == '') {
            self::init();
        }
    }

    /**
     * Initialize a PHP session.
     */
    public static function init()
    {
        session_start();

        self::$data = &$_SESSION;
    }

    /**
     * Create a new session.
     *
     * @param string $key   The name of the session
     * @param mixed  $value The value of the session
     */
    public static function set($key, $value)
    {
        // Check whether the session has started already. If not, start it.
        if (session_id() == '') {
            self::init();
        }

        self::$data[$key] = $value;
    }

    /**
     * Retrieve an existing session.
     *
     * @param string $key The name of the session
     *
     * @return mixed Data assigned to the session key
     */
    public static function get($key)
    {
        // Check whether the session has started already. If not, start it.
        if (session_id() == '') {
            self::init();
        }

        if (isset(self::$data[$key])) {
            return self::$data[$key];
        } else {
            return false;
        }
    }

    /**
     * Delete a session.
     *
     * @param string $key The name of the session
     */
    public static function delete($key)
    {
        // Check whether the session has started already. If not, start it.
        if (session_id() == '') {
            self::init();
        }

        if (isset(self::$data[$key])) {
            unset(self::$data[$key]);
        }
    }
}
