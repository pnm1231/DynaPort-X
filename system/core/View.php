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
 * View Class.
 *
 * The loader class which helps load classes into the application.
 *
 * @category    Core
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/Views
 */
class View
{
    /**
     * Whether the pre-view hook is attached.
     *
     * @var bool
     */
    private static $_hookedPre = false;

    /**
     * Whether the post-view hook is attached.
     *
     * @var bool
     */
    private static $_hookedPost = false;

    /**
     * Data that should be passed to views.
     *
     * @var array
     */
    public $data = [];

    /**
     * HTML object.
     *
     * @var \class HTML
     */
    private $html;

    public function __construct()
    {
        $this->html = new HTML();
    }

    /**
     * Render a view.
     *
     * @param string $file   File (view) to render.
     * @param bool   $return Return the view instead of printing
     *
     * @return mixed
     */
    public function render($file, $return = false)
    {

        // Check whether Hooks are enabled.
        if (GLBL_ENABLE_HOOKS == true && !self::$_hookedPre) {
            self::$_hookedPre = true;

            // Run hooks registered to pre-view.
            Hooks::run('dpx_pre_view');
        }

        // If $return is true, start the output buffer
        if ($return) {
            ob_start();
        }

        $file = Loader::pathnameToFile('view', $file);

        // Check the availability of the view.
        // If available, print it. Otherwise, throw a 500 error.
        if (file_exists($file)) {

            // Check if there are data to be passed to the view
            // If so, extract them as variables
            if (isset($this->data) && is_array($this->data)) {
                extract($this->data);
            }

            // Make the HTML object accessible inside the view
            $html = $this->html;

            include $file;
        } else {
            new DPxError('Something unavailable was called.', 500, 'DPX.View.render: \''.$file.'\' is not available.');
        }

        // If the $return is true, get the output buffer content and clean it
        if ($return) {
            $content = ob_get_clean();
        }

        // Check whether Hooks are enabled.
        if (GLBL_ENABLE_HOOKS == true && !self::$_hookedPost) {
            self::$_hookedPost = true;

            // Run hooks registered to pre-view.
            Hooks::run('dpx_post_view');
        }

        // If the $return is true, return the content
        if ($return) {
            return $content;
        }
    }
}
