<?php
class WafApi
{
/*
 * ****************************************************************************************
 * 		Singleton pattern handlers 
 * ****************************************************************************************
 */
    // Hold an instance of the class
    private static $instance;

    // A private constructor; prevents direct creation of object
    private function __construct() 
    {
	 	$time = microtime(true);
		$this->begintime = $time;
		$this->queries = array();
		$this->isConnected = false;
    }

    // The singleton method
    public static function Singleton() 
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    // Prevent users to clone the instance
    public function __clone()
    {
        $logger = Logger::getLogger("Core.Waf");
        $logger->error("Trying to clone Waf");
        trigger_error('Don\'t clone Waf!.', E_USER_ERROR);
    }

	private $begintime;
	private $phperrors;
	private $isConnected = false;
	
	
	function __destruct()
	{
        $logger = Logger::getLogger("Core.Waf");
        $curTime = microtime(true);
        $diff = round($curTime - $this->begintime, 4);
        $logger->debug("Destructing Waf - total lifetime: " . $diff . " seconds");
		if ($this->isConnected)
		{
			mysql_close();
		}
	}

/*
 * ****************************************************************************************
 * 		MySQL 
 * ****************************************************************************************
 */
	// MySQL vars
	var $MySQL_con; //Connection string
	var $MySQL_prefix; //prefix
	var $MySQL_Settings = array();
	function Connect($host, $username, $password, $database, $prefix = "")
	{
		$this->MySQL_Settings = array($host, $username, $password, $database, $prefix);
	}

	function NewQuery($query = "")
   	{
		if (!$this->isConnected)
		{
            $logger = Logger::getLogger("Core.Waf");
            $logger->debug("Connecting to MySQL database");
			// First Query - Open the connection first!
			$host = $this->MySQL_Settings[0];
			$username = $this->MySQL_Settings[1];
			$password = $this->MySQL_Settings[2];
			$database = $this->MySQL_Settings[3];
			$prefix = $this->MySQL_Settings[4];
			$this->MySQL_con = mysql_connect($host, $username, $password);
			mysql_select_db($database, $this->MySQL_con);
			$this->MySQL_prefix = $prefix;
		}

   		return new Waf_MySQL_Query($this, $query);
   	}
	
/*
 * ****************************************************************************************
 * 		Library processing 
 * ****************************************************************************************
 */
	var $libraries = array();
	function LoadLibrary($name)
	{
        if ($name != "log4php")
        {
            $logger = Logger::getLogger("Core.Waf");
            $logger->debug("Loading library '" . $name . "'");
        }
		if (file_exists("application/libraries/".$name."/library.php"))
		{
			$include_mode = "pre";
			include("application/libraries/".$name."/library.php");
			$this->libraries[count($this->libraries)] = $name;
			return true;
		} else {
			return false;
		}
	}

	function IncludeLibraries($mode)
	{
        $logger = Logger::getLogger("Core.Waf");
        $logger->debug("Including libraries - Mode: '" . $mode . "'");
		$include_mode = $mode;
		foreach($this->libraries as $lib)
		{
			include("application/libraries/".$lib."/library.php");
		}
	}

/*
 * ****************************************************************************************
 * 		Parameter storage
 * ****************************************************************************************
 */
	// Store parameters here!
	private $data = array();
	
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
	
    public function __isset($name) {
        return isset($this->data[$name]);
    }

    /**  As of PHP 5.1.0  */
    public function __unset($name) {
        unset($this->data[$name]);
    }
	
	

/*
 * ****************************************************************************************
 * 		API Authentication
 * ****************************************************************************************
 */
   public function Authenticate()
   {
	 $f = WafApi::Singleton();
	 if(!isset($_SERVER['PHP_AUTH_USER']))  
	 {  	   
	    $f->DoError(401);
	 }
	 if (!Security::Login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
	 {
	    $f->DoError(401);
	 }
   }
 
 
/*
 * ****************************************************************************************
 * 		Output
 * ****************************************************************************************
 */

	// Find the correct page
	function ProcessURL()
	{
	    $method = strtolower($_SERVER['REQUEST_METHOD']);
		
        $logger = Logger::getLogger("Core.Waf.ProcessURL");
        $logger->debug("Processing " . $method . ": " . $_SERVER['REQUEST_URI']);

	    if (!isset($_GET['controller']))
	    {
			$logger->debug("No controller present - returning 200 OK");
			$this->DoError(200);
	    }
	    $filename = "application/api/" . strtolower($_GET['controller']) . ".class.php";
	    if (!file_exists($filename))
	    {
			$logger->error("File " . $filename . " does not exist");
		 $this->DoError(404);
	    }
	    require($filename);
	    $className = "api_" . strtolower($_GET['controller']);
	    $c = new $className;
	    
		$logger->debug("Method: " . $method);
	    $ret = null;
	    switch ($method)
	    {
		  case 'get':
			if (isset($_GET['value']))
			{
			   $ret = $c->Get($_GET['value']);
			} else {
			   $ret = $c->Get();
			}
			break;
		  case 'put':
			if (!isset($_GET['value']))
			   $this->DoError(401);
			$ret = $c->Put($_GET['value']);
			break;
		  case 'post':
			$ret = $c->Post();
			break;
		  case 'delete':
			if (!isset($_GET['value']))
			   $this->DoError(401);
			$ret = $c->Delete($_GET['value']);
			break;
		  default:
			$this->DoError(500);
		  break;
	    
	    }
	    if ($ret)
	    {
		  $this->DoOutput($ret);
	    }
	}

	// Process Output
	function DoOutput($obj)
	{
	    header("content-type: text/plain");
	    echo json_encode($obj);
	 /*
		global $settings;
        $logger = Logger::getLogger("Core.Waf.DoOutput");
	 */
	}
	
	function DoError($code)
	{
        $logger = Logger::getLogger("Core.Waf");
        $logger->error("DoError " . $code);
	   $f = WafApi::Singleton();
	   global $settings;
	 $obj = new ApiReturnObject();

		switch ($code)
		{
			case 200:
				header("HTTP/1.1 200 OK");
				header("Status: 200 OK"); 
				 $obj->data = null;
				 $obj->error = "OK";
				 $obj->status = 200;
				break;
			case 404:
				header("HTTP/1.1 404 Not Found");
				header("Status: 404 Not Found"); 
				 $obj->data = null;
				 $obj->error = "API kan niet worden gevonden";
				 $obj->status = 404;
				break;
			case 401:
			   header('HTTP/1.1 401 Unauthorized');  
			   header("Status: 401 Unauthorized"); 
			   header('WWW-Authenticate: Basic realm="' . $settings['basePath'] . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($settings['basePath']) . '"');
			   $obj->data = null;
			   $obj->error = "Login-gegevens ontbreken of zijn ongeldig";
			   $obj->status = 401;
			   break;
			case 403:
				header("HTTP/1.1 403 Forbidden");
				header("Status: 403 Forbidden"); 
				 $obj->data = null;
				 $obj->error = "API call is niet toegelaten";
				 $obj->status = 403;
				break;
			case 400: //Bad Request
				header("HTTP/1.1 400 Bad Request");
				header("Status: 400 Forbidden"); 
				 $obj->data = null;
				 $obj->error = "API call is niet toegelaten";
				 $obj->status = 400;
				break;
			   
		}
		$this->DoOutput($obj);
		die();

  	}
    function DoRedirect($url, $isPermanent = false, $isAbsolute = false)
    {
        $logger = Logger::getLogger("Core.Waf");
        $logger->debug("DoRedirect " . $url);
        if (!$isAbsolute)
        {
            global $settings;
            $url = $settings['basePath'] . $url;
            $logger->debug("Process absolute path to " . $url);
        }
        if ($isPermanent)
        {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header("location: " . $url);
        die();
    }

/**
 * ****************************************************************************************
 * 		Logging
 * ****************************************************************************************
 */
	public $logs = array();
	function Log($type, $value1, $value2, $value3)
	{		
		$this->logs[] = array($type, $value1, $value2, $value3);	
	}
	
}

?>