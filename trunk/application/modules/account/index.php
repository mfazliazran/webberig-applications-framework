<?php
if (!$p)
{

    // Setup Top menu	
    //$this->activeTopMenu = "";

    $this->LoadLibrary("waf_forms");
    $form = new Waf_Form("frmChangePass");


    $input = $form->CreateInput("Password", "old");
    $input->label = "Oud wachtwoord";

    $input = $form->CreateInput("Password", "new");
    $input->label = "Nieuw wachtwoord";

    $input = $form->CreateInput("Password", "new2");
    $input->label = "Herhaal nieuw wachtwoord";

    //Create validators
    ///////////////////////////////////////////////
    $validator = $form->CreateValidator("Required", "old");
    $validator->message = "Oud wachtwoord is verplicht";

    $validator = $form->CreateValidator("Required", "new");
    $validator->message = "Nieuw wachtwoord is verplicht";

    $validator = $form->CreateValidator("Required", "new2");
    $validator->message = "U moet het wachtwoord herhalen";

    $validator = $form->CreateValidator("Equal", "new");
    $validator->compareWith = "new2";
    $validator->message = "Nieuwe wachtwoorden zijn niet gelijk";

    $validator = $form->CreateValidator("Password", "new");
    $validator->message = "Het paswoord is niet sterk genoeg";
    
    $form->confirmation = "Uw paswoord is gewijzigd.";

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
<a href="account/linkGoogle" class="button">Google account koppelen</a>
</p>

<form class="wf" method="post" id="frmRole">
	<div class="caption">Wachtwoord wijzigen</div>
	<fieldset>
<?php
	$form->Show("old");
	$form->Show("new");
	$form->Show("new2");
?>
	</fieldset>

	<div class="actions">
<input type="submit" value="Wijzigen" />

	</div>
</form>
<?php
} 
?>