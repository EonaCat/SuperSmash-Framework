<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace System\SuperSmash;
use settings\settings;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Debug {
    // This variable will hold the instance of the class
    private static $instance;

    // This variable will hold the errorMessage
    protected $errorMessage;

    // This variable will hold the file that contains the error
    protected $errorFile;

    // This variable will hold the line the error is on
    protected $errorLine;

    // This variable will hold the level of the error
    protected $errorLevel;

    // This variable will hold the backTrace of the error
    protected $errorTrace;
    
    // This variable will hold check if we need to log the error
    protected $logErrors;
    
    // This variable will hold the error development level
    protected $development;

    // This variable will hold the original settings
    protected $originalSettings;
    
    // This variable will hold the the url Information
    protected $urlInformation;
    
    // This variable will hold the current language
    protected $language;

    // Create the constructor
    public function __construct() {
        
        // Set the error reporting
        $this->logErrors    = configuration('logErrors', 'SuperSmash');
        $this->development  = configuration('development', 'SuperSmash');
        
        // Save our original settings incase we change them midscript
        $this->originalSettings['logErrors']    = $this->logErrors;
        $this->originalSettings['development']  = $this->development;
        
        // Get our URL info
        $this->urlInformation = getUrlInformation();
    }

    // This function will trigger the error
    public function triggerError($errorNumber, $message = '', $file = '', $line = 0, $backtrace = null) {
        
        // Language setup
        $this->language = strtolower(configuration('language', 'SuperSmash'));
        
        // fill in the attributes
        $this->errorMessage = $message;
        $this->errorFile    = $file;
        $this->errorLine    = $line;
        $this->errorTrace   = $backtrace;

        // Get the error Level
        switch($errorNumber) {
            case E_USER_ERROR:
                $this->errorLevel = 'Error';
                $severity = 2;
                break;

            case E_USER_WARNING:
                $this->errorLevel = 'Warning';
                $severity = 1;
                break;
                
            case E_USER_NOTICE:
                $this->errorLevel = 'Notice';
                $severity = 1;
                break;
            
            case E_ERROR:
                $this->errorLevel = 'Error';
                $severity = 2;
                break;
                
            case E_WARNING:
                $this->errorLevel = 'Warning';
                $severity = 1;
                break;
                
            case E_NOTICE:
                $this->errorLevel = 'Notice';
                $severity = 1;
                break;
                
            case E_STRICT:
                $this->errorLevel = 'Strict';
                $severity = 1;
                break;

            default:
                $this->errorLevel = 'Error Code: '.$errorNumber;
                $severity = 2;
                break;
        }
        
        // Check if the error Logging is enabled
        if ($this->logErrors) {
            $this->logError();
        }
        
        // Check if the error is important or the development environment is active
        if($this->development || $severity == 2) {
            // create the error page
            $this->createErrorPage();
        }
    }
    
    // This function will show a specific error page
    public function showError($type) {
        if (ob_get_level() != 0){
          ob_end_clean();  
        } 
        
        // Get the language
        $language = strtolower (configuration('language', 'SuperSmash'));

        // Get the site url
        $websiteURL = $this->urlInformation['websiteURL'];
        
        // See if there is a custom page in the app folder
        if(file_exists(settings::getFilePath() . DS . settings::getApp() . 'pages' . DS . $this->language . DS . $language . DS . $type .'.php')) {
            ob_start();
            require_once(settings::getFilePath() . DS . settings::getApp() . 'pages' . DS . $this->language . DS . $language . DS . $type .'.php');
            $page = ob_get_contents();
            $page = str_replace("<head>","<head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
        <meta name=\"generator\" content=\"SuperSmash Framework\" />"
            ,$page);
            // Replace some footer values
            $Benchmark = loadClass('Benchmark');
            $page = str_replace('{elapsed}', $Benchmark->elapsed('system', 4), $page);
            $page = str_replace('{usage}', $Benchmark->usage(), $page);                       
            ob_end_clean();
            die($page);
        } else {
            ob_start();
            require_once(SYSTEM . DS . "SuperSmash" . DS . 'pages' . DS . $this->language . $language . DS . $type .'.php');
            $page = ob_get_contents();
            $page = str_replace("<head>","<head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
        <meta name=\"generator\" content=\"SuperSmash Framework\" />"
            ,$page);
            // Replace some footer values
            $Benchmark = loadClass('Benchmark');
            $page = str_replace('{elapsed}', $Benchmark->elapsed('system', 4), $page);
            $page = str_replace('{usage}', $Benchmark->usage(), $page);            
            ob_end_clean();
            die($page);
        }
    }

    // This function will log the error to the log file
    protected function logError() {
        
        // Get the site url
        $url = $this->urlInformation;
        
        // Create the log message
        $err_message =  "| Logging started at: ". date('Y-m-d H:i:s') ."\n";
        $err_message .= "| Error Level: ".$this->errorLevel ."\n";
        $err_message .= "| Message: ".$this->errorMessage ."\n"; 
        $err_message .= "| Reporting File: ".$this->errorFile."\n";
        $err_message .= "| Error Line: ".$this->errorLine."\n";
        $err_message .= "| URL When Error Occured: ". $url['websiteURL'] ."/". $url['uri'] ."\n\n";
        $err_message .= "--------------------------------------------------------------------\n\n";

        // Write to the log file
        $log = @fopen(SYSTEM . DS . 'editable' . DS . 'logs' . DS . ERROR, 'a');
        @fwrite($log, $err_message);
        @fclose($log);
    }
    
    // This function will log the message to the debugging log
    public function log($message, $filename = DEBUG) {
        
        // Create the log message
        $logMessage = "(".date('Y-m-d H:i:s') .") ".$message ."\n"; 

        // Write to the log file
        if (file_exists(settings::getFilePath() . DS . settings::getApp() . DS . "logs")){
            $log = @fopen(settings::getFilePath() . DS . settings::getApp() . DS . "logs" . DS . $filename, 'a');
        } else {
            $log = @fopen(SYSTEM . DS . 'editable' . DS . 'logs' . DS . $filename, 'a');
        }
        @fwrite($log, $logMessage);
        @fclose($log);
    }
    
    // This function will enable or disable errorReporting
    public function errorReporting($report = true) {
        if($report) {            
            // Set the error reporting back to the original state
            $this->logErrors = $this->originalSettings['logErrors'];
            $this->development = $this->originalSettings['development'];
        } else {
            // Use the custom error reporting
            $this->logErrors = 0;
            $this->development = 0;
        }
        return true;
    }
    
    // This function will build the error page
    protected function createErrorPage() {
        
        if (ob_get_level() != 0){
          ob_end_clean();  
        } 
        
        // Get the site url
        $websiteURL = $this->urlInformation['websiteURL'];
        
        // Get the correct error message
        ob_start();
            if(!$this->development) {
                include(SYSTEM . DS . "SuperSmash" . DS . 'pages' . DS . $this->language . DS . 'error.php');
            } else {
                include(SYSTEM . DS . "SuperSmash" . DS . 'pages' . DS . $this->language . DS . 'debug_error.php');
            }
            $page = ob_get_contents();

            // Replace some footer values
            $Benchmark = loadClass('Benchmark');
            $page = str_replace('{elapsed}', $Benchmark->elapsed('system', 4), $page);
            $page = str_replace('{usage}', $Benchmark->usage(), $page);
               
        ob_end_clean();
        
        // If we are debugging, build the debug block
        if($this->development) {
            // Create the regex, and search for it
            $regex = "{DEBUG}(.*){/DEBUG}";
            while(preg_match("~". $regex ."~iUs", $page, $match)) {
                $blocks = '';
                
                // We dont need the first trace because its in the error message
                unset($this->errorTrace[0]);
                $i = 1;
                
                // Make sure we have at least 1 backtrace!
                if(count($this->errorTrace) > 0) {
                    // Loop through each level and add it to the $blocks var.
                    foreach($this->errorTrace as $key => $value) {
                        $block = $match[1];
                        $block = str_replace('{#}', $key++, $block);
                        
                        // Loop though each variable in the Trace level
                        foreach($value as $key => $value) {
                            
                            // Upper case the key
                            $key = strtoupper($key);
                            
                            // If $v is an object, then go to next loop
                            if(is_object($value)) {
                              continue;   
                            }
                            
                            // If $v is an array, we need to dump it
                            if(is_array($value)) {
                                $value = "<pre>" . $this->var_dump($value, $key) . "</pre>";
                            }

                            $block = str_replace("{".$key."}", $value, $block);
                        }
                        
                        // Add to blocks
                        $blocks .= $block;
                        
                        // We only want to do this no more then 3 times
                        if($i == 2) {
                           break; 
                        }
                        $i++;
                    }
                }
                
                // Finally replace the whole thing with $blocks
                $page = str_replace($match[0], $blocks, $page);
            }
        }
        
        // add the error information to the page
        $page = str_replace("{ERROR_COPYRIGHT}", "SuperSmash Framework &#169;" . date("Y") . " <a href=\"http://www.SuperSmash.nl\">SuperSmash</a>", $page);
        $page = str_replace("{ERROR_LEVEL}", $this->errorLevel, $page);
        $page = str_replace("{MESSAGE}", $this->errorMessage, $page);
        $page = str_replace("{FILE}", $this->errorFile, $page);
        $page = str_replace("{LINE}", $this->errorLine, $page);

        die($page);
    }

    // This function will create a var dump in a nice way
    protected function var_dump($variable, $var_name = null, $indent = null) {	
        // Init our empty html return
        $html = '';
        
        // Create our indent style
        $tab_line = "<span style='color:#eeeeee;'>|</span> &nbsp;&nbsp;&nbsp;&nbsp ";


        // Grab our variable type and get our text color
        $type = ucfirst(gettype($variable));
        
        switch($type) {
            case "Array":
                // Count our number of keys in the array
                $count = count($variable);
                $html .= "$indent" . ($var_name ? "$var_name => ":"") . "<span style='color:#a2a2a2'>$type ($count)</span><br />$indent(<br />";
                $keys = array_keys($variable);
                
                // Foreach array key, we need to get the value.
                foreach($keys as $name) {
                    $value = $variable[$name];
                    $html .= $this->var_dump($value, "['$name']", $indent.$tab_line);
                }
                $html .= "$indent)<br />";
                break;
                
            case "String":
                $type_color = "<span style='color:green'>";
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($variable).")</span> $type_color\"$variable\"</span><br />";
                break;
                
            case "Integer":
                $type_color = "<span style='color:red'>";
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($variable).")</span> $type_color$variable</span><br />";
                break;
                
            case "Double":
                $type_color = "<span style='color:red'>"; 
                $type = "Float";
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($variable).")</span> $type_color$variable</span><br />";
                break;
                
            case "Boolean":
                $type_color = "<span style='color:blue'>";
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($variable).")</span> $type_color".($variable == 1 ? "true":"false")."</span><br />";
                break;
                
            case "null":
                $type_color = "<span style='color:black'>";
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($variable).")</span> ".$type_color."null</span><br />";
                break;
                
            case "Object":
                $type_color = "<span style='color:black'>";
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type</span><br />";
                break;
                
            case "Resource":
                $type_color = "<span style='color:black'>";
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type</span> ".$type_color."Resource</span><br />";
                break;
                
            default:
                $html .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".@strlen($variable).")</span> $variable<br />";
                break;
        }

        return $html;
    }
}
?>