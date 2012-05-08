<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace System\SuperSmash;
use settings\settings;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Language {
    // This variable will contain an array of supported languages
    protected $supportedLanguages = array();

    // This variable will contain an array of loaded language files
    protected $loadedLanguages = array();
    
    // This variable will contain an array of supported system and application languages
    protected $languages = array();
    
    // This variable will hold our language
    public $language;

    // Create the constructor
    public function __construct() {
        // Scan the languages folder
        $this->scanLanguageFolders();

        // Set the default Language
        $this->language = configuration('language', 'SuperSmash');
    }

    // This function will set the specified language
    public function setLanguage($language) {
        // Check if the language exists
        $language = strtolower($language);
        if( in_array($language, $this->languages['application']) || in_array($language, $this->languages['system']) ) {
            $this->language = $language;
            return true;
        }
        return false;
    }

    // This function will load the specified language
    public function load($file, $language = null) {
        // Set the language if specified
        if($language != null) {
            $this->setLanguage($language);
        }
        
        // Add the extension, and create our tag
        $language = $this->language;
        $key = $file .'_'. $language;
        $file = $file . '.php';

        // Make sure we havent loaded this already
        if(isset($this->supportedLanguages[$key])) {
            return $this->supportedLanguages[$key];
        }
        
        // Init our empty variable arrays
        $vars = array();
        $vars2 = array();

        // Load the core language file if it exists
        if(file_exists(SYSTEM . DS . 'editable' . 'languages' . DS . $language . DS . $file)) {
            $vars = include(SYSTEM . DS . 'editable' . 'languages' . DS . $language . DS . $file);
            if(!is_array($vars)){
                return false;
            }
        }

        // Next we load the application file, allows overriding of the core one
        if(file_exists(settings::getFilePath() . DS . settings::getApp() . DS . 'languages' . DS . $language . DS . $file)) {
            $vars2 = include(settings::getFilePath() . DS . settings::getApp() . DS . 'languages' . DS . $language . DS . $file);
            if(!is_array($vars2)){
              return false;  
            } 
        }
        
        // Merge if both the app and core had the same filename
        $vars = array_merge($vars, $vars2);

        // Without a return, we need to store what we have here.
        $this->loadedLanguages[] = $file;
        $this->supportedLanguages[$key] = $vars;

        // Init the return
        return (!empty($vars)) ? $vars : false;
    }

    // This function gets the specified variable of the configuration array
    public function get($variable, $file = null)
    {
        // Check to see that we loaded something first
        if(empty($this->supportedLanguages)){
          return false;  
        } 
        
        // Determine our language variable filename if not givin
        if($file == null) {
            $file = end($this->loadedLanguages);   
        }
        
        // Build out lang var key
        $key = $file .'_'. $this->language;
        
        // check to see if our var is set... if not, try to load it first
        if(!isset($this->supportedLanguages[$key])){
          $this->load($file);  
        } 
        
        // Attempt to load the actual language var now
        if(isset($this->supportedLanguages[$key][$variable])) {
            return $this->supportedLanguages[$key][$variable];
        }
        return false;
    }

    // This function will return an array of all the languages that where found in the language folder
    public function getLanguages($type = null) {
        if($type == 'system') {
            return $this->languages['system'];
        } elseif($type == 'application') {
            return $this->languages['application'];
        }
        return $this->languages;
    }

    // This function will scan and find all the installed languages
    protected function scanLanguageFolders() {
        // Load the system languages first
        $path = SYSTEM . DS . 'editable' . DS . 'languages';
        $list = opendir( $path );
        while($file = readdir($list)) {
            if($file[0] != "." && is_dir($path . DS . $file)) {
                $this->languages['system'][] = $file;
            }
        }
        closedir($list);
        
        // Finally, Load app languages
        $path = settings::getFilePath() . DS . settings::getApp() . DS . 'languages';
        $list = opendir( $path );
        while($file = readdir($list)) {
            if($file[0] != "." && is_dir($path . DS . $file)) {
                $this->languages['application'][] = $file;
            }
        }
        closedir($list);
    }
}
?>