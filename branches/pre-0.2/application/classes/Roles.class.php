<?php
class Roles
{
    static function GetList()
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Getting the role list");
        $f = Waf::Singleton();
        $qry = $f->NewQuery("SELECT id, name FROM %PRE%roles WHERE sequence >= (SELECT sequence FROM %PRE%roles WHERE id = @role) ORDER BY sequence ASC, name ASC; ");
        $qry->SetParam("role", $_SESSION['role']);
        return $qry->Exec();
    }
    
    static function Delete($id)
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Deleting role id " . $id);
        $f = Waf::Singleton();
        $qry = $f->NewQuery("DELETE FROM %PRE%roles WHERE ID = @ID;");
        $qry->SetParam("ID", $id);
        $qry->Exec();

        $qry = $f->NewQuery("DELETE FROM %PRE%roledetails WHERE roleID = @ID;");
        $qry->SetParam("ID", $id);
        $qry->Exec();
    }
    
    static function Insert($form)
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Creating a role");
        $f = Waf::Singleton();
        $values = array();
        $values['name'] = $form['name'];
        $values['sequence'] = $form['sequence'];
        $qry = $f->NewQuery();
        $qry->GenerateInsertQuery("roles", $values);
        if ($rs = $qry->Exec())
        {
            $roleID = mysql_insert_id();
            $ret = Roles::SaveProfile($roleID, $form);
            return $ret;
        } else {
            return $rs;          
        }
    }
    
    static function Update($id, $form)
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Updating role id " . $id);
        $f = Waf::Singleton();
        $values = array();
        $values['name'] = $form['name'];
        $values['sequence'] = $form['sequence'];
        $qry = $f->NewQuery();
        $qry->GenerateUpdateQuery("roles", $values, array("ID"=>$id));
        if ($rs = $qry->Exec())
        {
            $ret = Roles::SaveProfile($id, $form);			
            if ($ret)
            {
                    $f->Log("APPL", "Roles", "Update", $id);						
            }
            return $ret;
        }
  }
  static function GetRole($id)
  {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Looking for role id " . $id);
		$arrList = array();
		$f = Waf::Singleton();
		$qry = $f->NewQuery("SELECT * FROM %PRE%roles WHERE ID = @ID;");
		$qry->SetParam("ID", $id);
		$retid = $qry->Exec();
		$role = mysql_fetch_assoc($retid);
		$arrList['name'] = $role['name'];
		$arrList['sequence'] = $role['sequence'];
		$arrList['ID'] = $role['ID'];

      $modules = Modules::GetModules();
      foreach ($modules as $module)
      {
        $arrList = array_merge($arrList, array(('mod_'.$module['dir']) => Roles::GetRoleValue($id, $module['dir'], '')));
        $details = Modules::GetModuleSecurity($module['dir']);
        foreach($details as $line)
        { 
          $arrList = array_merge($arrList, array(($module['dir'].'_'.$line[0]) => Roles::GetRoleValue($id, $module['dir'], $line[0])));
        }
      }
    return $arrList;		
  }
  static function InsertRoleLine($roleID, $module, $sub, $value)
  {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Inserting role line " . $module . "/" . $sub);
		$f = Waf::Singleton();
		$qry = $f->NewQuery("REPLACE INTO %PRE%roledetails (roleID, module, sub, value) VALUES (@roleID, @module, @sub, @value)");
		$qry->SetParam("roleID", $roleID);
		$qry->SetParam("module", $module);
		$qry->SetParam("sub", $sub);
		$qry->SetParam("value", $value);
		$retid = $qry->Exec();
  }
  static function GetRoleValue($roleID, $module, $sub)
  {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Checking role value " . $module . "/" . $sub . "in role id" . $roleID);
		$f = Waf::Singleton();
		$qry = $f->NewQuery("SELECT value from %PRE%roledetails WHERE roleID = @roleID AND module = @module AND sub = @sub");
		$qry->SetParam("roleID", $roleID);
		$qry->SetParam("module", $module);
		$qry->SetParam("sub", $sub);
		$retid = $qry->Exec();
		if($line = mysql_fetch_assoc($retid))
		{  
			return $line['value'];
		}else {
      return 0;
    }
  }

  static function SaveProfile($roleID, $form)
  {
        $logger = Logger::getLogger("ApplicationCore.Classes.Roles");
        $logger->debug("Saving role profile id " . $roleID);
      $modules = Modules::GetModules();
      foreach ($modules as $module)
      {
		if (!isset($form['mod_'.$module['dir']]))
			$form['mod_'.$module['dir']] = 0;
			if ($form['mod_'.$module['dir']]==1)
			{
			  Roles::InsertRoleLine($roleID, $module['dir'], '', 1);
			} else {
			  Roles::InsertRoleLine($roleID, $module['dir'], '', 0);        
			}
			$details = Modules::GetModuleSecurity($module['dir']);
			foreach($details as $line)
			{
			  
			  if (isset($form[$module['dir'].'_'.$line[0]])&&$form[$module['dir'].'_'.$line[0]]==1)
			  {
				Roles::InsertRoleLine($roleID, $module['dir'], $line[0], 1);
			  } else {
				Roles::InsertRoleLine($roleID, $module['dir'], $line[0], 0);       
			  }
			}
      }
      return true;
  }
}
?>