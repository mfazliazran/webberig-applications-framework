<?php
if (!$p)
{
    if (isset($_GET['value'])&&is_numeric($_GET['value']))
    {
        Roles::Delete($_GET['value']);	
        Messenger::Add("confirm", _("Role has been deleted successfully"));
    }
}
$this->DoRedirect("roles");
?>