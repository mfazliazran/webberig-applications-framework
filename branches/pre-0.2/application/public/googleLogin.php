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
Er is een fout opgetreden. <a href=".">Keer terug naar het login-scherm</a>.
<?php
            break;
        case "notlinked":
?>
Uw Google account is niet gekoppeld aan een van de gebruikers in dit programma. Om dit op te lossen gaat u als volgt te werk:
<ul>
    <li>Meld u aan via uw gebruikersnaam en paswoord (<a href=".">Keer terug naar het loginscherm</a>)</li>
    <li>Ga vervolgens naar uw account-gegevens</li>
    <li>Klik op de knop &quot;Google-account koppelen&quot;</li>
</ul>
<?php
            break;
        case "cancelled":
?>
De bewerking is geannuleerd. <a href=".">Keer terug naar het login-scherm</a>.
<?php
            break;
    }
}
?>
