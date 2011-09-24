<?php
class topMenu extends ViewMaster
{
    public function DoOutput()
    {
		$f = Waf::Singleton();
		if (isset($f->activeTopMenu))
		{
			$this->activeTopMenu = $f->activeTopMenu;
		} else {
			$this->activeTopMenu = "";		
		}
        
        $modules = Modules::GetModules();

?>
<nav id="top">
    <ul>
        <li <?php echo ($this->activeTopMenu == '') ? 'class="active"' : '';?>><a href=".">Dashboard</a></li>
<?php

        foreach ($modules as $name => $module)
        {
            if (!Security::Allowed($name))
                continue;
            if ((isset($this->ignoreList)) && (in_array($name, $this->ignoreList)))
                continue;
?>
        <li <?php echo ($this->activeTopMenu == $name) ? 'class="active"' : '';?>><a href="<?php echo $name;?>"><?php echo $module['name'];?></a></li>
<?php
        }
?>
    </ul>
</nav>
<?php
    }

}
?>