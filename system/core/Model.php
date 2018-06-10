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
 * @link       https://github.com/pnm1231/DynaPort-X/wiki
 * @since      File available since Release 0.2.0
 */

/**
 * Model Class.
 *
 * The main model class of the framework.
 *
 * @category    Core
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/Models
 */
class Model
{
    /**
     * Database class.
     *
     * @var \Database
     */
    protected $db;

    /**
     * Preserved database object.
     *
     * @var \Database
     */
    public static $dbInstance;

    public function __construct()
    {

        // Check if the database name and the user is defined.
        // If defined, call the database.
        if (DB_NAME && DB_USER) {

            // Check if there are no preserved DB instances.
            // If there aren't any, create a new DB object.
            // If there is one, use it as the DB object without re-connecting.
            if (empty(self::$dbInstance)) {
                $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
                self::$dbInstance = $this->db;
            } else {
                $this->db = self::$dbInstance;
            }
        }
    }

    /**
     * Get the last inserted ID.
     *
     * @return string Last inserted ID
     */
    public function last_id()
    {
        return $this->db->lastInsertId();
    }
}
