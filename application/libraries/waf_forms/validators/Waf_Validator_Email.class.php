<?php
class Waf_Validator_Email extends Waf_Validator
{
	public function __construct(&$form, $field)
	{
		parent::__construct($form, $field);
		$this->form->inputs[$field]->class = "required";
	}
	public function Validate()
	{
		if (empty($this->form->inputs[$this->field]->value))
			return true;
		$email = $this->form->inputs[$this->field]->value;
		
		// Taken from http://www.linuxjournal.com/article/9585?page=0,3
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex)
		{
		   $isValid = false;
		}
		else
		{
		   $domain = substr($email, $atIndex+1);
		   $local = substr($email, 0, $atIndex);
		   $localLen = strlen($local);
		   $domainLen = strlen($domain);
		   if ($localLen < 1 || $localLen > 64)
		   {
			  // local part length exceeded
			  $isValid = false;
		   }
		   else if ($domainLen < 1 || $domainLen > 255)
		   {
			  // domain part length exceeded
			  $isValid = false;
		   }
		   else if ($local[0] == '.' || $local[$localLen-1] == '.')
		   {
			  // local part starts or ends with '.'
			  $isValid = false;
		   }
		   else if (preg_match('/\\.\\./', $local))
		   {
			  // local part has two consecutive dots
			  $isValid = false;
		   }
		   else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		   {
			  // character not valid in domain part
			  $isValid = false;
		   }
		   else if (preg_match('/\\.\\./', $domain))
		   {
			  // domain part has two consecutive dots
			  $isValid = false;
		   }
		   else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',  str_replace("\\\\","",$local)))
		   {
			  // character not valid in local part unless 
			  // local part is quoted
			  if (!preg_match('/^"(\\\\"|[^"])+"$/',
				  str_replace("\\\\","",$local)))
			  {
				 $isValid = false;
			  }
		   }
			if (function_exists('checkdnsrr'))
			{ 
				if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
				   {
					  // domain not found in DNS
					  $isValid = false;
				   }
				}
			}
			return $isValid;		
		}
	}
}
?>