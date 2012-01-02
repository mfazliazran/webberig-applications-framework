<?php
class Waf_Input_TextArea extends Waf_Input
{
	
	public function Output()
	{
?>
<div<?php echo $this->createHTMLParameter("class", "input-container " . $this->class);?>>
	<label<?php echo $this->createHTMLParameter("for", $this->name);?>><?php echo $this->label;?>:</label>
	<textarea<?php echo $this->createHTMLParameter("name", $this->name). ($this->hasTooltip() ? $this->createHTMLParameter("class", "inputTooltip") : '');?> /><?php echo $this->value;?></textarea><br />
	<?php
		$this->createTooltip();
	?>
</div>
<?php		
	}
	
}

?>