<?php
if (!$p)
{
    $header = new Header("Paswoord wijzigen","site/images/icons/changepassword.png") ;
    $this->LoadLibrary("waf_forms");

    $form = new Waf_Form();
    $form->buttonLabel = "Verzenden";

    /////////////////////////////////////////////////////
    // 1. Definition
    /////////////////////////////////////////////////////
    //Create inputs
    ///////////////////////////////////////////////

    $input = $form->CreateInput("Password", "old");
    $input->label = "Oud wachtwoord";

    $input = $form->CreateInput("Password", "new");
    $input->label = "Nieuw wachtwoord";

    $input = $form->CreateInput("Password", "new2");
    $input->label = "Herhaal nieuw wachtwoord";

    //Create validators
    ///////////////////////////////////////////////
    $validator = $form->CreateValidator("Equal", "new");
    $validator->compareWith = "new2";
    $validator->message = "Paswoorden komen niet overeen";

    $validator = $form->CreateValidator("Required", "old");
    $validator->message = "Oud wachtwoord is verplicht";

    $validator = $form->CreateValidator("Required", "new");
    $validator->message = "Nieuw wachtwoord is verplicht";

    $validator = $form->CreateValidator("Required", "new2");
    $validator->message = "U moet het wachtwoord herhalen";

    $validator = $form->CreateValidator("Password", "new");
    $validator->message = "Het paswoord is niet sterk genoeg";


    /////////////////////////////////////////////////////
    // 2. Handling
    /////////////////////////////////////////////////////
    $form->ProcessForm();

    if ($form->isProcessed())
    {
        print_r($form->ToArray());
        print_r($_SESSION);
        die();
        Users::ChangePassword($form);
        $this->DoRedirect(".");
    }
} else {

    /////////////////////////////////////////////////////
    // 3. Show errors if any!
    /////////////////////////////////////////////////////
    $form->ShowErrors();

    /////////////////////////////////////////////////////
    // 4. Show form
    /////////////////////////////////////////////////////
    $form->ShowForm();

}
?>