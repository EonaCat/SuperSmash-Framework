<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\SuperSmash;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Registry 
{

    // Registry array of objects  
    private static $objects = array();

    // The instance of the registry 
    private static $instance;

    // prevent cloning of the registry 
    public function __clone()
    {
        // Do nothing
    }


    // This function will create a singleton if it not has been created yet
    public static function singleton() 
    {
        if(!isset(self::$instance)) 
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // This function will get a specified key and returns it
    protected function get($key) 
    {
        if(isset(self::$objects[$key])) 
        {
            return self::$objects[$key];
        }
        return null;
    }

    // This function will set a specified key
    protected function set($key,$value) 
    {
        self::$objects[$key] = $value;
    }

    // This function will load a specified key and returns the singleton
    static function load($key) 
    {
        return self::singleton()->get($key);
    }

    // This function will store an object as a singleton
    static function store($key, $instance) 
    {
        return self::singleton()->set($key,$instance);
    }
}
?>