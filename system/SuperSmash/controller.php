<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\SuperSmash;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Controller 
{
    // This variable will hold the controllerName
    public $controller;

    // This variable will hold the action taken
    public $action;
    
    // This variable will hold the queryString
    public $queryString;

    // This variable will hold the queryString
    public static $database;

    // This variable will hold the instance of the class
    private static $instance;

    // Create the constructor
    public function __construct() 
    {		
        // Set the instance of the controller
        self::$instance = $this;
    
        // Set our controller, action and queryString
        $this->controller   = $GLOBALS['controller'];
        $this->action       = $GLOBALS['action'];
        $this->queryString  = $GLOBALS['querystring'];
        
        // Initiate the loader
        $this->load = loadClass('Loader');
        
        // Initiate the autoloader Helpers
        $helpers = configuration('helpers', 'SuperSmash');
        if(count($helpers) > 0) 
        {
            foreach($helpers as $helper) 
            {
                $this->load->helpers($helper);
            }
        }
        
        // Intiate the autoloader Libraries
        $libraries = configuration('libraries', 'SuperSmash');
        if(count($libraries) > 0) 
        {
            foreach($libraries as $library) 
            {
                $this->load->libraries($library);
            }
        }
    }

    // This function will get the controller instance
    public static function getInstance() 
    {
        return self::$instance;
    }

    // This function will be called before an action is taken
    public function _beforeAction()
    {
        // We can write some custom code here that will be taken before an action is made
    }
    
    // This function will be called after an action is taken
    public function _afterAction() 
    {
        // We can write some custom code here that will be taken after an action is made
    }

}
?>