<?php
/**
 * Class Fizzy_FrontController
 * @package Fizzy
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.voidwalkers.nl/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@voidwalkers.nl so we can send you a copy immediately.
 * 
 * @copyright Copyright (c) 2009 Voidwalkers (http://www.voidwalkers.nl)
 * @license http://opensource.org/licenses/mit-license.php The MIT License
 */

/** Fizzy_Request */
require_once 'Fizzy/Request.php';
/** Fizzy_Router */
require_once 'Fizzy/Router.php';

/**
 * FrontController class for Fizzy. Dispatches the request to the correct 
 * Controller.
 *
 * @author Mattijs Hoitink <mattijs@voidwalkers.nl>
 */
class Fizzy_FrontController
{
    /**
     * The application configuration.
     * @var Fizzy_Config
     */
    protected $_config = null;
    
    /**
     * The request object.
     * @var Fizzy_Request
     */
    protected $_request = null;
    
    /**
     * The router object.
     * @var Fizzy_Router
     */
    protected $_router = null;
    
    /** **/

    /**
     * Default constructor.
     * @param Fizzy_Config $config
     */
    public function __construct(Fizzy_Config $config = null)
    {
        $this->_config = $config;
    }
    
    /**
     * Sets the application configuration.
     * @param Fizzy_Config $config 
     */
    public function setConfig(Fizzy_Config $config)
    {
        $this->_config = $config;
    }

    /**
     * Sets the request object.
     * @param Fizzy_Request $request
     */
    public function setRequest(Fizzy_Request $request)
    {
        $this->_request = $request;
    }
    
    /**
     * Sets the router object.
     * @param Fizzy_Router $router
     */
    public function setRouter(Fizzy_Router $router)
    {
        $this->_router = $router;
    }

    /**
     * Dispatches the request to a controller.
     * @param Fizzy_Request $request
     */
    public function dispatch()
    {
        if(null === $this->_request) { $this->_request = new Fizzy_Request(); }
        $request = $this->_request;

        if(null === $this->_router) { $this->_router = new Fizzy_Router($this->_config->getConfiguration('routes')); }
        $router = $this->_router;

        // Find a route and inject the route parameters into the request object
        $router->route($request);
        
        // Get the controller and action
        $controller = $request->getController();

        // Check if controller exists
        $controllerClass = ucfirst($controller) . 'Controller';
        

        $controllerFileName = $controllerClass . '.php';
        $controllerFilePath = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $controllerFileName;

        if(!is_file($controllerFilePath)) {
            require_once 'Fizzy/Exception.php';
            throw new Fizzy_Exception("Controller file for controller {$controllerClass} not found.");
        }
        require_once $controllerFilePath;
        
        if(!class_exists($controllerClass)) {
            require_once 'Fizzy/Exception.php';
            throw new Fizzy_Exception("Controller class {$controllerClass} not found.");
        }

        $reflectionClass = new ReflectionClass($controllerClass);
        $controllerInstance = $reflectionClass->newInstance($request);

        // retrieve the action
        $action = $request->getAction();
        $actionMethod = $action . 'Action';
        
        if(!$reflectionClass->hasMethod($actionMethod)) {
            require_once 'Fizzy/Exception.php';
            throw new Fizzy_Exception("Action method {$actionMethod} in Controller {$controllerClass} not found.");
        }

        $controllerInstance->$actionMethod();
    }
    
}
