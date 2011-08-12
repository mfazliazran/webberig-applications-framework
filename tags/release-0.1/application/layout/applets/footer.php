<footer>
    <div class="left">
    Copyright <?php echo date("Y");?>
    </div>
    <?php
    if (isset($_SESSION['userID']))
    {
        $user = Users::GetUserByID($_SESSION['userID']);
?>
    <div class="right">
        <a href="account"><?php echo $user['fullname'];?></a> - <a href="account/logout">Logout</a>
    </div>
<?php
    }
    ?>
</footer>
<script>
    $(document).ready(function(){
        $("#disclaimer, #helpmodal").dialog({
            autoOpen: false,
            resizable: false,
            draggable: false,
//				height: 140,
            modal: true,
            buttons: {
                OK: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    });
</script>