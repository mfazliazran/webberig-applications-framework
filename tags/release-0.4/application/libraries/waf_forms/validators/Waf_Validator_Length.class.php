<?php
class Waf_Validator_Length extends Waf_Validator
{
	public $minLength = 0;
	public $maxLength = 0;
	
	public function __construct(&$form, $field)
	{
		parent::__construct($form, $field);
//		$this->form->inputs[$field]->class = "required";
	}
	public function Validate()
	{
		$value = $this->form->inputs[$this->field]->value;
		$length = strlen($value);
		
		if ($this->minLength > $length)
		{
			return false;
		}
		if ($this->maxLength > 0)
		{
			if ($length > $this->maxLength)
			{
				return false;
			}
		}
		return true;
	}
}
?>