<?php
if (!$p)
{
	$this->activeTopMenu = "settings";
	$this->subMenu = ViewMaster::Create("SubMenu");
	$this->subMenu->addAction("Nieuw", "roles/edit");
	$this->subMenu->addSettings();
	
	$roles = Roles::GetList();
} else {
	$count = mysql_num_rows($roles);
	if ($count==0)
	{
		echo ("Geen rollen gevonden...");
	} else {
?>
<table id="roles" class="list">
	<thead>
		<tr>
			<th>Naam</th>
		</tr>
	</thead>
	<tbody>
<?php
while ($row = mysql_fetch_assoc($roles))
{
?>
		<tr id="<?php echo $row['id'];?>">
			<td><?php echo $row['name'];?></td>
		</tr>
<?php
}
?>
	</tbody>
</table>
<?php	
	}
}
?>
