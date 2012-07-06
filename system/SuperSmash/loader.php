<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\SuperSmash;
use settings\settings;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Loader {

    // This function will call the specified model
    public function model($name, $additionalInstanceName = null) 
    {
        // Check for path. We need to get the model file name
        if(!strpos($name, '/')) 
        {
            $paths = explode('/', $name);
            $class = ucfirst(end($paths)) . "Model";
        } 
        else 
        {
            $class = ucfirst($name) . "Model";
            $name = strtolower($name);
        }
        
        // Include the model page
        require_once(settings::getFilePath() . DS . settings::getApp() . DS . 'models' . DS . $name .'.php');

        // Get our class into a variable
        $object = new $class();

        // Get the instance
        if($additionalInstanceName !== null) 
        {
            getInstance()->$additionalInstanceName = $object;
        } 
        else 
        {
            getInstance()->$class = $object;
        }
        return $object;
    }

    // This function will load the view file and display it
    public function view($viewName, $data, $displayView = false) 
    {
        // Make sure our data is in an array format
        if(!is_array($data)) 
        {
            showError('no_array', array('data', 'Loader::view'), E_WARNING);
            $data = array();
        }
        
        // Set the filePath for the view
        $filePath = settings::getFilePath() . DS . settings::getApp() . DS . 'views' . DS . $viewName . DS .  'index.php';

        // Set the viewPath for the view
        $viewPath = array('viewPath' => DS . 'views' . DS . $viewName);
        
        // Get the websiteURL for the view
        $websiteInformation = getUrlInformation();
        $websiteInformation = array('websiteURL' => $websiteInformation['websiteURL']);

        // Set the websitePath
        $websitePath = array('websitePath' => $websiteInformation['websiteURL'] . settings::getApplicationPath() . "/" . settings::getApp());

        // Get all the applications for the view
        $applications = settings::getApps();

        $data = array_merge($data,$viewPath, $websiteInformation, $websitePath, $applications);

        // extract variables
        extract($data);	 

        // Get our page contents
        if(file_exists($filePath))
        {
            ob_start();
            include($filePath);
            $page = ob_get_contents();
            $page = str_replace("<head>","<head>
    <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
    <meta name=\"generator\" content=\"SuperSmash Framework\" />"
            ,$page);
            ob_end_clean();
        
            // Replace some Global values
            $Benchmark = loadClass("Benchmark");
            $page = str_replace("{elapsed}", $Benchmark->elapsed('system', 4), $page);
            $page = str_replace("{usage}", $Benchmark->usage(), $page);
            $page = str_replace("</head>", "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"". $websiteURL . "/" . "system" . "/" . "SuperSmash" . "/" . "pages" . "/css/footer.css\"/>\n</head>", $page);
            preg_match('/<body[^>]*?[^>]*>/i', $page, $body);
            $page = str_replace("$body[0]", "$body[0]\n<div class=\"SuperSmashFrameworkWrapper\">", $page);            
            $page = str_replace("</body>", "</div>\n</body>", $page);            
            $page = str_replace("</div>\n</body>", "\t\t<br />\n\t\t<div class=\"SuperSmashFramework\"><p>Running on SuperSmash Framework &#169; " . date("Y") . ", <a target=\"_blank\" href=\"http://www.SuperSmash.nl\">SuperSmash</a></p></div>\n\t</div>\n\t</body>", $page);            

            // Spit out the page
            if(!$displayView)
            {
              echo $page;  
            } 
            return $page;
        }
        else 
        {
            showError('view', array($viewName), E_ERROR);
            return false;
        }
    }

// This function will be used to call in a class from either the APP library, or the system library folders
    public function library($name, $instance = true) 
    {
        // Make sure periods are replaced with slahes if there is any
        if(strpos($name, ".")) 
        {
            $name = str_replace('.', '\\', $name);
        }
        
        // Load the Class
        $class = loadClass($name, 'Library');
        
        // Do we instance this class?
        if($instance) 
        {
            // Allow for custom class naming
            (!$instance) ? $name = $instance : '';
            
            // Instance
            $FB = getInstance();
            if($FB) 
            {
                (!isset($FB->$name)) ? $FB->$name = $class : '';
            }
        }
        return $class;
    }

    // This function will be used to setup a database connection
    public function database($arguments, $instance = true)
    {
        // Load our connection settings. We can allow custom connection arguments
        if(!is_array($arguments)) 
        {
            // Check our registry to see if we already loaded this connection
            $object = \Registry::singleton()->load("database".$arguments);
            if($object != null) 
            {
                // Skip to the instancing part unless we set instance to false
                if($instance) 
                {
                    goto Instance;   
                }
                return $object;
            }
        
            // Get the DB connection information
            $info = configuration($arguments, 'database');
            if($info === null) 
            {
                showError('db_key_not_found', array($arguments), E_ERROR);
            }
        } 
       
        // Not in the registry, so establish a new connection
        $dispatch = $first ."Database\\Driver";
        $object = new $dispatch($info);
        
        // Store the connection in the registry
        \Registry::singleton()->store("DBC_".$arguments, $object);		
        
        // Here is our instance goto
        Instance: 
        {
            // If user wants to instance this, then we do that
            if($instance && !is_numeric($arguments)) 
            {
                if($instance) $instance = $arguments;

                // Easy way to instance the connection is like this
                $FB = getInstance();
                if($FB) 
                {
                    (!isset($FB->$instance)) ? $FB->$instance = $object : '';
                }
            }
        }
        return $object;
    }

    // This function is used to load in a helper file from either the application/helpers, or the SuperSmash/helpers folders
    public function helper($name) 
    {
        // Check the application/helpers folder
        if(file_exists(settings::getFilePath() . DS .  settings::getApp() . DS . 'helpers' . DS . $name . '.php')) 
        {
            require_once(settings::getFilePath() . DS .  settings::getApp() . DS . 'helpers' . DS . $name . '.php');
        }        
        // Check the core/helpers folder
        else
        {
            require_once(SYSTEM . DS .  'helpers' . DS . $name . '.php');
        }
    }
}
?>