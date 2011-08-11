<?php
class Messenger
{
    static function Add($type, $message)
    {
        if (!isset($_SESSION['messenger']))
            $_SESSION['messenger'] = array();
        if (!isset($_SESSION['messenger'][$type]))
            $_SESSION['messenger'][$type] = array();
        if (in_array($message, $_SESSION['messenger'][$type]))
            return;
        $_SESSION['messenger'][$type][] = $message;
    }
    
    static function Output()
    {
        if (!isset($_SESSION['messenger']) || !is_array($_SESSION['messenger']))
        {
            return;
        }
        $hasOutput = false;
        foreach ($_SESSION['messenger'] as $class => $messages)
        {
/*
            if ((!is_array($messages)) || count($messages==0))
            {
                    continue;
            }
*/
            $hasOutput = true;
?>
<div class="messenger <?php echo $class;?>">
<ul>
<?php
            foreach ($messages as $msg)
            {
?>
	<li><?php echo $msg;?></li>
<?php
            }
?>
</ul>
</div>
<?php
        }
        unset($_SESSION['messenger']);
        if($hasOutput)
        {
                // Output Javascript
        }
    }
}
?>