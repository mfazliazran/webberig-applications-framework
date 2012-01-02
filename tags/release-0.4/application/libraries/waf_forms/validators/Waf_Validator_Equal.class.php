<?php
class Waf_Validator_Equal extends Waf_Validator
{
	public function __construct(&$form, $field)
	{
		parent::__construct($form, $field);
		$this->form->inputs[$field]->class = "required";
	}
	public function Validate()
	{
		if (empty($this->compareWith))
		{
			throw new Exception('compareWith is empty');
		}
		if (!isset($this->form->inputs[$this->compareWith]))
		{
			throw new Exception('compareWith field does not exist');			
		}
		$value1 = $this->form->inputs[$this->field]->value;
		$value2 = $this->form->inputs[$this->compareWith]->value;
		return $value1 == $value2;

	}
	public $compareWith;
}
?>