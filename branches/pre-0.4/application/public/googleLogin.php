<?php
if (!$p)
{
    
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
$msg = "";
try {
    $openid = new LightOpenID;
    $openid->required = array('contact/email','contact/firstname','contact/lastname');
    if(!$openid->mode) {
        $openid->identity = 'https://www.google.com/accounts/o8/id';
        $this->DoRedirect($openid->authUrl(), false, true);
        /*
        header('Location: ' . $openid->authUrl());
        die();
        */
    } elseif($openid->mode == 'cancel') {
        // Show error
        $msg = "cancelled";
    } else {
        if ($openid->validate())
        {
            $id = $openid->identity;
            if ($id)
            {
                $user = Users::GetUserByIdentity($id);
                if (!$user)
                {
                    $msg = "notlinked";
                    // Show error
                } else {
                    Security::CreateSession($user);
                    $this->DoRedirect("");
                }
            } else {
                //Show error
                $msg = "error";
            }
            
        } else {
            // Show error
            $msg = "error";
        }
    }
} catch(ErrorException $e) {
    $msg = "error";
}

} else {
    switch ($msg)
    {
        case "error":
?>
<?php echo _("An error occured");?>. <a href="."><?php echo _("Return to the login screen");?></a>.
<?php
            break;
        case "notlinked":
?>
<?php echo _("Your Google account is not linked to a user in this application. Please follow these steps");?>:
<ul>
    <li><?php echo _("Log in using your username and password");?> (<a href="."><?php echo _("Return to the login screen");?></a>)</li>
    <li><?php echo _("Go to the account page");?></li>
    <li><?php echo _("Click on the 'Link Google account' button");?></li>
</ul>
<?php
            break;
        case "cancelled":
?>
<?php echo _("You have cancelled the operation");?>. <a href="."><?php echo _("Return to the login screen");?></a>.
<?php
            break;
    }
}
?>