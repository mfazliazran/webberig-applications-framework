<?php
abstract class ViewMaster
{
    public static function Create($name)
    {
        $logger = Logger::getLogger("Core.ViewMaster");
        $logger->debug("Creating view - ". $name);
        require_once("application/views/" . $name . ".php");
        return new $name();
    }

 /*
 * ****************************************************************************************
 * 		Parameter storage
 * ****************************************************************************************
 */
    private $data = array();
	
    public function __set($name, $value) {
        $logger = Logger::getLogger("Core.ViewMaster");
        $logger->debug("Setting variable - ". $name .": " . $value);
        $this->data[$name] = $value;
    }

    public function __get($name) {
        $logger = Logger::getLogger("Core.ViewMaster");
        $logger->debug("Getting variable - ". $name);
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
        $logger = Logger::getLogger("Core.ViewMaster");
        $logger->debug("Unsetting variable - ". $name);
        unset($this->data[$name]);
    }
	
    function Output()
    {
        $logger = Logger::getLogger("Core.ViewMaster");
        $logger->debug("Outputting view - ". $name);
        I18n::BindDomain("Applets");
        $this->DoOutput();
    }
    abstract function DoOutput();
}

?>