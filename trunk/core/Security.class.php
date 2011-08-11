<?php
class Security
{
	static function Check()
	{
        $logger = Logger::getLogger("Core.Security");
        
        $f = Waf::Singleton();
        if ($f->isPublic)
        {
            $logger->debug("Security check PASSED: public page");
            return true;
        }
		if (!isset($_SESSION['userID']) || !isset($_SESSION['user']))
		{
            $logger->debug("Security check FAILED: Session variables not found");
			return false;
		} else {
			if (!Security::CheckFingerprint())
			{
                $logger->debug("Security check FAILED: Fingerprint check failed");
				return false;				
			}
		}	
        $logger->debug("Security check PASSED: '". $_SESSION['user']."'");
		return true;
	}
	
	static function Login($user, $password)
	{
        $logger = Logger::getLogger("Core.Security");
		session_regenerate_id();
		global $f;
		$qry = $f->NewQuery("SELECT * FROM %PRE%users WHERE username = @user AND password = SHA1(@pass) LIMIT 1;");
		$qry->setParam("user", $user);
		$qry->setParam("pass", $password);
		$retid = $qry->Exec();
		if (!$row = mysql_fetch_assoc($retid))
		{	
            $logger->debug("Login FAILED: no records found for username '". $user ."'");
			//session_destroy();
			return false;
		} else {
            $logger->debug("Login SUCCESS: username '". $user ."'");
            Security::CreateSession($row);
			return true;
		}
	}
	
    
    static function CreateSession($user)
    {
        $logger = Logger::getLogger("Core.Security");
        $logger->debug("Creating session for user '". $user['username'] ."'");
        $_SESSION['user'] = $user['username'];
        $_SESSION['userID'] = $user['ID'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fingerprint'] = Security::GenerateFingerprint();
        
    }
    static function Allowed($module, $sub = "")
    {
        $logger = Logger::getLogger("Core.Security");
        $f = Waf::Singleton();
        if ($f->isPublic || !isset($_SESSION['role']))
        {
            return false;
        }
        $qry = $f->NewQuery("SELECT value from %PRE%roledetails WHERE roleID = @roleID AND module = @module AND sub = @sub");
        $qry->SetParam("roleID", $_SESSION['role']);
        $qry->SetParam("module", $module);
        $qry->SetParam("sub", $sub);
        $retid = $qry->Exec();

        if($line = mysql_fetch_assoc($retid))
        {  
                $val = ($line['value']==1) ? true : false;
        } else {
                $val = false;
        }
        if (isset($val))
        {
            if ($val)
            {
                $logger->debug("Security allowed check - Success '". $module ."/". $sub ."'");
                return true;
            } else {
                $logger->debug("Security allowed check - Failed '". $module ."/". $sub ."'");
                return false;
            }
        }
    }
    static function Logout()
    {
    $logger = Logger::getLogger("Core.Security");
    $logger->debug("Logging out");

            session_destroy();
    }
    static function ShowLoginWindow()
    {
    $logger = Logger::getLogger("Core.Security");
    $logger->debug("Show login screen");
    $f = Waf::Singleton();
        $f->includefile = "application/modules/account/login.php";
        $f->DoOutput();
        die();
    }
	
	// Fingerprint functions
	static function CheckFingerprint()
	{
        $logger = Logger::getLogger("Core.Security");
		if (!isset($_SESSION['fingerprint']))
        {
            $logger->debug("Checking fingerprint: can't find session variable");
			return false;
        }
		if ($_SESSION['fingerprint']==Security::GenerateFingerprint())
		{
            $logger->debug("Checking fingerprint: Success");
			return true;
		} else
		{
            $logger->error("Checking fingerprint: Failed");
			return false;	
		}
	}
	
	static function GenerateFingerprint()
	{
        $logger = Logger::getLogger("Core.Security");
        $logger->debug("Generating fingerprint");
		if (!isset($_SESSION['username']))
			return false;
        
		$user = $_SESSION['username'];
		global $settings;
		$word = $settings['ApplicationName'];
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$php_sessid = session_id();
		$fingerprint = $word . $browser . $ip . $user . $php_sessid;
		return md5($fingerprint);
		
	}
}

?>