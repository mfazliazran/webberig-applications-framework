<?php
class Waf_Validator_Password extends Waf_Validator
{
	public $Strength = 2;
	
	public function __construct(&$form, $field)
	{
		parent::__construct($form, $field);
	}
	public function Validate()
	{
		if ($this->CheckPasswordStrength() >= $this->Strength)
		{
			return true;
		} else {
			return false;
		}
	}
	
	private function CheckPasswordStrength()
	{
		$value = $this->form->inputs[$this->field]->value;
		$strength = 0;
		$patterns = array('#[a-z]#','#[A-Z]#','#[0-9]#','/[¬!"£$%^&*()`{}\[\]:@~;\'#<>?,.\/\\-=_+\|]/');
		foreach($patterns as $pattern)
		{
			if(preg_match($pattern,$value,$matches))
			{
				$strength++;
			}
		}
		
		return $strength;
		
		// 1 - weak
		// 2 - not weak
		// 3 - acceptable
		// 4 - strong
	} 
}
?>