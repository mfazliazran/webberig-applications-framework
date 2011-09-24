<?php
$topmenu = ViewMaster::Create("topMenu");
$topmenu->ignoreList = array("users", "roles", "account");
$topmenu->Output();
?>