<?php
class Utility
{
	static function ReverseDate($date)
	{
        if (strlen($date))
        {
            //This function changes the format DD-MM-YYYY into YYYY-MM-DD and vice versa
            $newdate = str_replace("/", "-", $date);
            $newdate = explode("-", $date);
            return $newdate[2]."-".$newdate[1]."-".$newdate[0];
        } else {
            return "";
        }
	}
	static function left($str, $cnt){
		return substr($str, 0, $cnt);
	}
    
	static function DateFromTimeStamp($timestamp)
	{
		$parts = explode(" ", $timestamp);
		return Utility::ReverseDate($parts[0]);
	}
	
	static function cleanURL($url) {
		$newUrl = str_replace("__", "_", strtolower(preg_replace("@\W@", "", str_replace(" ", "_", $url))));
		return $newUrl;
	}
    
    static function FormatCurrency($value)
    {
        $return = "&euro; ";
        //		$return .= round($value, 2);
        $return .= number_format( $value, 2, ',', '');
        return $return;
    }
    
    static function CSS($file)
    {
        global $settings;
        
        // application/layout/css/screen.css
        if (!file_exists($file))
        {
            throw new Exception("File '". $file ."' does not exist");
        }
        $md5 = md5_file($file);

        if (isset($settings['CSSPalette']) && is_array($settings['CSSPalette']))
        {
            $txt = implode("", $settings['CSSPalette']);
            $md5 = md5($md5.$txt);
        }

        $md5 = Utility::left($md5,5);
?>
    <link href="<?php echo $file . "?" . $md5;?>" rel="stylesheet" />
<?php
    }
    static function JS($file)
    {
        if (!file_exists($file))
        {
            throw new Exception("File '". $file ."' does not exist");
        }
        $md5 = md5_file($file);
        $md5 = Utility::left($md5,5);
?>
    <script src="<?php echo $file . "?" . $md5;?>"></script>
<?php
    }
}
?>