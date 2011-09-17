<?php
class Waf {
    /*
     * ****************************************************************************************
     * 		Singleton pattern handlers 
     * ****************************************************************************************
     */

    // Hold an instance of the class
    private static $instance;

    // A private constructor; prevents direct creation of object
    private function __construct() {
        $time = microtime(true);
        $this->begintime = $time;
        $this->queries = array();
        $this->isConnected = false;
    }

    // The singleton method
    public static function Singleton($type = "") {
        if (!isset(self::$instance)) {
            if ($type == "api") {
                $api = WafApi::Singleton();
                self::$instance = $api;
            } else {
                $c = __CLASS__;
                self::$instance = new $c;
            }
        }
        return self::$instance;
    }

    // Prevent users to clone the instance
    public function __clone() {
        $logger = Logger::getLogger("Core.Waf");
        $logger->error("Trying to clone Waf");
        trigger_error('Don\'t clone Waf!.', E_USER_ERROR);
    }

    private $begintime;
//    private $phperrors;
    private $isConnected = false;

    function __destruct() {
        $logger = Logger::getLogger("Core.Waf");
        $curTime = microtime(true);
        $diff = round($curTime - $this->begintime, 4);
        $logger->debug("Destructing Waf - total lifetime: " . $diff . " seconds");
        if ($this->isConnected) {
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

    function Connect($host, $username, $password, $database, $prefix = "") {
        $this->MySQL_Settings = array($host, $username, $password, $database, $prefix);
    }

    function NewQuery($query = "") {
        if (!$this->isConnected) {
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

    function LoadLibrary($name) {
        if ($name != "log4php") {
            $logger = Logger::getLogger("Core.Waf");
            $logger->debug("Loading library '" . $name . "'");
        }
        if (file_exists("application/libraries/" . $name . "/library.php")) {
            $include_mode = "pre";
            include("application/libraries/" . $name . "/library.php");
            $this->libraries[count($this->libraries)] = $name;
            return true;
        } else {
            return false;
        }
    }

    function IncludeLibraries($mode) {
        $logger = Logger::getLogger("Core.Waf");
        $logger->debug("Including libraries - Mode: '" . $mode . "'");
        $include_mode = $mode;
        foreach ($this->libraries as $lib) {
            include("application/libraries/" . $lib . "/library.php");
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
                ' on line ' . $trace[0]['line'], E_USER_NOTICE);
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
     * 		Output
     * ****************************************************************************************
     */

    // Some important settings for
    public $includefile = "";
    public $designFile = "application/layout/default.html";

    // Find the correct page
    function ProcessURL() {
        $logger = Logger::getLogger("Core.Waf.ProcessURL");
        $logger->debug("Processing url " . $_SERVER['REQUEST_URI']);
        $this->isPublic = false;

        if (isset($_GET['page'])) {
            $page = "core/pages/" . $_GET['page'] . ".php";
            if (!file_exists($page)) {
                $page = "application/pages/" . $_GET['page'] . ".php";
                if (!file_exists($page)) {
                    $this->DoError(404);
                }
                $logger->debug("Page: " . $_GET['page']);
            }
            $this->I18n = "Pages";
            
        } else if (isset($_GET['module'])) {
            $page = "application/modules/" . $_GET['module'] . "/";
            if (!file_exists($page)) {
                // Check if it's a public page ?
                $page = "application/public/" . $_GET['module'] . ".php";
                if (!file_exists($page)) {
                    if (isset($_SESSION['userID'])) {
                        $this->DoError(404);
                    }
                    return;
                } else {
                    // It's a public page!!
                    $this->isPublic = true;
                    $this->includefile = $page;
                    $this->I18n = "PublicPages";
                    $logger->debug("Public page: " . $_GET['module']);
                    return;
                }
            }
            // Check if the module is allowed
            if (!Security::Allowed($_GET['module'])) {
                $this->DoError(403);
                return;
            }

            $logger->debug("Module: " . $_GET['module']);
            $this->I18n = "Module" . ucfirst($_GET['module']);

            if (isset($_GET['action'])) {
                $action = $_GET['action'];
                $page .= $_GET['action'] . ".php";
            } else {
                $action = "index";
                $page .= "index.php";
            }
            if (!file_exists($page)) {
                $this->DoError(404);
                return;
            }
            $logger->debug("Action: " . $action);
        } else {
            $page = "application/pages/index.php";
            if (!file_exists($page)) {
                $this->DoError(404);
                return;
            }
        }
        $this->includefile = $page;
    }

    // Process Output
    function DoOutput() {
        global $settings;
        $logger = Logger::getLogger("Core.Waf.DoOutput");

        // Execute header of page
        $p = false;
        $logger->debug("Starting output: " . $this->includefile);
        $logger->debug("designFile: " . $this->designFile);
        if (isset($this->I18n))
        {
            I18n::BindDomain($this->I18n);
        }
        include($this->includefile); //Preoutput execution
        $designFile = $this->designFile;

        // Read the HTML template
        $handle = fopen($designFile, "r");
        $design = fread($handle, filesize($designFile));
        fclose($handle);

        $contents = explode("<%", $design);

        // Start outputting
        echo $contents[0];
        for ($header_i = 1; $header_i < count($contents); $header_i++) {
            $slices = explode("%>", $contents[$header_i]);
            if (count($slices) > 1) {
                if (trim($slices[0] == 'body')) {
                    // Execute body of page
                    $p = true;
                    $this->IncludeLibraries('body');
                    $logger->debug("Including body");
                    include($this->includefile);
                    if (isset($this->I18n))
                    {
                        I18n::BindDomain($this->I18n);
                    }
                } else {
                    // Include an applet
                    $applet = trim($slices[0]);
                    if (file_exists("application/layout/applets/" . $applet . ".php")) {
                        $logger->debug("Including applet " . $applet);
                        I18n::BindDomain("Applets");
                        include("application/layout/applets/" . $applet . ".php");
                    } else {
                        I18n::BindDomain("Views");
                        if (strpos($applet, "?") === false) {
                            $view = ViewMaster::Create($applet);
                            $view->Output();
                        } else {
                            $parts = explode("?", $applet);
                            $parameters = array();
                            parse_str($parts[1], $parameters);
                            $view = ViewMaster::Create($parts[0]);
                            foreach ($parameters as $key => $value) {
                                $view->$key = $value;
                            }
                            $view->Output();
                        }
                    }
                }
                echo $slices[1];
            } else {
                echo $slices[0];
            }
        }
        $this->IncludeLibraries('footer');
    }

    function DoError($code) {
        $logger = Logger::getLogger("Core.Waf");
        $logger->error("DoError " . $code);
        switch ($code) {
            case 404:
                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                $this->title = "Error 404 - Page not found";
                $this->includefile = "application/errorpages/404.php";
                break;
            case 403:
                header("HTTP/1.1 403 Forbidden");
                header("Status: 403 Forbidden");
                $this->title = "Error 403 - Forbidden";
                $this->includefile = "application/errorpages/403.php";
                break;
        }
        $this->DoOutput();
        die();
    }

    function DoRedirect($url, $isPermanent = false, $isAbsolute = false) {
        $logger = Logger::getLogger("Core.Waf");
        $logger->debug("DoRedirect " . $url);
        if (!$isAbsolute) {
            global $settings;
            $url = $settings['basePath'] . $url;
            $logger->debug("Process absolute path to " . $url);
        }
        if ($isPermanent) {
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

    function Log($type, $value1, $value2, $value3) {
        $this->logs[] = array($type, $value1, $value2, $value3);
    }

}

?>