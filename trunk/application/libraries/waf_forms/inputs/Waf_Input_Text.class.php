<?php
class Waf_Input_Text extends Waf_Input
{
	
	public function Output()
	{
?>
<div<?php echo $this->createHTMLParameter("class", "input-container " . $this->class);?>>
	<label<?php echo $this->createHTMLParameter("for", $this->name);?>><?php echo $this->label;?>:</label>
	<input type="text"<?php
    echo $this->createHTMLParameter("name", $this->name);
    echo $this->createHTMLParameter("value", $this->value);
    echo ($this->hasTooltip() ? $this->createHTMLParameter("class", "inputTooltip") : '') ;
    echo (isset($this->maxLength) ? $this->createHTMLParameter("maxlength", $this->maxLength) : '');
?> /><br />
	<?php
		$this->createTooltip();
	?>
</div>
<?php		
	}
}
?>