<?
if($_SESSION['type']!=='admin') exit();

$q = mysqli_query($link, 'SELECT * FROM customers WHERE id=\''.sf($_GET['id']).'\'');
$c = mysqli_fetch_assoc($q);

if($_POST['name']) {
	
	mysqli_query($link, 'UPDATE customers SET `name`=\''.sf($_POST['name']).'\', `email`=\''.sf($_POST['email']).'\', `phone`=\''.sf($_POST['phone']).'\', `address`=\''.sf($_POST['address']).'\', `address_2`=\''.sf($_POST['address_2']).'\', `city`=\''.sf($_POST['city']).'\', `state`=\''.sf($_POST['state']).'\', `zip`=\''.sf($_POST['zip']).'\', `country`=\''.sf($_POST['country']).'\', `sponsor`=\''.sf($_POST['sponsor']).'\', `notes`=\''.sf($_POST['notes']).'\'  WHERE id=\''.sf($_GET['id']).'\'');
	
	echo 'document.location=\''.root().'?page=customers\';';
	exit();
}

if($_GET['delete_customer']) {
	mysqli_query($link, 'DELETE FROM customers WHERE id=\''.sf($_GET['delete_customer']).'\'');
	
	echo 'alert(\'Customer Removed\'); document.location=\''.root().'?page=customers\';';
	exit();
}

?>
<form id="edit_customer_form">
<strong>Name</strong>
		  <input id="name" name="name" placeholder="Name" class="form-control input-md" type="text" value="<?=$c['name']?>"><br />
		  <strong>Email</strong>
		  <input id="email" name="email" placeholder="Email Address" class="form-control input-md" type="text" value="<?=$c['email']?>"><br />
		  <strong>Phone</strong>
		  <input id="phone" name="phone" placeholder="Phone" class="form-control input-md" type="text" value="<?=$c['phone']?>"><br />
		  <strong>Address</strong>
		  <input id="address" name="address" placeholder="Address" class="form-control input-md" type="text" value="<?=$c['address']?>"><br />
		  <strong>Address 2</strong>
		  <input id="address_2" name="address_2" placeholder="Address Line 2 (Suite #)" class="form-control input-md" type="text" value="<?=$c['address_2']?>"><br />
		  <strong>City</strong>
		  <input id="city" name="city" placeholder="City" class="form-control input-md" type="text" value="<?=$c['city']?>"><br />
		  <strong>State</strong>
		  <input id="address" name="state" placeholder="State" class="form-control input-md" type="text" value="<?=$c['state']?>"><br />
		  <strong>Zip</strong>
		  <input id="address" name="zip" placeholder="Zip / Postal Code" class="form-control input-md" type="text" value="<?=$c['zip']?>"><br />
		  <strong>Country</strong>
		  <input id="country" name="country" placeholder="Country" class="form-control input-md" type="text" value="<?=$c['country']?>"><br />
		  
		  <br />
		  <strong>Sponsor</strong>
		  <input id="sponsor" name="sponsor" placeholder="Sponsor" class="form-control input-md" type="text" value="<?=$c['sponsor']?>"><br />
		  
		  <br />
		  <strong>Notes</strong>
		  <textarea id="notes" name="notes" placeholder="Notes" class="form-control input-md"><?=$c['notes']?></textarea><br />
		  
		  <br />

		  <button type="button" class="btn btn-info" onclick="save_customer();">Save</button>
		  
		  <button type="button" class="btn btn-danger pull-right" onclick="delete_customer();">Delete Customer</button>
		  
		  <script>
		  function save_customer() {
			$.post('<?=root()?>exec/edit_customer/?id=<?=$_GET['id']?>', $('#edit_customer_form').serialize(), null, 'script');
		  }
		  
		  function delete_customer() {
			if(confirm('Are you absolutely sure you want to delete the customer <?=$c['name']?>?')) {
				$.post('<?=root()?>exec/edit_customer/?delete_customer=<?=$_GET['id']?>', null, null, 'script');
			}
		  }
		  </script>
</form>