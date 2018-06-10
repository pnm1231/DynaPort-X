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
 * Controller Class.
 *
 * The main Controller class of the framework.
 *
 * @category    Core
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/Controllers
 */
class Controller
{
    /**
     * Auto-load controllers.
     *
     * @var array Array of controllers
     */
    public $controllers = [];

    /**
     * Auto-load models.
     *
     * @var array Array of models
     */
    public $models = [];

    /**
     * Auto-load libraries.
     *
     * @var array Array of libraries
     */
    public $libraries = [];

    /**
     * Loaded models.
     *
     * @var \class Model object(s)
     */
    public $model;

    /**
     * Loaded controllers.
     *
     * @var \class Controller object(s)
     */
    public $controller;

    /**
     * Loaded libraries.
     *
     * @var \class Library object(s)
     */
    public $library;

    /**
     * Component loader.
     *
     * @var \Loader
     */
    public $load;

    /**
     * View object.
     *
     * @var \View
     */
    public $view;

    /**
     * Preserved view object.
     *
     * @var \View
     */
    protected static $viewInstance;

    /**
     * Session object.
     *
     * @var \Session
     */
    public $session;

    /**
     * Stored variabled.
     *
     * @var array Variables
     */
    private static $vars = [];

    public function __construct()
    {

        // Assign the Loader object.
        $this->load = new Loader($this);

        // Load models that are requested to be auto-loaded.
        if ($this->models && is_array($this->models) && count($this->models) > 0) {
            foreach ($this->models as $model) {
                $this->load->model($model);
            }
        }

        // Load controllers that are requested to be auto-loaded.
        if ($this->controllers && is_array($this->controllers) && count($this->controllers) > 0) {
            foreach ($this->controllers as $controller) {
                $this->load->controller($controller);
            }
        }

        // Load libraries that are requested to be auto-loaded.
        if ($this->libraries && is_array($this->libraries) && count($this->libraries) > 0) {
            foreach ($this->libraries as $library) {
                $this->load->library($library);
            }
        }

        // Check if there is no preserved View instance.
        // If there isn't, create a new View object.
        // If there is one, use it as the View object.
        if (self::$viewInstance) {
            $this->view = self::$viewInstance;
        } else {
            $this->view = new View();
            self::$viewInstance = $this->view;
        }

        // Assign the Session object.
        $this->session = new Session();
    }

    /**
     * Load a component.
     *
     * @deprecated deprecated since version 2.0.38
     *
     * @param string $type      Component type (model,controller)
     * @param string $component Component path/name
     */
    public function load($type, $component)
    {
        $this->load->{$type}($component);
    }

    /**
     * Set a variable.
     *
     * These variables are accessible through any controller that is extending
     * the parent (this) controller.
     *
     * @param string $k Key
     * @param mixed  $v Value
     */
    public function setVar($k, $v)
    {
        self::$vars[$k] = $v;
    }

    /**
     * Return a variable.
     *
     * @param string $k Key
     *
     * @return mixed Value
     */
    public function getVar($k)
    {
        if (isset(self::$vars[$k])) {
            return self::$vars[$k];
        } else {
            return false;
        }
    }
}
