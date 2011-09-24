<?php
if (isset($_POST['username']) && isset($_POST['password']))
{
	if(Security::Login($_POST['username'], $_POST['password']))
	{
		die('OK');
	} else {
		die();
	}
}
if (isset($_GET['username']) && isset($_GET['password']))
{
	if(Security::Login($_GET['username'], $_GET['password']))
	{
		die('OK');
	} else {
		die();
	}
}
?>
<!DOCTYPE html>
<html lang="nl">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $settings["ApplicationName"];?> - Powered by Webberig.be</title>
		<base href="<?php echo $settings["basePath"];?>" />
<?php
Utility::JS("application/libraries/jquery/jquery.min.js");
Utility::JS("application/libraries/jquery/jquery-ui.min.js");
Utility::JS("application/layout/js/scripts.js");
Utility::CSS("application/layout/css/login.css");
Utility::CSS("application/libraries/jquery/jquery-ui-1.8.12.custom.css");
//Utility::CSS("application/libraries/waf_forms/form.css");
?>
		<script>
		//var isLoading = false;
		$(document).ready(function(){
			
			$( "#modal-login" ).dialog({
				autoOpen: true,
				height: 150,
				width: 480,

				closeOnEscape: false,
				draggable: false,
				resizable: false,
				beforeClose: function()
				{
					return false;
				}
			});
			$("#loginFrm_username").focus();
			$("#loading").hide();
			
			
			$("form input").keypress(function (e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
					$('#btnLogin').click();
					return false;
				} else {
					return true;
				}
		    });
            $("#btnLogin").click(function()
            {
                var formData = $("#loginFrm").serialize();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $settings["basePath"];?>',
                    data: formData,
                    success: function(data)
                    {
                        if (data=="OK")
                            LoginSuccess();
                        else
                            LoginFailed();
                    },
                    beforeSend: function() {
                        isLoading = true;
                        $("#loginFrm").hide();
                        $("#loading").fadeIn();
                        $("#btnLogin").button({disabled: true});
                    },
                    error: LoginFailed
                  });
            });
            $("#btnLogin").button();
		});
		function LoginSuccess()
		{
			location.reload(true);
		}
		function LoginFailed()
		{
			$("#loginFrm").fadeIn();
			$("#loading").hide();
			$("#btnLogin").button({disabled: false});
			$(".ui-dialog:first").effect("shake", { times:3, direction: "left", distance: 5 }, 50)
			$("#loginFrm_password").val("");
			$("#loginFrm_username").val("");
			var t = setTimeout ( '$("#loginFrm_username").focus()', 500 );
			//isLoading = false;
		}
		</script>
    <style>
        #googleButton span
        {
            padding-bottom: 0px !important;
        }
    </style>
</head>
<body>
	<div id="modal-login" title="<?php echo $settings["ApplicationName"];?> - Login">
	<div id="loading" style="width: 300px; height: 70px; float: left; border-right: dotted 1px #666; margin-right: 10px; padding-right: 5px"><img src="application/layout/images/loading.gif" /></div>
    <form id="loginFrm" style="width: 300px; float: left; border-right: dotted 1px #666; margin-right: 10px; padding-right: 5px">
      <fieldset>
        <label style="width: 115px;"><?php echo _("Username");?>:</label>
        <input name="username" id="loginFrm_username"/>
        <br />
        <label style="width: 115px;"><?php echo _("Password");?>:</label>
        <input name="password" id="loginFrm_password" type="password" />
      </fieldset>
        <label style="width: 120px;"></label>
            <input id="btnLogin" type="button" value="<?php echo _("Login");?>" />
    </form>
        <p><?php echo _("Log in using");?>:<br /><br /></p>
        <a href="googleLogin" id="googleButton" class="button"><img src="application/layout/images/google.png" alt="<?php echo _("Login with Google account");?>"/></a>
	</div>
	
</body>
</html>
<?php
die();
?>