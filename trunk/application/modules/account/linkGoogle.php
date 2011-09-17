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
<?php echo _("Er is een fout opgetreden");?>. <a href="account"><?php echo _("Keer terug naar het account-scherm");?></a>.
<?php
            break;
        case "linked":
?>
<?php echo _("Uw Google account is met succes gekoppeld. U kan vanaf nu inloggen door in het login scherm op de Google knop te klikken");?>! <a href="account"><?php echo _("Keer terug naar het account-scherm");?></a>
<?php
            break;
        case "cancelled":
?>
<?php echo _("De bewerking is geannuleerd");?>. <a href="account"><?php echo _("Keer terug naar het account-scherm");?></a>.
<?php
            break;
    }
}
?>