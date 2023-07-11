<?
if($_SESSION['type']!=='admin') exit();

if($_POST['email']) 
{
    if(empty($_POST['access_demos'])){
        $_POST['access_demos'] = '0';
    }
    
    $query = 'INSERT INTO inspectors (`name`,`email`,`password`, `active`, `access_production`, `access_demos`, `last_login`, `initial`, `stamp_number`) 
	                            VALUES (
	                                \''.sf($_POST['name']).'\'
	                                , \''.sf($_POST['email']).'\'
	                                , \''.sf(password_hash($_POST['password'], PASSWORD_DEFAULT)).'\'
	                                , 1
	                                , 1
	                                , 1
	                                , NOW()
	                                , \''.sf($_POST['initial']).'\'
	                                , \''.sf($_POST['stamp_number']).'\'
	                            )';
	                            //echo $query;
	mysqli_query($link, $query);
	
	$id = mysqli_insert_id($link);
	
	if($id)
	{
	    $_SESSION['message_content'] = 'Inspector have been added successfully!';
	    $_SESSION['message_type'] = 'success';
	}
	else
	{   
	    $_SESSION['message_content'] = 'Failed to add new inspector!';
	    $_SESSION['message_type'] = 'error';
	}
	
	echo 'document.location=\''.root().'?page=inspectors\'';

	exit();

}

?>
<h1>Add Inspector</h1>
<br>
<form id="user_form">
        <strong>Name</strong>
        <input id="name" name="name" placeholder="Name" class="form-control input-md" type="text" value=""  required="required"><br />
        <strong>Email</strong>
        <input id="email" name="email" placeholder="Email Address" class="form-control input-md" type="text" value=""  required="required"><br />
        <strong>Initial</strong>
        <input id="initial" name="initial" placeholder="initial" class="form-control input-md" type="text" value=""  required="required"><br />
        <strong>Stamp #</strong>
        <input id="stamp_number" name="stamp_number" placeholder="Stamp #" class="form-control input-md" type="text" value=""  required="required"><br />
        <strong>Password</strong>
        <input id="password" name="password" placeholder="Password" class="form-control input-md" type="password" value=""><br />
        <select style="display: none" name="type" id="type">
            <option selected value="inspector">Inspector</option>
        </select
		
		<input style="display: none"  type="checkbox" name="access_production" value="1" checked="checked">
		<input style="display: none"  type="checkbox" name="access_demos" value="1">
		<input style="display: none"  type="checkbox" name="active" value="1" checked="checked" id="active">
		
		<button type="button" class="btn btn-info" onclick="add_inspector();">Add Inspector</button>

		<script>
		function add_inspector() {
		    $.post('<?=root()?>exec/add_inspector/', $('#user_form').serialize(), null, 'script');
		}
		</script>
</form>