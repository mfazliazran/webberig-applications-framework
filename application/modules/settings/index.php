<?php
if (!$p)
{
	// Define submenu
	$this->subMenu = ViewMaster::Create("SubMenu");
	$this->subMenu->addSettings();
    

	// Setup Top menu	
	$this->activeTopMenu = "settings";

	$this->LoadLibrary("waf_forms");
	$form = new Waf_Form("frmSettings");
	
	$input = $form->CreateInput("Text", "emailto");
	$input->label = "E-mail to";
	$input = $form->CreateInput("Text", "emailfrom");
	$input->label = "E-mail from";
	$form->confirmation = _("Settings saved");
	
	$form->ProcessForm();
	if($form->isSent())
	{
		if ($form->isValid)
		{
			if (!Settings::Save($form->ToArray()))
			{
				$form->AddError(_("An unknown error occured"));
			}
		}
		
	} else
	{
		$form->SetValues(Settings::Load());
		//look up user
		//$form->SetValues($item);
	}

} else {
?>
<form method="post" id="frmRole" class="wf">
	<div class="caption"><?php echo _("Contact forms");?></div>
	<fieldset>
<?php
	$form->Show("emailto");
	$form->Show("emailfrom");
?>
	</fieldset>

	<div class="actions">
<input type="submit" value="<?php echo _("Save");?>" />

	</div>
</form>
<?php
} 
?>