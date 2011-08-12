<?php
class Waf_Input_Select extends Waf_Input
{
	private $options = array();
	public $emptyOption = true;
	
	public function AddOption($key, $label = null)
	{
		if ($label)
		{
			$this->options[$key] = $label;
		} else {
			$this->options[] = $key;			
		}
	}
	public function AddRecordSet($set, $key, $value)
	{
		if ($set)
		{
			while ($row = mysql_fetch_assoc($set))
			{
				$this->AddOption($row[$key], $row[$value]);
			}
		}
	}
	public function Output()
	{
		
?>
<div<?php echo $this->createHTMLParameter("class", "input-container " . $this->class);?>>
	<label<?php echo $this->createHTMLParameter("for", $this->name);?>><?php echo $this->label;?></label>
	<select<?php echo $this->createHTMLParameter("name", $this->name). ($this->hasTooltip() ? $this->createHTMLParameter("class", "inputTooltip") : '');?>>
<?php
		if($this->emptyOption)
		{
?>
		<option<?php echo (empty($this->value) ? $this->createHTMLParameter("selected", "selected") : "") . $this->createHTMLParameter("value", "");?>></option>
<?php
		}
		foreach($this->options as $key => $value)
		{
?>
		<option<?php echo (($key==$this->value && isset($this->value)) ? $this->createHTMLParameter("selected", "selected") : "") . $this->createHTMLParameter("value", $key);?>><?php echo $value;?></option>
<?php
		}
?>
	</select><br />
	<?php
		$this->createTooltip();
	?>
</div>
<?php		
	}
	
}

?>