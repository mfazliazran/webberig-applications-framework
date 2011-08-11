<?php
if (!$p)
{
	$this->activeTopMenu = "settings";
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
	$form = new Waf_Form("frmUser");

	// Create inputs
	$input = $form->CreateInput("Text", "username");
	$input->label = "Gebruikersnaam";
	$input->tooltip = 'De gebruikersnaam moet uniek zijn.';
	
	$input = $form->CreateInput("Password", "password");
	$input->label = "Paswoord";
	$input->tooltip = 'Het paswoord moet zowel numerieke als alfanumerieke karakters bevatten, en minstens 6 tekens lang zijn.';
	
	if ($mode=="add")
	{
		$input = $form->CreateInput("Password", "password2");
		$input->label = "Herhaal paswoord";
	}
	
	$input = $form->CreateInput("Text", "fullname");
	$input->label = "Volledige naam";
	
	$input = $form->CreateInput("Select", "role");
	$input->label = "Rol";
	$input->emptyOption = false;
	$roles = Roles::GetList();
	while ($r = mysql_fetch_assoc($roles))
	{
		$input->AddOption($r['id'], $r['name']);
	}
	
	// Validators
	$validator = $form->CreateValidator("Required", "username");
    $validator->message = "De gebruikersnaam is verplicht";
	$validator = $form->CreateValidator("Required", "fullname");
    $validator->message = "De volledige naam is verplicht";
	$validator = $form->CreateValidator("Required", "role");
    $validator->message = "De rol is verplicht";
	if ($mode == "add")
	{
            $form->confirmation = "De nieuwe gebruiker is aangemaakt.";
        //Paswoord is verplicht voor nieuwe gebruiker...
		$validator = $form->CreateValidator("Password", "password");
		$validator->message = "Het paswoord is niet sterk genoeg";
		$validator = $form->CreateValidator("Required", "password2");
		$validator->message = "U moet het paswoord herhalen";
		$validator = $form->CreateValidator("Equal", "password");
		$validator->message = "Paswoorden komen niet overeen";
		$validator->compareWith = "password2";
	} else {
            $form->confirmation = "De nieuwe gebruiker is gewijzigd.";
        }

	$form->ProcessForm();
	if($form->isSent())
	{
		if ($form->isValid)
		{

			switch($mode)
			{
				case "edit":
					if (!Users::Update($_GET['value'], $form->ToArray()))
					{
						$form->AddError("Er heeft zich een ongekende fout voorgedaan");
					}
					break;
				case "add":
					if (!Users::Insert($form->ToArray()))
					{
						$form->AddError("Er heeft zich een ongekende fout voorgedaan");
					}
					break;
			}
		
			//take action
			if ($form->isValid)
			{
				$this->DoRedirect("users");
			}
		}
	} else
	{
		// SET $form
		if ($mode == "edit")
		{
			//look up user
			if (!$item = Users::GetUserByID($_GET['value']))
			{
				$this->DoRedirect("users");
			}
			$form->SetValues($item);
		} 
		else 
		{
		}
	}
} else {
	$form->ShowErrors();
?>
<form method="post" id="frmUser" class="wf">
<div class="caption">Gebruiker</div>
<fieldset>
<?php
	$form->Show("username");
	$form->Show("password");

	if ($mode=="add")
	{
		$form->Show("password2");
	}
	$form->Show("fullname");
	$form->Show("role");
?>
</fieldset>
<div class="actions">
<?php
if ($mode=="edit")
{
?>
<a class="button delete" onclick="$('#dialog-confirm').dialog('open');">Verwijderen</a>
<?php
}
?>
<input type="submit" value="Opslaan" /></div>
</div>
</form>
<?php
	if ($mode=="edit")
	{
?>
<div id="dialog-confirm" title="Gebruiker verwijderen">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Deze gebruiker zal worden verwijderd! <br />Bent u zeker?</p>
</div>
	<script>
		$(document).ready(function(){
		$( "#dialog-confirm" ).dialog({
			autoOpen: false,
			resizable: false,
			draggable: false,
			height: 140,
			modal: true,
			buttons: {
				"Verwijderen": function() {
					Redirect("users/delete/<?php echo $_GET['value'];?>");
				},
				"Annuleren": function() {
					$( this ).dialog( "close" );
				}
			}
		});
		});
	</script>
<?php
	}
}
?>