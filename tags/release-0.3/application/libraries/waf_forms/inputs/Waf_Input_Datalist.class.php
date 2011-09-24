<?php
class Waf_Input_Select extends Waf_Input
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
    <input type="text" <?php echo $this->createHTMLParameter("list", $this->name).$this->createHTMLParameter("name", $this->name). ($this->hasTooltip() ? $this->createHTMLParameter("class", "inputTooltip") : '');?> />
	<datalist <?php echo $this->createHTMLParameter("id", $this->name);?>>
<?php

		foreach($this->options as $key => $value)
		{
?>
		<option<?php echo $this->createHTMLParameter("value", $value);?>>
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