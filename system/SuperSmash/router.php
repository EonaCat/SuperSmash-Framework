<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\SuperSmash;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Router 
{
    // http protocol (https or http)
    protected $protocol;

    // hostname
    protected $hostName;
    
    // website URL
    protected $websiteURL;
    
    // requested URI
    protected $uri;
    
    // website directory
    protected $websiteDir;
    
    // controller name
    protected $controler;

    // action (sub page)
    protected $action;

    // querystring
    protected $queryString;
 
    // Create the contructor
    public function __construct() 
    {
        // Load the input class
        
        $this->input = loadClass('Input');

        // Start routing
        $this->checkRoutingUrl();
    }

    // This function will check how the url should be loaded
    protected function checkRoutingUrl()
    {
        // Determine our http hostname, and site directory
        $this->hostName = rtrim($_SERVER['HTTP_HOST'], '/');
        $this->websiteDir = dirname( $_SERVER['PHP_SELF'] );

        // Detect our protocol
        if(isset($_SERVER['HTTPS'])) 
        {
            if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') 
            {
                $this->protocol = 'https';
            } 
            else 
            {
                $this->protocol = 'http';
            }
        }
        else
        {
            $this->protocol = 'http';
        }
        
        // Build our Full Base URL
        $websiteURL = str_replace('//', '/', $this->hostName .'/'. $this->websiteDir);

        $this->websiteURL = $this->protocol .'://' . rtrim($websiteURL, '/');

        // Process the site URI
        if (!configuration('urlParameters', 'SuperSmash')) 
        {
            // Get our current url, which is passed on by the 'url' param
            $this->uri = (isset($_GET['url']) ? $this->input->get('url', true) : '');  
        }
        else 
        {
            // Define our needed vars
            $controllerParameter =  configuration('controllerParameter', 'SuperSmash');
            $actionParameter = configuration('actionParameter', 'SuperSmash');
            
            // Make sure we have a controller at least
            $controller = $this->input->get($controllerParameter, true);

            if (!$controller)
            {
                $this->uri = '';
            } 
            else 
            {
                // Get our action
                $action = $this->input->get($actionParameter, true);
                if(!$action) $action = configuration('defaultAction', 'SuperSmash'); // Default Action
                
                // initialise the uri
                $this->uri = $controller .'/'. $action;
                
                // Clean the query string
                $queryString = $this->input->clean($_SERVER['QUERY_STRING']);
                $queryString = explode('&', $queryString);
                foreach($queryString as $string)
                {
                    // Convert this segment to an array
                    $string = explode('=', $string);
                    
                    // Dont add the controller / action twice ;)
                    if($string[0] == $controllerParameter || $string[0] == $actionParameter)
                    {
                       continue;   
                    }
                    
                    // Append the uri vraiable
                    $this->uri .= '/'. $string[1];
                }
            }
        }

        // If the URI is empty, then load defaults
        if (empty($this->uri)) 
        {
            // Set our Controller / Action to the defaults
            $controller = configuration('defaultController', 'SuperSmash'); // Default Controller
            $action = configuration('defaultAction', 'SuperSmash'); // Default Action
            $queryString = array(); // Default query string
        }  
        // There is a URI, Lets load our controller and action
        else 
        {
            // Remove any left slashes or double slashes
            $this->uri = ltrim( str_replace('//', '/', $this->uri), '/');

            // We will start by bulding our controller, action, and querystring
            $urlArray = array();
            $urlArray = explode("/", $this->uri);
            $controller = $urlArray[0];

            // If there is an action, then lets set that in a variable
            array_shift($urlArray);
            if(isset($urlArray[0]) && !empty($urlArray[0])) 
            {
                $action = $urlArray[0];
                array_shift($urlArray);
            }
            
            // If there is no action, load the default action.
            else 
            {
                $action = configuration('defaultAction', 'SuperSmash'); // Default Action
            }
            
            // $queryString is what remains
            $queryString = $urlArray;
        }
        
        // Make sure the first character of the controller is not an _ !
        if( strncmp($controller, '_', 1) == 0 || strncmp($action, '_', 1) == 0 )
        {
            show_404();
        }
        
        // Set static Variables
        $this->controller = $controller;
        $this->action = $action;
        $this->queryString = $queryString;
    }

    // This function returns all the url information
    public function getUrlInformation()
    {
        $array = array(
            'protocol' => $this->protocol,
            'hostName' => $this->hostName,
            'websiteURL' => $this->websiteURL,
            'websiteDir' => $this->websiteDir,
            'uri' => $this->uri,
            'controller' => $this->controller,
            'action' => $this->action,
            'querystring' => $this->queryString
        );
        return $array;
    }
    
    // This function returns the specified URI segment    
    public function getUriSegment($index) {
        // Return the URI
        if(isset($this->uri[$index]))
        {
            return $this->uri[$index];
        }
        return false;
    }
}
?>