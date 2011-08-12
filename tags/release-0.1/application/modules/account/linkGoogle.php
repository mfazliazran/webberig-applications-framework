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
Er is een fout opgetreden. <a href="account">Keer terug naar het account-scherm</a>.
<?php
            break;
        case "linked":
?>
Uw Google account is met succes gekoppeld. U kan vanaf nu inloggen door in het login scherm op de Google knop te klikken! <a href="account">Keer terug naar het account-scherm</a>
<?php
            break;
        case "cancelled":
?>
De bewerking is geannuleerd. <a href="account">Keer terug naar het account-scherm</a>.
<?php
            break;
    }
}
?>