<?php
// $include_mode = pre / head / body / footer;
switch ($include_mode)
{
	case 'pre':
		require ("application/libraries/waf_forms/Waf_Form.class.php");
		require ("application/libraries/waf_forms/Waf_Input.class.php");
		require ("application/libraries/waf_forms/Waf_Validator.class.php");
		require ("application/libraries/waf_forms/Waf_Action.class.php");
	break;
	
	case 'head':
        Utility::CSS("application/libraries/waf_forms/form.css");
        Utility::CSS("application/libraries/waf_forms/qtip/jquery.qtip.css");
        Utility::JS("application/libraries/waf_forms/qtip/jquery.qtip.min.js");
        Utility::JS("application/libraries/waf_forms/scripts.js");
	break;
}
?>