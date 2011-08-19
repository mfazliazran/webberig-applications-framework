<?php
class Waf_Input_Hidden extends Waf_Input
{
	
	public function Output()
	{
?>
	<input type="hidden"<?php echo $this->createHTMLParameter("name", $this->name).$this->createHTMLParameter("value", $this->value);?> /><br />
<?php		
	}
	
}

?>