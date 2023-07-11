<?

if($_POST['customer_email']) {

	mysqli_query($link, 'INSERT INTO customers (`name`,`address`,`address_2`, `city`, `state`, `zip`, `country`, `email`, `phone`, `sponsor`, `notes`) VALUES (\''.sf($_POST['customer_name']).'\',\''.sf($_POST['customer_address']).'\',\''.sf($_POST['customer_address_2']).'\', \''.sf($_POST['customer_city']).'\', \''.sf($_POST['customer_state']).'\', \''.sf($_POST['customer_zip']).'\', \''.sf($_POST['customer_country']).'\', \''.sf($_POST['customer_email']).'\', \''.sf($_POST['customer_phone']).'\', \''.sf($_POST['customer_sponsor']).'\', \''.sf($_POST['customer_notes']).'\')');
	
	$id = mysqli_insert_id($link);
	
	echo 'document.location=\''.root().'?page=customers\'';
	
	exit();

}

?>
<form id="new_customer_form">
		  <strong>Name</strong>
		  <input id="customer_name" name="customer_name" placeholder="Name" class="form-control input-md" type="text" value=""><br />
		  <strong>Email</strong>
		  <input id="customer_email" name="customer_email" placeholder="Email Address" class="form-control input-md" type="text" value=""><br />
		  <strong>Phone</strong>
		  <input id="customer_phone" name="customer_phone" placeholder="Phone" class="form-control input-md" type="text" value=""><br />
		  <strong>Address</strong>
		  <input id="customer_address" name="customer_address" placeholder="Address" class="form-control input-md" type="text" value=""><br />
		  <strong>Address 2</strong>
		  <input id="customer_address_2" name="customer_address_2" placeholder="Address Line 2 (Suite #)" class="form-control input-md" type="text" value=""><br />
		  <strong>City</strong>
		  <input id="customer_city" name="customer_city" placeholder="City" class="form-control input-md" type="text" value=""><br />
		  <strong>State</strong>
		  <input id="customer_address" name="customer_state" placeholder="State" class="form-control input-md" type="text" value=""><br />
		  <strong>Zip</strong>
		  <input id="customer_address" name="customer_zip" placeholder="Zip / Postal Code" class="form-control input-md" type="text" value=""><br />
		  <strong>Country</strong>
		  <input id="customer_country" name="customer_country" placeholder="Country" class="form-control input-md" type="text" value=""><br />
		  
		  <br />
		  <strong>Sponsor</strong>
		  <input id="customer_sponsor" name="customer_sponsor" placeholder="Sponsor" class="form-control input-md" type="text" value=""><br />
		  
		  <br />
		  <strong>Notes</strong>
		  <textarea id="customer_notes" name="customer_notes" placeholder="Notes" class="form-control input-md"></textarea><br />
		  
		  
		  
		  <br />
		  <button type="button" class="btn btn-info" onclick="add_customer();">Add Customer</button>
		  
</form>
		  <script>
		  function add_customer() {
			$.post('<?=root()?>exec/add_customer/?add_customer=true', $('#new_customer_form').serialize(), null, 'script');
		  }
		  </script>