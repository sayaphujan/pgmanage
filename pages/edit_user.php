<?
if($_SESSION['type']!=='admin') exit();


if($_POST['email']) {

	mysqli_query($link, 'UPDATE users SET `name`=\''.sf($_POST['name']).'\', `email`=\''.sf($_POST['email']).'\', `type`=\''.sf($_POST['type']).'\', `active`=\''.sf($_POST['active']).'\', `access_production`=\''.sf($_POST['access_production']).'\', `access_demos`=\''.sf($_POST['access_demos']).'\' WHERE id=\''.sf($_GET['id']).'\'');	
	
	if($_POST['password']!=='') {
		mysqli_query($link, 'UPDATE users SET `password`=\''.sf(password_hash($_POST['password'], PASSWORD_DEFAULT)).'\' WHERE id=\''.sf($_GET['id']).'\'');	
	}
	
	echo 'document.location=\''.root().'?page=users\'';
	
	exit();

}

if($_GET['delete']==1) {
	mysqli_query($link, 'DELETE FROM users WHERE id=\''.sf($_GET['id']).'\'');
	header('location: '.root().'?page=users');
}

$q = mysqli_query($link, 'SELECT * FROM users WHERE id=\''.sf($_GET['id']).'\'');

$u = mysqli_fetch_assoc($q);

?>

<div class="floatr">
	<button class="btn btn-danger" onclick="document.location='<?=root()?>?page=edit_user&id=<?=$u['id']?>&delete=1';">Delete User</button>
</div>
<div class="clear"></div>
<form id="user_form">
<strong>Name</strong>
		  <input id="name" name="name" placeholder="Name" class="form-control input-md" type="text" value="<?=$u['name']?>"><br />
		  <strong>Email</strong>
		  <input id="email" name="email" placeholder="Email Address" class="form-control input-md" type="text" value="<?=$u['email']?>"><br />
		  <strong>Password</strong>
		  <input id="password" name="password" placeholder="Password" class="form-control input-md" type="password" value=""><br />
		  <strong>Type</strong>
		 <select name="type" id="type">
			<option value="admin" <?=($u['type']=='admin' ? 'selected="selected"' : '')?>>Admin User</option>
			<option value="employee" <?=($u['type']=='employee' ? 'selected="selected"' : '')?>>Employee</option>
		</select>
		<br />
		<br />
		<strong>Access:</strong><br />
		
		<input type="checkbox" name="access_production" value="1" <?=($u['access_production']=='1' ? 'checked="checked"' : '')?>> Production Access <br />
		<input type="checkbox" name="access_demos" value="1" <?=($u['access_demos']=='1' ? 'checked="checked"' : '')?>> Demo System Access

		<br />
		<br />
		<strong>Active: </strong> <input type="checkbox" name="active" value="1" checked="checked" id="active" <?=($u['active']==1 ? 'checked="checked"' : '')?>>
		<br />

		<br />
		<button type="button" class="btn btn-info" onclick="edit_user();">Save User</button>

		<script>
		function edit_user() {
		$.post('<?=root()?>exec/edit_user/?id=<?=$_GET['id']?>', $('#user_form').serialize(), null, 'script');
		}
		</script>
</form>