<?php
// $include_mode = pre / head / body / footer;
switch ($include_mode)
{
	case 'head':
        Utility::JS("application/libraries/jquery/jquery.min.js");
        Utility::JS("application/libraries/jquery/jquery-ui.min.js");
        Utility::JS("application/libraries/jquery/jquery.ui.nestedSortable.js");
        Utility::CSS("application/libraries/jquery/jquery-ui-1.8.12.custom.css");
	break;
}
?>