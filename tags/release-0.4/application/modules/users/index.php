<?php
if (!$p)
{
	$this->activeTopMenu = "settings";
	// Define submenu
	$this->subMenu = ViewMaster::Create("SubMenu");
	$this->subMenu->addSettings();
	$this->subMenu->addAction(_("New"), "users/edit");
	
	$users = Users::GetList();
} else {
	$count = mysql_num_rows($users);
	if ($count==0)
	{
		echo _("No users found.");
	} else {
?>
<table id="users" class="list">
	<thead>
		<tr>
			<th><?php echo _("Username");?></th>
			<th><?php echo _("Name");?></th>
		</tr>
	</thead>
	<tbody>
<?php
while ($row = mysql_fetch_assoc($users))
{
?>
		<tr id="<?php echo $row['ID'];?>">
			<td><?php echo $row['username'];?></td>
			<td><?php echo $row['fullname'];?></td>
		</tr>
<?php
}
?>
	</tbody>
</table>
<script>
	$(document).ready(function(){
		//////////////////////////////////////////////////////////////////////
		// Define click event for edit actions
		//////////////////////////////////////////////////////////////////////
		$("#users tbody tr").click(function()
		{
			Redirect("users/edit/" + this.id);
		}).addClass("selectable");
	});
</script>
<?php	
	}
}

?>
