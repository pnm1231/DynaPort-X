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
 * Hooks Class.
 *
 * The hooks class which enables extending the core without editing core files,
 * but placing hooks at certain points of the framework.
 *
 * @category    Core
 *
 * @author      Prasad Nayanajith
 *
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/hooks
 */
class Hooks
{
    /**
     * Store registered hooks.
     *
     * @var array List of hooks
     */
    protected static $hooks = [];

    /**
     * Store loaded hook objects.
     *
     * @var array Loaded hook objects
     */
    protected static $loadedHooks = [];

    /**
     * Add a hook.
     *
     * @param string $point  At which point this hook should get called
     * @param string $path   Path of the class file, relative to application folder
     * @param string $file   File name
     * @param string $class  Class name
     * @param string $method Specifig method (optional)
     * @param array  $params Parameters to pass (optional)
     */
    public static function add($point, $path, $file, $class, $method = '', $params = [])
    {
        if ($point && $class) {
            self::$hooks[$point][] = [$path, $file, $class, $method, $params];
        }
    }

    /**
     * Get registered hooks.
     *
     * @param string $point The point name
     *
     * @return array Registered hooks
     */
    public static function get($point)
    {
        if (!empty(self::$hooks) && isset($hooks[$point])) {
            return self::$hooks[$point];
        }
    }

    /**
     * Run a hook.
     *
     * @param string $point Point name
     * @param array  $data  Data, if any (optional)
     *
     * @return bool Successfully executed or not
     */
    public static function run($point, $data = [])
    {
        $file = GLBL_PATH.'/'.GLBL_FOLDERS_APPLICATION.'/config/hooks.php';
        // Check if the application has defined hooks.
        if (!file_exists($file)) {
            return false;
        }

        // Include hook definitions.
        require_once $file;

        // If there are no hooks defined, return false
        if (empty(self::$hooks) || empty(self::$hooks[$point])) {
            return false;
        }

        // Get hooks registered to the given point.
        $hooks = self::$hooks[$point];

        // Check if there are hooks registered for this point.
        if ($hooks && count($hooks) > 0) {

            // Go through hooks.
            foreach ($hooks as $hook) {
                $hook_file = GLBL_PATH.'/'.GLBL_FOLDERS_APPLICATION.'/'.rtrim($hook[0], '/').'/'.$hook[1].'.php';

                if (file_exists($hook_file)) {
                    require_once $hook_file;

                    // Check whether the hooked class is available.
                    if (class_exists($hook[2])) {

                        // Check whether any data were passed from the point
                        // If so, override them as Parameters
                        if (!empty($data)) {
                            $hook[4] = [$data];
                        }

                        // Check whether the hooked object is already created.
                        // If not, create the object and store it for later use.
                        if (!isset(self::$loadedHooks[$hook[2]])) {

                            // If the method is not set but parameters, consider it as Class parameters.
                            if (empty($hook[3]) && !empty($hook[4])) {

                                // Create the hooked object with parameters.
                                self::$loadedHooks[$hook[2]] = new $hook[2]($hook[4]);

                                // Remove parameters.
                                $hook[4] = '';
                            } else {

                                // Create the hooked object.
                                self::$loadedHooks[$hook[2]] = new $hook[2]();
                            }
                        }

                        // Check if a method is also defined along with its existence.
                        if (!empty($hook[3]) && method_exists(self::$loadedHooks[$hook[2]], $hook[3])) {

                            // Call the relevant method with parameters if available.
                            BaseObject::callMethod(self::$loadedHooks[$hook[2]], $hook[3], $hook[4]);
                        }
                    }
                }
            }
        }
    }
}
