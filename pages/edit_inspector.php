<?php
if($_SESSION['type']!=='admin') 
    exit();
    
if($_GET['delete']==1) 
{
    $query = 'DELETE FROM inspectors WHERE id=\''.sf($_GET['id']).'\'';
	$delete = mysqli_query($link, $query);
	
	if($delete)
	{
	    $_SESSION['message_content'] = 'Inspector have been succesfully deleted!';
	    $_SESSION['message_type'] = 'success';
	}
	else
	{   
	    $_SESSION['message_content'] = 'Failed to delete inspector!';
	    $_SESSION['message_type'] = 'error';
	}
	echo 'document.location=\''.root().'?page=inspectors\'';
	exit();
}

if($_POST['email']) 
{
	$update = mysqli_query($link, 'UPDATE inspectors SET 
	                        `name`=\''.sf($_POST['name']).'\'
	                        , `email`=\''.sf($_POST['email']).'\'
	                        , `initial`=\''.sf($_POST['initial']).'\'
	                        , `stamp_number`=\''.sf($_POST['stamp_number']).'\'
	                        WHERE id=\''.sf($_GET['id']).'\'');	
	                        
	if($update)
	{
	    $_SESSION['message_content'] = 'Inspector have been successfully modified!';
	    $_SESSION['message_type'] = 'success';
	}
	else
	{   
	    $_SESSION['message_content'] = 'Failed to modify inspector!';
	    $_SESSION['message_type'] = 'error';
	}
	
	if($_POST['password']!=='') {
		mysqli_query($link, 'UPDATE inspectors SET `password`=\''.sf(password_hash($_POST['password'], PASSWORD_DEFAULT)).'\' WHERE id=\''.sf($_GET['id']).'\'');	
	}
	
	echo 'document.location=\''.root().'?page=inspectors\'';
	exit();
}

$q = mysqli_query($link, 'SELECT * FROM inspectors WHERE id=\''.sf($_GET['id']).'\'');
$u = mysqli_fetch_assoc($q);

?>
<div class="floatr">
	<button class="btn btn-danger" onclick="javascript:delete_inspector()">Delete Inspector</button>
</div>
<div class="clear"></div>
<form id="user_form">
          <strong>Name</strong>
		  <input id="name" name="name" placeholder="Name" class="form-control input-md" type="text" value="<?=$u['name']?>" required="required"><br />
		  <strong>Email</strong>
		  <input id="email" name="email" placeholder="Email Address" class="form-control input-md" type="text" value="<?=$u['email']?>" required="required"><br />
		  <strong>Initial</strong>
		  <input id="initial" name="initial" placeholder="Email Address" class="form-control input-md" type="text" value="<?=$u['initial']?>" required="required"><br />
		  <strong>Stamp Number</strong>
		  <input id="stamp_number" name="stamp_number" placeholder="Stamp #" class="form-control input-md" type="text" value="<?=$u['stamp_number']?>" required="required"><br />
          <strong>Password</strong>
          <input id="password" name="password" placeholder="Password" class="form-control input-md" type="password" value=""><br />
		<br />
		<button type="button" class="btn btn-info" onclick="edit_inspector();">Save Inspector</button>

		<script>
		function edit_inspector() {
		    $.post('<?=root()?>exec/edit_inspector/?id=<?=$_GET['id']?>', $('#user_form').serialize(), null, 'script');
		}
		
		function delete_inspector() {
		    $.post('<?=root()?>exec/edit_inspector/?id=<?=$_GET['id']?>&delete=1', $('#user_form').serialize(), null, 'script');
		}
		</script>
</form>