<?php
class Waf_Input_Checkbox extends Waf_Input
{
	
	public function Output()
	{
?>
<div<?php echo $this->createHTMLParameter("class", "input-container " . $this->class);?>>
	<label<?php echo $this->createHTMLParameter("for", $this->name);?>><?php echo $this->label;?>:</label>
	<input class="checkbox<?php echo $this->hasTooltip() ? ' inputTooltip' : '';?>" type="checkbox"<?php echo ($this->value == 1 ? $this->createHTMLParameter("checked", "checked") : "") . $this->createHTMLParameter("name", $this->name).$this->createHTMLParameter("value", 1);?> /><br />
	<?php
		$this->createTooltip();
	?>
</div>
<?php		
	}
	
}

?>