<?
if($_SESSION['type']!=='admin') exit();

if($_POST['email']) {
    
    if(empty($_POST['access_demos'])){
        $_POST['access_demos'] = '0';
    }

    //echo 'INSERT INTO users (`name`,`email`,`password`, `type`, `active`, `access_production`, `access_demos`) VALUES (\''.sf($_POST['name']).'\',\''.sf($_POST['email']).'\',\''.sf(password_hash($_POST['password'], PASSWORD_DEFAULT)).'\', \''.sf($_POST['type']).'\', \''.sf($_POST['active']).'\', \''.sf($_POST['access_production']).'\', \''.sf($_POST['access_demos']).'\') ';
    
	mysqli_query($link, 'INSERT INTO users (`name`,`email`,`password`, `type`, `active`, `access_production`, `access_demos`) VALUES (\''.sf($_POST['name']).'\',\''.sf($_POST['email']).'\',\''.sf(password_hash($_POST['password'], PASSWORD_DEFAULT)).'\', \''.sf($_POST['type']).'\', \''.sf($_POST['active']).'\', \''.sf($_POST['access_production']).'\', \''.sf($_POST['access_demos']).'\')');
	
	$id = mysqli_insert_id($link);
	
	echo 'document.location=\''.root().'?page=users\'';
	
	exit();

}

?>
<form id="user_form">
<strong>Name</strong>
		  <input id="name" name="name" placeholder="Name" class="form-control input-md" type="text" value=""><br />
		  <strong>Email</strong>
		  <input id="email" name="email" placeholder="Email Address" class="form-control input-md" type="text" value=""><br />
		  <strong>Password</strong>
		  <input id="password" name="password" placeholder="Password" class="form-control input-md" type="password" value=""><br />
		  <strong>Type</strong>
		 <select name="type" id="type">
			<option value="employee">Employee</option>
			<option value="admin">Admin User</option>
		</select>	
		
		<br />
		<br />
		<strong>Access:</strong> (employees only)<br />
		
		<input type="checkbox" name="access_production" value="1" checked="checked"> Production Access <br />
		<input type="checkbox" name="access_demos" value="1"> Demo System Access

		<br />
		<br />
		<strong>Active: </strong> <input type="checkbox" name="active" value="1" checked="checked" id="active">
		<br />

		<br />
		<button type="button" class="btn btn-info" onclick="add_user();">Add User</button>

		<script>
		function add_user() {
		$.post('<?=root()?>exec/add_user/', $('#user_form').serialize(), null, 'script');
		}
		</script>
</form>