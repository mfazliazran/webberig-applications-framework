<?php
class Waf_Input_Radio extends Waf_Input
{
	private $options = array();
	
	public function AddOption($key, $label = null)
	{
		if ($label)
		{
			$this->options[$key] = $label;
		} else {
			$this->options[] = $key;			
		}
	}
	public function Output()
	{
?>
<div<?php echo $this->createHTMLParameter("class", "input-container " . $this->class);?>>
	<label<?php echo $this->createHTMLParameter("for", $this->name);?>><?php echo $this->label;?></label>
	<div class="radiogroup">
<?php
		foreach($this->options as $key => $value)
		{
?>
		<input class="radio" type="radio"<?php echo $this->createHTMLParameter("name", $this->name).(($key==$this->value && isset($this->value)) ? $this->createHTMLParameter("checked", "checked") : "") . $this->createHTMLParameter("value", $key);?> /><?php echo $value;?><br />        
<?php
		}
?>
	</div>
	<br />

</div>
<?php		
	}
	
}

?>