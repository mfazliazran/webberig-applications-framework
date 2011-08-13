<?php
class Waf_Validator_Required extends Waf_Validator
{
	public function __construct(&$form, $field)
	{
		parent::__construct($form, $field);
		$this->form->inputs[$field]->class = "required";
	}
	public function Validate()
	{
		return (strlen($this->form->inputs[$this->field]->value)>0);
	}
}
?>