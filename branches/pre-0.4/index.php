<?php
// Set the autoload function for classes
function __autoload($class)
{
    if(file_exists(dirname(__FILE__). "/core/$class.class.php") && include_once(dirname(__FILE__). "/core/$class.class.php")) {		
        return true;
    } elseif (file_exists(dirname(__FILE__). "/application/classes/$class.class.php") && include_once(dirname(__FILE__). "/application/classes/$class.class.php")) {
		return true;
    } else {
        trigger_error("Could not load class '$class' from file '$class.class.php'", E_USER_WARNING);
        return false;
    }
}

// Load settings
include("application/settings.php");

// Start session
session_start();
ini_set('arg_separator.output','&amp;');


// Start debugging
if ($settings["debugging"])
{
	ini_set('display_errors', '1');
	error_reporting(E_ALL);
	ini_set('log_errors', '0');
} else {
	ini_set('log_errors', '1');
	ini_set('error_log', 'phperrors.txt');
	ini_set('display_errors', '0');
}

// Locales
I18n::SetLocale();


// Start Waf
$f = Waf::Singleton();

$f->LoadLibrary("log4php");
$logger = Logger::getLogger("Core");
$logger->debug("Logger initialized");

$f->LoadLibrary("jquery");

if (isset($settings["db"]))
	$f->Connect($settings["db"]["hostname"], $settings["db"]["username"], $settings["db"]["password"], $settings["db"]["database"], $settings["db"]["prefix"]);

// Decide the include file
$f->ProcessURL();

// Perform a security check
if (!Security::Check())
{
	Security::ShowLoginWindow();
	die();
}
// Process the page
$f->DoOutput();
?>
