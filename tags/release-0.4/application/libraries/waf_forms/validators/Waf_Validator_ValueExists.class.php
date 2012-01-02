<?php
class Waf_Validator_ValueExists extends Waf_Validator
{
	public function __construct(&$form, $field)
	{
		parent::__construct($form, $field);
		$this->form->inputs[$field]->class = "required";
	}
	public function Validate()
	{
            if ($this->table == null)
            {
                return false;
            }
            
            $value = $this->form->inputs[$this->field]->value;
            
            $f = Waf::Singleton();
            if (!empty ($this->ignoreField))
            {
                $sql = "SELECT count(*) FROM %PRE%". $this->table ." WHERE ". $this->field ."  = @value AND ". $this->ignoreField ." <> @ignoreValue";
            } else {
                $sql = "SELECT count(*) FROM %PRE%" .$this->table ." WHERE ". $this->field ." = @value";
            }
            $qry = $f->NewQuery($sql);
            $qry->setParam("field", $this->field);
            $qry->setParam("value", $value);
            if (!empty ($this->ignoreField))
            {
                $qry->setParam("ignoreField", $this->ignoreField);
                $qry->setParam("ignoreValue", $this->ignoreValue);
            } 
            $ret = $qry->Exec();
            $records = mysql_fetch_row($ret);
            if ($records[0]==0)
            {
                return true;
            } else {
                return false;
            }
	}
        
        public $table;
        public $ignoreField;
        public $ignoreValue;
}
?>