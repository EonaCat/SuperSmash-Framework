<?php

if (!defined("SUPERSMASH_FRAMEWORK")){die("You cannot access this page directly!");}

return array(
	'autoLoad' => "Autoload failed to load class: %s",
	'view' => "Unable to locate the view file \"%s\". Please make sure a view page is created and is correctly named.",
	'no_array' => "Variable \$%s passed is a non-array format in method %s",
	'db_key_not_found' => "The database key was not found",
	'sessionTable' => "The session database does not exist<br>Please run the session SQL script",
	'invalidCookieName' => "Invalid cookie name!",
	'invalidTableName' => "Invalid table name!",
	'invalidExpirationTime.' => "Seconds till expiration must be a valid number.",
	'invalidSecondsTime' => "Seconds till expiration can not be zero or less. Enable session expiration when the browser closes instead.",
	'invalidExpirationOnClose' => "Expire on close must be either TRUE or FALSE.",
	'invalidSessionRenewalTimeNumber' => "Session renewal time must be a valid number.",
	'invalidSessionRenewalTime' => "Session renewal time can not be zero or less.",	
	'invalidIPAddressFormat' => "The IP address must be a string similar to this: '192.168.10.200'",
	'invalidIPAddress' => "Invalid IP address.",
	'invalidSecureCookie' => "The secure cookie option must be either TRUE or FALSE.",	
);