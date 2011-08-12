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
	$validator->second_field = "new2";
	$validator->message = "Nieuwe wachtwoorden zijn niet gelijk";

	$validator = $form->CreateValidator("Required", "old");
	$validator->message = "Oud wachtwoord is verplicht";

	$validator = $form->CreateValidator("Required", "new");
	$validator->message = "Nieuw wachtwoord is verplicht";

	$validator = $form->CreateValidator("Required", "new2");
	$validator->message = "U moet het wachtwoord herhalen";


	/////////////////////////////////////////////////////
	// 2. Handling
	/////////////////////////////////////////////////////
	$form->ProcessForm();
	
	if ($form->isProcessed())
	{
		Users::ChangePassword($form);
		$this->DoRedirect(".");
	}
	/*
	if (count($_POST)>0)
	{
		$errors = array();
		$form = $_POST;

		//VALIDATION
		if(Validator::Required($form['old'], &$errors, "Gelieve uw oud wachtwoord in te vullen"))
		{
			if (!Security::Login($_SESSION['user'], $form['old']))
			{
				$errors[count($errors)] = "Het wachtwoord dat u heeft opgegeven is niet juist";
			}
		}

		Validator::Required($form['new'], &$errors, "Nieuw wachtwoord is verplicht");
		Validator::Required($form['new2'], &$errors, "Nieuw wachtwoord is verplicht");
		Validator::IsEqual($form['new'], $form['new2'], &$errors, "Nieuwe wachtwoorden zijn niet gelijk");

		if (count($errors)==0)
		{
			//VALIDATED
			if (!Users::Changepassword($_SESSION['userID'], $form['new']))
			{
				$errors[0] = "Er heeft zich een ongekende fout voorgedaan;";			
			} else
			{
				header("location: home.html");
			}
			
		}				
	}
	*/
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