<?php
if (isset($_GET['value'])&&is_numeric($_GET['value']))
{
    Users::Delete($_GET['value']);
    Messenger::Add("confirm", _("User has been deleted successfully"));
}

$this->DoRedirect("users");
?>