<?php
class Waf_Validator
{
	protected $form;
	protected $field;
	public $message;
	
	public function __construct(&$form, $field)
	{
		$this->form = &$form;
		$this->field = $field;
	}
}
?>