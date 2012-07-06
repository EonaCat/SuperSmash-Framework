<?php


/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace system\library;
use settings\settings;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Cache 
{
    protected $path;

    // Create the contructor
    public function __construct() 
    {
        $this->path = settings::getFilePath() . DS . settings::getApp() . DS . 'cache';
    }

    // This function will set the cache path
    public function set_path($path) 
    {
        // Remove any trailing slashes
        $path = rtrim($path, '/');
        $this->path = str_replace( array('\\', '/'), DS, $path );
    }

    // This function will read and returns the contents of the cached file
    public function get($id) 
    {
        // Define a file path
        $file = $this->path . DS . $id . '.cache';
        
        // check if our file exists
        if(file_exists($file)) 
        {
            // Get our file contents and Unserialize our data
            $data = file_get_contents($file);
            $data = unserialize($data);

            // Check out expire time, if expired, remove the file
            if($data['expire_time'] < time()) 
            {
                unlink($file);
                return false;
            }
            return $data['data'];
        }
        return false;
    }

    // This function will save the contents into the given file id.
    public function save($id, $contents, $expire = 86400) 
    {
        // Define a file path
        $file = $this->path . DS . $id . '.cache';
        
        // Create the files contents
        $data = array(
                    'expire_time' => (time() + $expire),
                    'data' => $contents
                );

        // Save file and contents
        if(file_put_contents( $file, serialize($data) )) 
        {
            // Try to put read/write permissions on the new file
            @chmod($file, 0777);
            return true;
        }
        return false;
    }

    // This function will delete a cached file
    public function delete($id) 
    {
        // Define a file path
        $file = $this->path . DS . $id . '.cache';
        
        // Return the direct result of the deleting
        return unlink($file);
    }

    // This function will delete all the cached files
    public function clear() 
    {
        // get a list of all files and directories
        $files = scandir($this->path);
        foreach($files as $file) 
        {
            // Define a file path
            $file = $this->path . DS . $file;
        
            // We only want to delete the the cache files, not subfolders
            if($file[0] != "." && $file != 'index.html') 
            {
                unlink($file); //Remove the file
            }
        }
        return true;
    }
}