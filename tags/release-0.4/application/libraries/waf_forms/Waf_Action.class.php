<?php
class Waf_Action
{
	protected $form;
	
	public function __construct(&$form)
	{
		$this->form = &$form;
	}
}
?>