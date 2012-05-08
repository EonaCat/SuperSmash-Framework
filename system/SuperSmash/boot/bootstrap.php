<?php

/**************************************/
/****	  SuperSmash Framework     ****/
/****	  Created By SuperSmash    ****/
/****	  Started on: 25-04-2012   ****/
/**************************************/

// Require the global settings
require (SYSTEM . DS . 'SuperSmash' . DS . 'global.php');

// Require the registry
require (SYSTEM . DS . 'SuperSmash' . DS . 'registry.php');
 
// Register the SuperSmash framework to process errors through a custom error handling system
set_error_handler( 'errorHandler' , E_ALL | E_STRICT );

// Initiate the system start time
$Benchmark = loadClass('Benchmark');
$Benchmark->start('system');

// Load the SuperSmash Framework
$SuperSmash = loadClass('SuperSmash');

?>