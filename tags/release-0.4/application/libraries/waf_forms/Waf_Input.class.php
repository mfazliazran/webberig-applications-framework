<?php
class Waf_Input
{
	public $name;
	public $value;
	public $class;
	public $label;
	public $tooltip;
	
	public function __construct($name)
	{
		$this->name = $name;
	}
	
	protected function createHTMLParameter($name, $value)
	{
		if (strlen($value)>0)
		{
			echo " " . $name . "=\"" . $value . "\"";
		}
	}
	public function createTooltip()
	{
		if ($this->hasTooltip())
		{
?>
<div class="tooltip">
	<h4><?php echo $this->label;?></h4>
	<?php echo $this->tooltip;?>
</div>
<?php
		}
	}
	public function hasTooltip()
	{
		return strlen($this->tooltip);
	}
}
?>