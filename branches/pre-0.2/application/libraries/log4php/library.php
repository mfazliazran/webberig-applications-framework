<?php
// $include_mode = pre / head / body / footer;
switch ($include_mode)
{
	case 'pre':
		require ("application/libraries/log4php/Logger.php");
        global $settings;
        if ($settings["debugging"])
        {
            Logger::configure('application/libraries/log4php/config.debug.xml');
        } else {
            Logger::configure('application/libraries/log4php/config.xml');
        }
	break;
}
?>