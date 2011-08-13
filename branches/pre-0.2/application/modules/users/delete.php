<?php
if (isset($_GET['value'])&&is_numeric($_GET['value']))
{
    Users::Delete($_GET['value']);
    Messenger::Add("confirm", "De gebruiker is met succes verwijderd.");
}

$this->DoRedirect("users");
?>