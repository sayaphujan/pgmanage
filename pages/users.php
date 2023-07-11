<?
if($_SESSION['type']!=='admin') exit();

?>
<div class="floatl">
	<h2 class="form-signin-heading">Staffs</h2>
</div>

<div class="floatr">
	<button class="btn btn-primary" onclick="document.location='<?=root()?>?page=add_user';">Add Staff</button>
</div>
<div class="clear"></div>


<table class="table table-striped table-bordered table-hover">
<tr><th width="30%">Name</th><th>E-Mail</th><th>User Type</th><th>Last Login</th><th>Active</th></tr>

<?

$q = mysqli_query($link, 'SELECT * FROM users WHERE type != \'inspector\' ORDER BY name ASC');

while($row = mysqli_fetch_assoc($q)) {
	echo '<tr onclick="document.location=\''.root().'?page=edit_user&id='.$row['id'].'\'">
		<td>'.$row['name'].'</td>
		<td>'.$row['email'].'</td>
		<td>'.$row['type'].'</td>
		<td>'.$row['last_login'].'</td>
		<td>'.$row['active'].'</td>
		</tr>';
}

?>

</table>      
