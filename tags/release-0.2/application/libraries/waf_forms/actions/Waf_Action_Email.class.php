<?php
class Waf_Action_Email extends Waf_Action
{
	public $To;
	public $From;
	public $Subject;
	public function Execute()
	{
		$header = "From: ".$this->From."\n";
        $body = "";
        foreach($this->form->inputs as $input)
        {
            $body .= $input->label . ": " . $input->value;
        }
        mail($this->To, $this->Subject, $body, $header);
	}
	
}

?>