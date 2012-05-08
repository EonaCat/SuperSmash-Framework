<?php	

/**************************************/
/****	  SuperSmash Framework     ****/
/****	  Created By SuperSmash    ****/
/****	  Started on: 25-04-2012   ****/
/**************************************/

namespace settings;

// Define the directory seperator
define('DS', DIRECTORY_SEPARATOR);

// Define the root path
define('ROOT', dirname(dirname(dirname(dirname(__FILE__)))));

class settings{
	private static $application;
	private static $applications = array();
	private static $filePath;
	private static $applicationPath;
		static function getApplicationPath(){
			return self::$applicationPath;
		}
		static function getFilePath(){
			return self::$filePath;
		}		
		static function getApps(){
			return self::$applications;
		}		
		static function getApp(){
			return self::$application;
		}		
		static function setApps($value){
			self::$applications = $value;
		}
		static function set($value){
			if ($value == "applicationchooser"){
				self::$filePath = ROOT . DS . "system" . DS . 'SuperSmash';
				self::$applicationPath = "/" . "system" . "/" . 'SuperSmash';
			} else {
				self::$filePath = ROOT . DS . "applications";
				self::$applicationPath = "/" . "applications";
			}

			self::$application = $value;

			if (!is_dir(self::$filePath . DS . self::$application)){
				self::$filePath = ROOT . DS . "system" . DS . 'SuperSmash';
				self::$applicationPath = "/" . "system" . "/" . 'SuperSmash';
				self::$application = "applicationchooser";
			}
		}		
}

// Create a constant for the debug FileName
define("DEBUG", $debugLog);

// Create a constant for the error FileName
define("ERROR", $errorLog);

// Scan all the available applications
$temporary = scandir(ROOT . DS . "applications");
$applications = array();

	foreach ($temporary as $application) 
	{
		if ($application == "." || $application == "..")
			continue;
		array_push($applications, $application);
	}

settings::setApps($applications);
require_once(dirname(dirname(dirname(__FILE__))) . "\\" . "SuperSmash" . "\\" . "cookie.php");

if (isset($_POST['changepage'])){
	ob_start();
	include dirname(__FILE__) . "\\" . "SuperSmash" . "\\" . "applicationchooser" . "\\" . "configuration" . "\\" . "SuperSmashconfiguration.php";
	ob_end_clean();

	if ($sessionDatabase){
		$_SESSION['changedPage'] = $_POST['changepage'];
	} else {
		\System\SuperSmash\Cookie::set("changedPage", $_POST['changepage'], false, 3600);
		header("Location: index.php");
	}
} 

if (\System\SuperSmash\Cookie::exists("changedPage")){
	settings::set(\System\SuperSmash\Cookie::get("changedPage"));	
} else if (isset($_SESSION['changedPage'])){
	settings::set($_SESSION['changedPage']);	
} else if ($applicationChooser){
	settings::set("applicationchooser");
}

// Define the system path
define('SYSTEM', ROOT . DS . 'system');

// Define the access variable
define('SUPERSMASH_FRAMEWORK', true);

// Load the default application
if (!$applicationChooser) {
	settings::set($applications[$applicationNumber]);
}

?>