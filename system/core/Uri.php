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
 * URI Class.
 *
 * The URI class which handles URI-related tasks.
 *
 * @category    Core
 *
 * @author      Prasad Nayanajith
 *
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/uri
 */
class Uri
{
    /**
     * Exploded URI.
     *
     * @var array
     */
    public $uriExpl;

    /**
     * Module name.
     *
     * @var string
     */
    public $module;

    /**
     * Controller name.
     *
     * @var string
     */
    public $controller;

    /**
     * Method name.
     *
     * @var string
     */
    public $method;

    /**
     * Parameters.
     *
     * @var array
     */
    public $params;

    /**
     * Errors occurred.
     *
     * @var string
     */
    public $error;

    public function __construct()
    {

        // Check whether the URI is specified.
        // If so, use it as the URI.
        // Otherwise figure out the URI using the current URL.
        if (isset($_GET['uri']) && !empty($_GET['uri'])) {

            // Build the full URL using the URI
            $url = GLBL_URL.'/'.$_GET['uri'];

            // If it is not a valid URL, throw a 400 error.
            if (!Validate::this($url, 'url')) {
                $this->error = 400;

                return;
            }

            // Get the specified URI.
            $uri = $_GET['uri'];
        } else {

            // Get the current URL.
            $url = self::currentURL();

            // Parse the current URL.
            $urlParse = parse_url($url);

            // Parse the base URL.
            $glblParse = parse_url(GLBL_URL);

            // If the GLBL_URL does not have a path, indicate it as the base.
            if (!isset($glblParse['path'])) {
                $glblParse['path'] = '';
            }

            // Get the URI.
            $uri = rtrim(ltrim(str_replace($glblParse['path'], '', $urlParse['path']), '/'), '/');

            // If the URI is 'index.php', consider it as empty.
            if ($uri == 'index.php') {
                $uri = '';
            }
        }

        // Check whether Hooks are enabled.
        if (GLBL_ENABLE_HOOKS == true) {

            // Run hooks registered to pre-route.
            Hooks::run('dpx_pre_route');
        }

        // Check if there are routing methods declared.
        // If so, route the current URI.
        // The URI will be modified if there is a match.
        if (class_exists('Router', false)) {
            $uri = Router::route($uri);
        }

        // If the URI is not empty, break it into sections.
        if (!empty($uri)) {

            // Break the URI into sections.
            $uriExpl = explode('/', $uri);
            $this->uriExpl = $uriExpl;

            // Design the normal URI scheme.
            $uriScheme = ['module', 'controller', 'method'];

            // If the app is not modularized, remove 'module' from the flow.
            if (GLBL_MODULARIZED != true) {

                // Remove 'module' from the URI scheme.
                unset($uriScheme[0]);

                // Reset array keys of the URI scheme.
                $uriScheme = array_values($uriScheme);
            }

            // Go throug the URI scheme and register each section.
            foreach ($uriScheme as $k=>$v) {

                // Assign the current value to the relavant section.
                Registry::set('dpx_'.$v, $uriExpl[$k]);

                // Assign the same to the relavant public variable.
                $this->{$v} = $uriExpl[$k];

                // Remove it from the array.
                unset($uriExpl[$k]);
            }

            // Reset array keys of URI sections and
            // assign it to the 'params' public variable.
            $this->params = array_values($uriExpl);

            // Store parameters in the registry as well.
            Registry::set('dpx_params', $this->params);
        }
    }

    /**
     * Get the current URL.
     *
     * @return string Current URL
     */
    public static function currentURL()
    {
        return self::get_protocol().'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }

    /**
     * Get the current URI.
     *
     * @return string Current URI
     */
    public static function currentUri()
    {
        return rtrim(ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/'), '/');
    }

    /**
     * Get the request protocol.
     *
     * @return string http OR https
     */
    private static function get_protocol()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            return $_SERVER['HTTP_X_FORWARDED_PROTO'];
        } else {
            return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        }
    }
}
