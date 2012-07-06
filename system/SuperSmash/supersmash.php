<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\SuperSmash;
use settings\settings;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class SuperSmash 
{
    protected $Router;
    protected $dispatch;
    public static $database;

    // This function will start the SuperSmash Framework
    public function start() 
    {
        // initialise the router
        $this->Router = loadClass('Router');
        
        // get the URL information to be used by the router
        $routes = $this->Router->getUrlInformation();
        
        // initialise some important routing variables
        $controller   = $GLOBALS['controller']   = $routes['controller'];
        $action       = $GLOBALS['action']       = $routes['action'];
        $queryString  = $GLOBALS['querystring']  = $routes['querystring'];

        // Include the application controller
        if(file_exists(settings::getFilePath() . DS . settings::getApp() . DS . 'controllers' . DS . strtolower($controller) . '.php'))
        {
            require_once (settings::getFilePath() . DS . settings::getApp() . DS . 'controllers' . DS . strtolower($controller) . '.php');
        }
        else 
        {
            show_404();
        }

        // Create a new object for the controller
        $this->dispatch = new $controller();
    
        // Create a database connection object
        if (configuration('useDatabase'))
        {
            if (self::$database == null)
            {
                self::$database = loadClass('Database','SuperSmash',configuration('database'));
                self::$database = self::$database->open();
            }
       }

        // Check if we need to put the session in the database
        if (configuration('sessionDatabase')) 
        {
            $config['database'] = self::$database;
            new Session($config);
        }

        // After loading the controller, make sure the method exists, or we have a 404
        if(method_exists($controller, $action)) 
        {
            // Call the beforeAction method in the controller.
            $this->performAction($controller, "_beforeAction", $queryString);
            
            // Call the actual action
            $this->performAction($controller, $action, $queryString);
            
            // Call the afterAction method in the controller.
            $this->performAction($controller, "_afterAction", $queryString);
        } 
        else 
        {
            // If the method did not exist, then we have a 404
            show_404();
        }
    }

    // This function will perform an action on the specified controller
    protected function performAction($controller, $action, $queryString = null) 
    {
        if(method_exists($controller, $action))
        {
            return call_user_func_array( array($this->dispatch, $action), $queryString );
        }
        return false;
    }

    public static function database()
    {
        return self::$database;
    }

    public static function language()
    {
        // Load the language
        $language = loadClass('Language');
        $language->setLanguage(configuration('language', 'SuperSmash'));
        $language->load('SuperSmash_errors');
        $language->load('page_errors');
        return $language;
    }
}
?>