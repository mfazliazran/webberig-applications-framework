<?php
class Waf_MySQL_Query
{
	private $base;
	public $qry;
	function __construct(&$parent, $query) {
		$this->base = $parent;
		$this->queryText($query);
	}

   	function makesafe($var, $quotes = false)
	{
        if (is_array($var)) {   //run each array item through this function (by reference)       
            foreach ($var as &$val) {
                $val = $this->makesafe($val, $quotes);
            }
        }
        else if (is_string($var)) { //clean strings
            $var = mysql_real_escape_string($var);
            if ($quotes) {
                $var = "'". $var ."'";
            }
        }
        else if (is_null($var)) {   //convert null variables to SQL NULL
            $var = "NULL";
        }
        else if (is_bool($var)) {   //convert boolean variables to binary boolean
            $var = ($var) ? 1 : 0;
        }
        return $var;		
	}
	function queryText($query)
	{
		$query = str_replace("%PRE%", $this->base->MySQL_prefix, $query);
		$this->qry = $query;
	}
	function setParam($name, $value)
	{
		$this->qry = str_replace(("@".$name), ("'".$this->makesafe($value)."'"), $this->qry);
	}
	function GenerateUpdateQuery($table, $values, $clause)
	{
        $logger = Logger::getLogger("Core.Waf.MySQL_Query");
        $logger->debug("Generating UPDATE query for table " . $table);
        
		$table = $this->base->MySQL_prefix . $table;
		$sql = "UPDATE `$table` SET ";
		$i = 0;
		foreach($values as $key => $value)
		{
			if ($i++ > 0)
			{
				$sql .= ", ";
			}
			$sql .= $key."= '". $this->makesafe($value)."'";
		}
		$sql .= " WHERE ";
		$i = 0;
		foreach($clause as $key => $value)
		{
			if ($i++ > 0)
			{
				$sql .= " AND ";
			}
			$sql .= $key."= '". $this->makesafe($value) . "'";
		}
		return $this->queryText($sql);
	}
	function GenerateInsertQuery($table, $values)
	{
        $logger = Logger::getLogger("Core.Waf.MySQL_Query");
        $logger->debug("Generating INSERT query for table " . $table);
		$table = $this->base->MySQL_prefix . $table;
		$sql = "INSERT INTO `$table` (";
		$i = 0;
		foreach($values as $key => $value)
		{
			if ($i++ > 0)
			{
				$sql .= ", ";
			}
			$sql .= "`".$key."`";
		}
		$sql .= ") VALUES (";
		$i = 0;
		foreach($values as $key => $value)
		{
			if ($i++ > 0)
			{
				$sql .= ", ";
			}
			$sql .= "'".$this->makesafe($value)."'";
		}
		$sql .= ");";
		return $this->queryText($sql);
	}
	
	function Exec()
	{
        $logger = Logger::getLogger("Core.Waf.MySQL_Query");
        $logger->debug("Executing query: " . $this->qry);
		$retid = mysql_query($this->qry, $this->base->MySQL_con);
		if ($retid)
		{
			$this->base->Log("SQL", "SUCCESS", $this->qry, "");
		} else
		{
            $logger->error("SQL error: " . mysql_error($this->base->MySQL_con));
			$this->base->Log("SQL", "FAILED", $this->qry, mysql_error($this->base->MySQL_con));
		}
		return $retid;
	}
}
?>