<?php
if (!$p)
{
    $msg = "";
try {
    $openid = new LightOpenID;
    $openid->required = array('contact/email','contact/firstname','contact/lastname');
    if(!$openid->mode) {
        $openid->identity = 'https://www.google.com/accounts/o8/id';
        $this->DoRedirect($openid->authUrl(), false, true);
    } elseif($openid->mode == 'cancel') {
        $msg = "cancelled";
    } else {
        if ($openid->validate())
        {
            $id = $openid->identity;
            if ($id)
            {
                Users::RegisterGoogleIdentity($id);
                $msg = "linked";
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
    echo $e->getMessage();
}

} else {
    switch ($msg)
    {
        case "error":
?>
<?php echo _("An unknown error occured");?>. <a href="account"><?php echo _("Return to the account screen");?></a>.
<?php
            break;
        case "linked":
?>
<?php echo _("Your Google account has been linked. You can now use the Google button to log in");?>! <a href="account"><?php echo _("Return to the account screen");?></a>
<?php
            break;
        case "cancelled":
?>
<?php echo _("De operation has been cancelled");?>. <a href="account"><?php echo _("Return to the account screen");?></a>.
<?php
            break;
    }
}
?>