<?php

/**************************************/
/****     SuperSmash Framework     ****/
/****     Created By SuperSmash    ****/
/****     Started on: 25-04-2012   ****/
/**************************************/

namespace System\SuperSmash;

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

class Benchmark {
    // Create the arrays that holds the timers
    protected $start = array(); 
    protected $stop = array();

    // This function will start a new timer
    public function start($key) {
        $this->start[$key] = microtime(true);
    }

    // This function will stop the specified timer
    public function stop($key) {
        $this->stop[$key] = microtime(true);
    }

    // This function will show the specified timer 
    public function elapsed($key, $round = 3, $stop = false) {
        if(!isset($this->start[$key])) {
            showError('benchMark', array($key), E_WARNING);
            return false;
        } else {
            if(!isset($this->stop[$key]) && $stop == true) {
                $this->stop[$key] = microtime(true);
            }
            return round((microtime(true) - $this->start[$key]), $round);
        }
    }
    
    // This function will return the amount of memory the page uses while loading
    public function usage() {
        $returnValue = '';
        $usage = memory_get_usage(true); 
        
        if($usage < 1024) {
            $returnValue =  $usage." bytes"; 
        } elseif($usage < 1048576) {
            $returnValue = round($usage/1024, 2)." kilobytes"; 
        } else { 
            $returnValue = round($usage/1048576, 2)." megabytes"; 
        }	
        return $returnValue;
    }
}
?>