<?php
    
/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/
use settings\settings;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

    // This function will autoload the classes that are not yet included/loaded in the SuperSmash Framework
    function __autoload($className) {
        // We will need to lowercase everything
        $parts = explode('\\', strtolower($className));

        // We need to remove the first part of the array (if the value is empty)  
        // (This can happen because you came from the root namespace)
        if (empty($parts[0])){ 
            $parts = array_shift($parts);
        };

        // Build the filePath
        $classPath = implode(DS, $parts);

        // We need to assign our filePath as the root (so php looks there when looking for files)
        $file = ROOT . DS . $classPath .'.php';

        // If the file exists, then include it, and return
        if (!file_exists($file)){

            // Failed to load the class we where looking for.
            showError('autoLoad', array( addslashes($className) ), E_ERROR);

        }
        require_once($file);
    }

    // This function will handle all the errors that where given by PHP (this will be the default error handler)
    function errorHandler($errorNumber, $errorMessage, $errorFile, $errorLine) {
        if(!$errorNumber) {
            return;
        }
        
        // Get the debug instance
        $debug = loadClass('Debug');	
        
        // Trigger the error
        $debug->triggerError($errorNumber, $errorMessage, $errorFile, $errorLine, debug_backtrace());

        // Don't execute PHP internal error handler
        return true;
    }

    // This function will show the errorMessage
    function showError($errorMessage = 'none', $arguments = null, $level = E_ERROR) {
        // Let get a backtrace for deep debugging
        $backtrace = debug_backtrace();
        $calling = $backtrace[0];
        
        // Load the language
        $language = loadClass('Language');
        $language->setLanguage(configuration('language', 'SuperSmash'));
        $language->load('errors');
        $message = $language->get($errorMessage);
        
        // Allow custom messages
        if(!$message) {
            $message = $errorMessage;
        }
        
        // check if there are any arguments
        if(is_array($arguments)) {
            // Add the arguments to the message
            $message = vsprintf($message, $arguments);
        }
        
        // Get the debug instance
        $debug = loadClass('Debug');

        // Trigger the error
        $debug->triggerError($level, $message, $calling['file'], $calling['line'], $backtrace);
    }

    // This function will show an 404 error page
    function show_404() {
        // Get the debug instance
        $debug = loadClass('Debug');

        // Show the error
        $debug->showError(404);        
    }

    // This function will log a message to a specified filename log
    function logMessage($message, $filename = DEBUG) {		
        // Get the debug instance
        $debug = loadClass('Debug');

        // Log the error
        $debug->log($message, $filename);
    }

    // This function returns an item of the configuration file
    function configuration($item, $type = 'SuperSmash') {   
        // Get the config instance
        $configuration = loadClass('Configuration');

        // Return the specific item
        return $configuration->get($item, $type);
    }

    // This function will set an item in the configuration file
    function configurationSet($item, $value, $name = 'SuperSmash') {
        // Get the config instance
        $configuration = loadClass('Configuration');	

         // Set the specific configuration item in the configuration file
        $configuration->set($item, $value, $name);
    }

    // This function will save a configuration to the configuration.php file
    function configurationSave($name) {
        // Get the config instance
        $configuration = loadClass('Configuration');	
        
        // Save the configuration to the configuration.php file
        return $configuration->save($name);
    }

    // This function will load the specific configuration in the configuration.php
    function configurationLoad($file, $name, $array = false) {	
        $configuration = loadClass('Configuration');
        $configuration->load($file, $name, $array);
    }

    // This function will get an instance of the controller
    function getInstance() {
        if (class_exists('Application\\SuperSmash\\Controller', false)) {
            return Application\SuperSmash\Controller::getInstance();
        } elseif (class_exists('System\\SuperSmash\\Controller', false)) {
            return System\SuperSmash\Controller::getInstance();
        } else {
            return false;
        }
    }
    
    // This function will return the website URL and the URL information
    function getUrlInformation() {
        return loadClass('Router')->getUrlInformation();
    }

    // This function will load a specific className
    function loadClass($className, $type = 'SuperSmash', $parameters = array()) {
        // We need to create a className path for the correct class
        if(strpos($className, '\\') === false) {
            $className = $type .'\\'. $className;
        }

        // We will need to lowercase everything
        $class = strtolower($className);
        
        // Create a storageName for the class
        $store_name = str_replace('\\', '_', $class);

        // Check if the class exists in the registry
        $loaded = \System\SuperSmash\Registry::singleton()->load($store_name);
        if($loaded !== null) {
            return $loaded;
        }

        // The class was not found in the registry so we need to look for the classFile ourself

        // Split the class path in parts
        $parts = explode('\\', $class);

        // Build our filepath
        $file = str_replace('\\', DS, implode('\\', $parts));

        // If we dont have the full path, we need to create it
        if($parts[0] !== 'system' && $parts[0] !== 'application') {
            // Check for needed classes in the Application library folder
            if(file_exists(settings::getFilePath() . DS . settings::getApp() . DS . $file . '.php')) {
                $file = settings::getFilePath() . DS . settings::getApp() . DS . $file .'.php';
                $className = '\Application\\'. $className;
            } else {
                $file = SYSTEM . DS . $file .'.php';
                $className = '\System\\'. $className;
            }
        } else {
            $file = ROOT . DS . $file .'.php';
        }

        // Require our classFile.
        require($file);

        // Check if the class needs parameters
        if (!empty($parameters)){
            try{
                if (strlen(strstr($className,"\System\SuperSmash\\"))>0) $className = str_replace("\System\SuperSmash\\", "", $className);
                $newClass = new ReflectionClass($className);
                $newClass = $newClass->newInstanceArgs($parameters);
                } catch (Exception $exception){
                    die("The class $className could not be loaded >>> <br/><br/> $exception");
                }
       
        } else {
            // Create an object of the new class
            $newClass = new $className();            
        }

        // Store this new object in the registry
        \System\SuperSmash\Registry::singleton()->store($store_name, $newClass); 

        // return the new class.
        return $newClass;
    }

    // This function will redirect you to a specified URL after a specified waiting time
    function redirect($url, $wait = 0) {
        // Check if the URL is valid. If not then add our current websiteURL to it.
        if(!preg_match('@^(mailto|ftp|http(s)?)://@i', $url)) {
            $websiteURL = getUrlInformation();
            $url = $websiteURL['websiteURL'] .'/'. $url;
        }

        // Check if we need to wait a few seconds before we can redirect the user
        if($wait >= 1) {
            header("Refresh:". $wait .";url=". $url);
        } else {
            header("Location: ".$url);
            die();
        }
    }
?>