<?php
class SubMenu extends ViewMaster
{
public function Output()
    {
        if (count($this->options) || count($this->actions))
        {
?>
<div id="submenu">
<?php
            $this->OutputActions();
            $this->OutputOptions();
?>
</div>
<?php
        }
    }
    
    private function OutputActions()
    {
            if(count($this->actions))
            {
?>
<div class="actions">
<?php
                foreach($this->actions as $action)
                {
?>
    <a class="button" id="btn<?php echo strtolower($action[0]);?>" href="<?php echo $action[1];?>"><?php echo $action[0];?></a>
<?php
                }
?>
</div>
<?php
            }
        
    }
    private function OutputOptions()
    {
        if(count($this->options))
        {
?>
<ul class="options">
<?php
            foreach($this->options as $opt)
            {
?>
    <li <?php if ($opt[2]) echo ' class="active"';?>><a href="<?php echo $opt[1];?>"><?php echo $opt[0];?></a></li>
<?php
            }
?>
</ul>
<?php
        }        
    }

    private $options = array();
    private $actions = array();
    
    public function addOption($label, $url, $isActive = false)
    {
        $this->options[] = array($label, $url, $isActive);
    }
    public function addAction($label, $url)
    {
        $this->actions[] = array($label, $url);
    }
    
    public function addSettings()
    {
        $this->addOption("Algemeen", "settings", $_GET['module']=='settings');
        $this->addOption("Gebruikers", "users", $_GET['module']=='users');
        $this->addOption("Gebruiksrechten", "roles", $_GET['module']=='roles');
    }

}
?>