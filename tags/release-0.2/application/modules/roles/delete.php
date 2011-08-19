<?php
if (!$p)
{
    if (isset($_GET['value'])&&is_numeric($_GET['value']))
    {
        Roles::Delete($_GET['value']);	
        Messenger::Add("confirm", "De rol is met succes verwijderd.");
    }
}
$this->DoRedirect("roles");
?>