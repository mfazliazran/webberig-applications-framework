<?php
class Settings
{
    static function Load()
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Settings");
        $logger->debug("Loading settings");
		$f = Waf::Singleton();
        if (!isset($f->Settings))
        {
            $qry = $f->NewQuery("SELECT * FROM %PRE%settings");
            $rs = $qry->Exec();
            $data = array();
            
            while ($row = mysql_fetch_assoc($rs))
            {
                $data[$row['name']] = $row['value'];
            }
            $f->Settings = $data;
        }
        return $f->Settings;
    }
    
    static function Save($form)
    {
        $logger = Logger::getLogger("ApplicationCore.Classes.Settings");
        $logger->debug("Saving settings");
		$f = Waf::Singleton();
        foreach ($form as $key => $value)
        {
            $qry = $f->NewQuery("SELECT * FROM %PRE%settings WHERE name = @name");
    		$qry->SetParam("name", $key);
            $retid = $qry->Exec();
            if (!$retid)
            {
                return false;
            }
            
            $values = array();
            $values['value'] = $value;
       		$qry = $f->NewQuery();
            if (mysql_num_rows($retid))
            {
                $qry->GenerateUpdateQuery("settings", $values, array("name" => $key));
            } else {
                $values['name'] = $key;
                $qry->GenerateInsertQuery("settings", $values);
            }
            $rs = $qry->Exec();
            if (!$rs)
            {
                return false;
            }
        }
        return true;
    }
}
?>