<?php
abstract class ViewMaster
{
    public static function Create($name)
    {
        $logger = Logger::getLogger("Core.ViewMaster");
        $logger->debug("Creating view - ". $name);
        require_once("application/views/" . $name . ".php");
        $obj = new $name();
        $obj->logger = Logger::getLogger("View." . $name);
        return $obj;
    }

 /*
 * ****************************************************************************************
 * 		Parameter storage
 * ****************************************************************************************
 */
    private $data = array();
    public $logger;
    
    public function __set($name, $value) {
        $this->logger->debug("Setting variable - ". $name .": " . $value);
        $this->data[$name] = $value;
    }

    public function __get($name) {
        $this->logger->debug("Getting variable - ". $name);
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
        $this->logger->debug("Unsetting variable - ". $name);
        unset($this->data[$name]);
    }
	
    function Output()
    {
        $this->logger->debug("Outputting view");
        I18n::BindDomain("Applets");
        $this->DoOutput();
    }
    abstract function DoOutput();
}

?>