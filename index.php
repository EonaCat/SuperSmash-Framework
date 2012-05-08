<?php	

/**************************************/
/****	  SuperSmash Framework     ****/
/****	  Created By SuperSmash    ****/
/****	  Started on: 25-04-2012   ****/
/**************************************/

use settings\settings;

// We need to include all the custom editable settings   // PLEASE DO NOT EDIT ANYTHING BUT FRAMEWORK CONTENT INSIDE THIS FOLDER !!!
require_once("system/editable/constants.php");

// We need to include all the available applications that are using the SuperSmash Framework
require_once ("system/SuperSmash/boot/applications.php");

// We need to bootstrap the SuperSmash Framework
require_once("system/SuperSmash/boot/bootstrap.php");

// Finally we need to start the SuperSmash Framework
$SuperSmash->start();
?>