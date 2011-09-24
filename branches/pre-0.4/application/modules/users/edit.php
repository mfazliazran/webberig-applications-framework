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
    $input->label = _("Username");
    $input->tooltip = _("Username must be unique") . ".";
    $input->maxLength = 50;

    $input = $form->CreateInput("Password", "password");
    $input->label = _("Password");
    $input->tooltip = _("The password must contain both numeric and alphanumeric characters, 6 characters minimum") . ".";

    if ($mode=="add")
    {
        $input = $form->CreateInput("Password", "password2");
        $input->label = _("Repeat password");
    }

    $input = $form->CreateInput("Text", "fullname");
    $input->label = _("Name");

    $input = $form->CreateInput("Select", "role");
    $input->label = _("Role");
    $input->emptyOption = false;
    $roles = Roles::GetList();
    while ($r = mysql_fetch_assoc($roles))
    {
        $input->AddOption($r['id'], $r['name']);
    }

    // Validators
    $validator = $form->CreateValidator("Required", "username");
    $validator->message = _("Username is required");
    $validator = $form->CreateValidator("Required", "fullname");
    $validator->message = _("Name is required");
    $validator = $form->CreateValidator("Required", "role");
    $validator->message = _("Roles is required");

    $validator = $form->CreateValidator("ValueExists", "username");
    $validator->message = _("The username already exists");
    $validator->table = "users";
    if ($mode == "edit")
    {
        $validator->ignoreField = "ID";
        $validator->ignoreValue = $_GET['value'];
    }

    if ($mode == "add")
    {
        $form->confirmation = _("The user has been created");
        
        //Paswoord is verplicht voor nieuwe gebruiker...
        $validator = $form->CreateValidator("Required", "password");
        $validator->message = _("New password is required");
        
        $validator = $form->CreateValidator("Required", "password2");
        $validator->message = _("You must repeat the password");
        
        $validator = $form->CreateValidator("Password", "password");
        $validator->message = _("The given password is not strong enough");
        
        $validator = $form->CreateValidator("Equal", "password");
        $validator->message = _("Passwords don't match");
        $validator->compareWith = "password2";
        
        $validator = $form->CreateValidator("Length", "password");
        $validator->message = _("Password isn't long enough");
        $validator->minLength = 6;
    } else {
        if (isset($_POST['password']) && strlen($_POST['password']))
        {
            $validator = $form->CreateValidator("Length", "password");
            $validator->message = _("Password isn't long enough");
            $validator->minLength = 6;

            $validator = $form->CreateValidator("Password", "password");
            $validator->message = _("The given password is not strong enough");
        }
        $form->confirmation = _("User has been updated");
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
                            $form->AddError(_("An unknown error occured"));
                    }
                    break;
                case "add":
                    if (!Users::Insert($form->ToArray()))
                    {
                            $form->AddError(_("An unknown error occured"));
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
<a class="button delete" onclick="$('#dialog-confirm').dialog('open');"><?php echo _("Delete");?></a>
<?php
    }
?>
<input type="submit" value="<?php echo _("Save");?>" /></div>
</div>
</form>
<?php
    if ($mode=="edit")
    {
?>
<div id="dialog-confirm" title="<?php echo _("Delete user");?>">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo _("User will be deleted");?> <br /><?php echo _("Are you sure");?>?</p>
</div>
<script>
    $(document).ready(function(){
        $( "#dialog-confirm" ).dialog({
            autoOpen: false,
            resizable: false,
            draggable: false,
            height: 160,
            width: 400,
            modal: true,
            buttons: {
                "<?php echo _("Delete");?>": function() {
                    Redirect("users/delete/<?php echo $_GET['value'];?>");
                },
                "<?php echo _("Cancel");?>": function() {
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