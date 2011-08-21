<?php
class Modules
{
	static function GetModules()
	{
        $logger = Logger::getLogger("Core.Modules");
        $logger->debug("Getting all modules");

		$array = array();
		$handle = opendir('application/modules');
    	while ($file = readdir($handle)) {
			if ((is_dir('application/modules/'.$file))&&($file <> ".")&&($file <> ".."))
			{
				if ($module = Modules::GetModule($file))
				{
					$array[$file] = $module;
				}
			}
    	}
    	closedir($handle);
		ksort($array);
		uasort($array, array('self', 'SortModules'));
		return $array;
	}

	static function GetModule($name)
	{
        $logger = Logger::getLogger("Core.Modules");
        $logger->debug("Reading module '". $name ."'");
		if (file_exists("application/modules/".$name."/module.xml"))
		{
			$module = array();
			$xml = simplexml_load_file("application/modules/".$name."/module.xml");
			foreach ($xml->children() as $node)
			{
				$module[$node->getName()] = (string) $node;
			}
			if (!isset($module['sequence']))
			{
				$module['sequence'] = 0;
			}
			$module['dir'] = $name;
			return $module;
		} else {
			return false;
		}
	}
	private static function SortModules($a, $b)
	{
		if ($a['sequence'] == $b['sequence'])
		{
			
			return 0;
		}
		return ($a['sequence'] < $b['sequence']) ? -1 : 1;
	}
	
	static function GetModuleSecurity($name)
	{
        $logger = Logger::getLogger("Core.Modules");
        $logger->debug("Reading module security '". $name ."'");
		if (file_exists("application/modules/".$name."/module.xml"))
		{
		  $arrList = array();
		  		  
			$xml = simplexml_load_file("application/modules/".$name."/module.xml");
			$n = $xml->xpath("/module/security");
			if ($n)
			{
			foreach ($n[0] as $key)
			{
			   foreach($key->attributes() as $a => $b) 
			   {
			   		$name = $b;
			   		
  			  		//$arrList[(string)$b] = 1;
					
         		}
		  		$arrList[count($arrList)] = array((string)$name, (string)$key);
      		}
			}
      return $arrList;
		} else {
			return array("ACCESS" => false);
		}
  
  }
}
?>