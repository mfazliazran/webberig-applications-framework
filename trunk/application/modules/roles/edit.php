<?php
if (!$p)
{
    $this->activeTopMenu = "settings";
    // Set MODE
    if (isset($_GET['value']))
    {
            $mode = "edit";
    } else
    {
            $mode = "add";
    }
    $this->subMenu = ViewMaster::Create("SubMenu");
    $this->subMenu->addSettings();

    $this->LoadLibrary("waf_forms");
    $form = new Waf_Form("frmSite");

    $input = $form->CreateInput("Text", "name");
    $input->label = _("Name");
    $input = $form->CreateInput("Text", "sequence");
    $input->label = _("Sequence");

    // Validators
    $validator = $form->CreateValidator("Required", "name");
    $validator->message = _("Name is required");

    if ($mode == "add")
    {
        $form->confirmation = _("New role has been created");
    } else {
        $form->confirmation = _("Roles has been updated");
    }

    $modules = Modules::GetModules();

    foreach ($modules as $module)
    {
            $input = $form->CreateInput("Checkbox", 'mod_'.$module['dir']);
            $input->label = $module['name'];
            $details = Modules::GetModuleSecurity($module['dir']);

            if (count($details))
            {
                    foreach($details as $line)
                    {
                            $input = $form->CreateInput("Text", $module['dir'].'_'.$line[0]);
                            $input->label = $module['name'];
                    }
            }
    }

    $form->ProcessForm();
    if($form->isSent())
    {
            if ($form->isValid)
            {
                    switch($mode)
                    {
                            case "edit":
                                    if (!Roles::Update($_GET['value'], $form->ToArray()))
                                    {
                                            $form->AddError(_("An unknown error occured"));
                                    }
                            break;
                            case "add":
                                    if (!Roles::Insert($form->ToArray()))
                                    {
                                            $form->AddError(_("An unknown error occured"));
                                    }
                            break;
                    }

                    //take action
                    if ($form->isValid)
                    {
                            $this->DoRedirect("roles");
                    }
            }

    } else
    {
        // SET $form
        if ($mode == "edit")
        {
                //look up user
                if (!$item = Roles::GetRole($_GET['value']))
                {
                        //record niet gevonden
                        $this->DoRedirect("roles");
                }
                $form->SetValues($item);
        } 
        else 
        {
                // default values if any
        }
    }
} else {
	
	$form->ShowErrors();
?>
<form method="post" id="frmRole" class="wf">
	<div class="caption"><?php echo _("General");?></div>
	<fieldset>
<?php
	$form->Show("name");
	$form->Show("sequence");
?>
	</fieldset>

	<div class="caption"><?php echo _("Modules");?></div>
	<fieldset>
<?php
	foreach ($modules as $module)
	{
		$form->Show('mod_'.$module['dir']);
		$details = Modules::GetModuleSecurity($module['dir']);
		if (count($details))
		{
			foreach($details as $line)
			{
				$form->Show($module['dir'].'_'.$line[0]);
			}
		}
	}
?>
	</fieldset>
	<div class="actions">
<?php
if ($mode=="edit")
{
?>
		<a class="button delete" onclick="$('#dialog-confirm').data('id', '<?php echo $_GET['value'];?>').dialog('open'); return false;" href="roles/delete/<?php echo $_GET['value'];?>">Verwijderen</a>
<?php
}
?>
<input type="submit" value="Opslaan" />
	</div>
</form>
<?php
	if ($mode=="edit")
	{
?>
	<div id="dialog-confirm" title="<?php echo _("Delete role");?>">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo _("Deleting the role will also delete all users with the given role");?>! <?php echo _("Are you sure");?>?</p>
	</div>
<?php
	}
}
?>