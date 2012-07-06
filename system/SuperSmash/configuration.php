<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\SuperSmash;
use settings\settings;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Configuration {

    // This array will hold all the settings
    protected $data = array();

    // Create the constructor
    public function __construct() 
    {
        // Load the default configuration file
         $this->load(settings::getFilePath() . DS . settings::getApp() . DS . 'configuration' . DS . 'configuration.php', 'SuperSmash');  
    }

    // This function will get the specified variable from the configuration file
    public function get($key, $type = 'SuperSmash')
    {
        // Check if the variable exists
        if(isset($this->data[$type][$key])) 
        {
            return $this->data[$type][$key];
        }
        return null;
    }
    
    // This function will return all the variables that where set in the data array
    public function getAll($type = 'SuperSmash') 
    {       
        // Check if the variable exists
        if(isset($this->data[$type])) 
        {
            return $this->data[$type];
        }
        return null;
    }

    // This function will set a variable in the data array    
    public function set($key, $value = false, $name = 'SuperSmash') 
    {       
        // If we have array, loop through and set each
        if(is_array($item)) 
        {
            foreach($item as $key => $value) 
            {
                $this->data[$name][$key] = $value;
            }
        } 
        else 
        {
            $this->data[$name][$item] = $value;
        }
    }

    // This function  will load a specific configuration file and will add its defined variables to the array
    public function load($file, $name, $array = false) 
    {
        // Include file and add it to the $files array
        if(!file_exists($file))
        {
          return;  
        } 

        require_once ($file);
        $this->files[$name]['filePath'] = $file;
        $this->files[$name]['config_key'] = $array;
                       
        if($array)
        {
          $variables = $variables[$array];  
        } 
        else 
        {
            $variables = get_defined_vars();    
        }

        // Unset the passed variables
        unset($variables['file'], $variables['name'], $variables['array']);
        
        // Add the variables to the $data[$name] array
        if(count($variables) > 0) 
        {
            foreach($variables as $key => $value) 
            {
                if($key != 'this' && $key != 'data') 
                {
                    $this->data[$name][$key] = $value;
                }
            }
        }
        return;
    }

    // This function will save all config variables to the config file, 
    // and makes a backup of the current config file
    public function save($name) 
    {
        // Convert everything to lowercase
        $name = strtolower($name);
        
        // Check to see if we need to put this in an array
        $configKey = $this->files[$name]['config_key'];
        
        if($configKey != false) 
        {
            $Old_Data = $this->data[$name];
            $this->data[$name] = array("$configKey" => $this->data[$name]);
        }

        // Create the new configuration file
        $configurationContent  = "<?php\n\n";

        $configurationContent .= "

        /**************************************/
        /****     SuperSmash Framework     ****/
        /****     Created By SuperSmash    ****/
        /****     Started on: 25-04-2012   ****/
        /**************************************/
        /**** This file has been generated ****/
        /***** by the SuperSmash Framework ****/
        /**************************************/
        \n\n
        ";

        // Loop through each var and write it
        foreach($this->data[$name] as $key => $value) 
        {
            if(is_numeric($value)) 
            {
                $configurationContent .= "\$$key = " . $value . ";\n";
            } 
            elseif(is_array($value)) 
            {
                $val = var_export($value, true);
                $configurationContent .= "\$$key = " . $value . ";\n";
            } 
            else 
            {
                $configurationContent .= "\$$key = '" . addslashes( $value ) . "';\n";
            }
        }

        // Close the php tag
        $configurationContent .= "?>";
        
        // Add the back to non array if we did put it in one
        if($configKey != false)
        {
            $this->data[$name] = $Old_Data;
        }
        
        // Copy the current config file for backup, 
        // and write the new config values to the new config
        copy($this->files[$name]['filePath'], $this->files[$name]['filePath'].'.bak');
        return file_put_contents($this->files[$name]['filePath'], $configurationContent);
    }
    
    // This function will revert the last saved configurationFile
    public function restore($name) 
    {
        // Copy the backup config file nd write the config values to the current config
        return copy($this->files[$name]['filePath'].'bak', $this->files[$name]['filePath']);
    }
}
?>