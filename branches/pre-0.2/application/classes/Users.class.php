<?php
class Users
{
	static function GetList()
	{
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Retrieving user list");
		$f = Waf::Singleton();
		$qry = $f->NewQuery("SELECT * FROM %PRE%users ORDER BY fullname ASC");
		return $qry->Exec();
	}
	static function GetUserByID($id)
	{
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Looking for user by ID " . $id);
		$f = Waf::Singleton();
		$qry = $f->NewQuery("SELECT * FROM %PRE%users WHERE ID = @id");
		$qry->SetParam("id", $id);
		$retid = $qry->Exec();
		if ($row = mysql_fetch_assoc($retid))
		{
			$logger->debug("User found: " . $row['fullname']);
			return $row;
		} else {
			$logger->error("User not found");
			return false;
		}
	}

	static function GetUserByUsername($id)
	{
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Looking for user by username " . $id);
		$f = Waf::Singleton();
		$qry = $f->NewQuery("SELECT * FROM %PRE%users WHERE username = @id");
		$qry->SetParam("id", $id);
		$retid = $qry->Exec();
		if ($row = mysql_fetch_assoc($retid))
		{
			$logger->debug("User found: " . $row['fullname']);
			return $row;
		} else {
			$logger->error("User not found");
			return false;
		}
	}
    
    static function GetUserByIdentity($id)
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Looking for user by identity " . $id);
        $f = Waf::Singleton();
        $qry = $f->NewQuery("SELECT u.* FROM %PRE%google_accounts acc JOIN %PRE%users as u ON acc.userid = u.ID WHERE acc.identity = @id");
        $qry->SetParam("id", $id);
        $retid = $qry->Exec();
        if ($row = mysql_fetch_assoc($retid))
        {
                $logger->debug("User found: " . $row['fullname']);
                return $row;
        } else {
                $logger->error("User not found");
                return false;
        }
    }
    
    static function RegisterGoogleIdentity($id)
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Registering Google identity " . $id);
		$f = Waf::Singleton();
        $qry = $f->NewQuery("REPLACE INTO %PRE%google_accounts (identity, userid) VALUES (@identity, @id)");
		$qry->SetParam("identity",$id);
		$qry->SetParam("id",$_SESSION['userID']);		
		$rs = $qry->Exec();
        
        
    }
	static function ChangePassword($userID, $newpassword)
	{
            $logger = Logger::getLogger("ApplicationCore.Classes.Users");
            $logger->debug("Changing password for user id" . $userID);
            $f = Waf::Singleton();
            $values = array();
            $values['password'] = sha1($newpassword);
            $qry = $f->NewQuery("UPDATE %PRE%users SET password = SHA1(@pass) WHERE ID = @ID");
            $qry->SetParam("pass",$newpassword);
            $qry->SetParam("ID",$userID);		
            $rs = $qry->Exec();
            return $rs;	
	}
	static function Delete($id)
	{
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Deleting user id " . $id);
		$f = Waf::Singleton();
		$qry = $f->NewQuery("DELETE FROM %PRE%users WHERE ID = @id;");
		$qry->SetParam("id", $id);
		$qry->Exec();
	}
	static function Insert($form)
	{
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Creating a user");
		$f = Waf::Singleton();
		$qry = $f->NewQuery("INSERT INTO %PRE%users (username, password, fullname, role) VALUES (@username, SHA1(@password), @fullname, @role);");
		$qry->SetParam("username", $form['username']);
		$qry->SetParam("password", $form['password']);
		$qry->SetParam("fullname", $form['fullname']);
		$qry->SetParam("role", $form['role']);

		$rs = $qry->Exec();
		return $rs;
	
	}
	static function Update($id, $form)
	{
        $logger = Logger::getLogger("ApplicationCore.Classes.Users");
        $logger->debug("Updating user " . $id);
		$f = Waf::Singleton();
        if (strlen($form['password']))
        {
			$pass = ", password = SHA1(@password)";
        } else {
            $pass = "";
        }
		$qry = $f->NewQuery("UPDATE %PRE%users SET role = @role, username = @username, fullname = @fullname". $pass ." WHERE ID = @id;");
		$qry->SetParam("username", $form['username']);
		$qry->SetParam("fullname", $form['fullname']);
		$qry->SetParam("role", $form['role']);
        if (strlen($form['password']))
        {
			$qry->SetParam("password", $form['password']);
        }
		$qry->SetParam("id", $id);
		$rs = $qry->Exec();
		return $rs; 
	
	}
}
?>