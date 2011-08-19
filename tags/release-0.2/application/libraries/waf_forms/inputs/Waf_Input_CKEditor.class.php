<?php
class Waf_Input_CKEditor extends Waf_Input
{
	
	public function Output()
	{
?>
<div<?php echo $this->createHTMLParameter("class", "input-container " . $this->class);?>>
	<textarea<?php echo $this->createHTMLParameter("id", "ckeditor_".$this->name).$this->createHTMLParameter("name", $this->name).$this->createHTMLParameter("class", "wysiwyg");?> /><?php echo $this->value;?></textarea>
</div>
<?php
		include("application/libraries/ckeditor/config.php");
	}
	
}

?>