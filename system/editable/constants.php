<?php

/**************************************/
/****	  SuperSmash Framework     ****/
/****	  Created By SuperSmash    ****/
/****	  Started on: 25-04-2012   ****/
/**************************************/

// Please do not edit anything else then this file contents.
// Editing anything else can and may cause harm to the SuperSmash framework !!!



/*************************************************/
/* Set this variable to true if you want to have */
/* an application chooser when people visit your */
/* website.										 */
/* 												 */
/* (The application choice will be stored in a 	 */
/* cookie named: changedPage)					 */
/*************************************************/

// Show the applications chooser startup page   //Default: true
$applicationChooser = true;	

/* 


/***************************************************/
/* Set this variable to which application you would*/
/* like to load as a default application.          */
/* Your website would automatically go to this 	   */
/* application and there is no way to choose       */
/* another application. 						   */
/* (Unless the applicationChooser is set to true)  */
/* 												   */
/* You can choose the application by specifying a  */
/* number. For example if:						   */
/* the directory applications contain 4 apps named:*/
/* Application A 								   */
/* Application B 								   */
/* Application C								   */
/* Start										   */
/* Then you can choose an application by entering  */
/* its application number:    					   */
/* 0  = Application A 							   */
/* 1  = Application B 							   */
/* 2  = Application C 							   */
/* 3  = Start  									   */
/* 												   */
/* All applications are ordered alfabetical  	   */
/***************************************************/

// Load application number 	// Default: 0
$applicationNumber = 0;

// Here you can change the debug log filename 	// Default: debug.log
$debugLog = "debug.log";

// Here you can change the error log filename 	// Default: error.log
$errorLog = "error.log";