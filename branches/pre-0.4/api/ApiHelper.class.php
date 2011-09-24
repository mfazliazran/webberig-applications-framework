<?php
class ApiHelper
{
	static function http_digest_parse($txt)
	{
	   // protect against missing data
	   $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
	   $data = array();
	
	   preg_match_all('@(\w+)=(?:(?:\'([^\']+)\'|"([^"]+)")|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
	
	   foreach ($matches as $m) {
		  $data[$m[1]] = $m[2] ? $m[2] : ($m[3] ? $m[3] : $m[4]);
		  unset($needed_parts[$m[1]]);
	   }
	
	   return $needed_parts ? false : $data;
	}
	
	public static function ReadRecords($rs)
	{
		$data = array();
		while ($row = mysql_fetch_assoc($rs))
		{
			$data[] = $row;
		}
		return $data;
	}
}
?>