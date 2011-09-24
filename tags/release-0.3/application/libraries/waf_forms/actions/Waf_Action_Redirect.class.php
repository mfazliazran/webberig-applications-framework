<?php
class Waf_Action_Redirect extends Waf_Action
{
	public $Url;
	public function Execute()
	{
		$f = Waf::Singleton();
		$f->DoRedirect($this->Url);
	}
	
}

?>