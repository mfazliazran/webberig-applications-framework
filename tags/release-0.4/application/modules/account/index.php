<?php
I18n::BindDomain("ModuleAccount");
if (!$p)
{

    // Setup Top menu	
    //$this->activeTopMenu = "";

    $this->LoadLibrary("waf_forms");
    $form = new Waf_Form("frmChangePass");


    $input = $form->CreateInput("Password", "old");
    $input->label = _("Old password");

    $input = $form->CreateInput("Password", "new");
    $input->label = _("New password");

    $input = $form->CreateInput("Password", "new2");
    $input->label = _("Repeat new password");

    //Create validators
    ///////////////////////////////////////////////
    $validator = $form->CreateValidator("Required", "old");
    $validator->message = _("Old password is required");

    $validator = $form->CreateValidator("Required", "new");
    $validator->message = _("New password is required");

    $validator = $form->CreateValidator("Required", "new2");
    $validator->message = _("Please repeat the password");

     $validator = $form->CreateValidator("Equal", "new");
    $validator->compareWith = "new2";
    $validator->message = _("Passwords are not equal");

    $validator = $form->CreateValidator("Password", "new");
    $validator->message = _("Password isn't strong enough");
    
    $form->confirmation = _("Password has been modified.");

    $form->ProcessForm();
    if($form->isSent())
    {
        if ($form->isValid)
        {
            $details = $form->ToArray();
            Users::ChangePassword($_SESSION['userID'], $details['new']);
        }

    }
} else {
    $form->ShowErrors();
?>
<p>
<a href="account/linkGoogle" class="button"><?php echo _("Link with Google");?></a>
</p>

<form class="wf" method="post" id="frmRole">
	<div class="caption"><?php echo _("Change password");?></div>
	<fieldset>
<?php
	$form->Show("old");
	$form->Show("new");
	$form->Show("new2");
?>
	</fieldset>

	<div class="actions">
<input type="submit" value="<?php echo _("Change");?>" />

	</div>
</form>
<?php
} 
?>