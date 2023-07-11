<?
if(!check_access('demos')) exit();

if($_POST['customer_email']) {

	mysqli_query($link, 'INSERT INTO customers (`name`,`address`,`address_2`, `city`, `state`, `zip`, `country`, `email`, `phone`, `sponsor`, `notes`) VALUES (\''.sf($_POST['customer_name']).'\',\''.sf($_POST['customer_address']).'\',\''.sf($_POST['customer_address_2']).'\', \''.sf($_POST['customer_city']).'\', \''.sf($_POST['customer_state']).'\', \''.sf($_POST['customer_zip']).'\', \''.sf($_POST['customer_country']).'\', \''.sf($_POST['customer_email']).'\', \''.sf($_POST['customer_phone']).'\', \''.sf($_POST['customer_sponsor']).'\', \''.sf($_POST['customer_notes']).'\')');
	
	$id = mysqli_insert_id($link);
	
	echo '$("#AddCustomerModal").modal("hide");'."\n";
	echo '$("#customer").append(\'<option value="'.$id.'">#'.$id.' - '.$_POST['customer_name'].' - '.$_POST['customer_city'].', '.$_POST['customer_state'].', '.$_POST['customer_country'].'</option>\');'."\n";
	echo '$("#customer").val("'.$id.'");'."\n";
	echo '$("#customer").trigger("chosen:updated");'."\n";
	
	exit();

}



if($_POST['customer']) {
	
	$meta = array();
	$meta['verifying_person']['name'] = sf($_POST['verify_person_name']);
	$meta['verifying_person']['contact'] = sf($_POST['verify_person_contact']);
	$metadata = json_encode($meta);
	
	mysqli_query($link, 'INSERT INTO demo_requests (`customer`,`date`,`requested_demo_pool`, `assigned_unit`, `status`, `notes`, `shipped_date`, `tracking_number`,`metadata`) VALUES (\''.sf($_POST['customer']).'\', NOW(),\''.sf($_POST['product']).'\', \''.sf($_POST['demo_unit']).'\', \''.sf($_POST['status']).'\', \''.sf($_POST['notes']).'\', \''.sf(date('Y-m-d h:i:s' ,strtotime($_POST['shipped_date']))).'\', \''.sf($_POST['tracking_number']).'\',\''.sf($metadata).'\')');
	
	header('location: '.root().'?page=demos&id='.mysqli_insert_id($link).'');
	
}

?>

<div id="AddCustomerModal" class="modal fade" role="dialog">
<form class="form-horizontal" action="" method="post" id="new_customer_form">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create A New Customer Lead</h4>
      </div>
      <div class="modal-body">
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
		  <strong>Customer Notes</strong>
		  <textarea id="customer_notes" name="customer_notes" placeholder="Notes" class="form-control input-md"></textarea><br /><br />
		  
		  
		  <br />
		  <button type="button" class="btn btn-info" onclick="add_customer();">Add Customer</button>
		  
		  <script>
		  function add_customer() {
			$.post('<?=root()?>exec/add_demo/?add_customer=true', $('#new_customer_form').serialize(), null, 'script');
		  }
		  </script>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
  </form>
</div>	




<div id="AddDemoUnitModal" class="modal fade" role="dialog">
  <form class="form-horizontal" action="" method="post" id="new_demo_unit_form">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Demo Unit</h4>
      </div>
      <div class="modal-body">
		  <strong>Demo Unit Set/Pool</strong>
		  <select id="demo_pool" name="demo_pool" class="form-control input-md">
			<?
				$demo_pool = mysqli_query($link,'SELECT * FROM demo_pool ORDER BY name ASC');
				while($pool = mysqli_fetch_assoc($demo_pool)) {
					echo '<option value="'.$pool['id'].'">'.$pool['name'].'</option>';
				}
			?>		  
		  </select>
		  <br />
		  <strong>Serial Number</strong>
		  <input id="demo_unit_serial" name="demo_unit_serial" placeholder="Serial Number" class="form-control input-md" type="text" value=""><br />
		  <strong>Colors</strong>
		  <input id="demo_unit_colors" name="demo_unit_colors" placeholder="Colors" class="form-control input-md" type="text" value=""><br />
		  
		  <strong>Canopy Notes</strong>
		  <textarea id="demo_unit_notes" name="demo_unit_notes" placeholder="Notes" class="form-control input-md"></textarea><br /><br />
		  
		  
		  <br />
		  <button type="button" class="btn btn-info" onclick="add_demo_unit();">Add Demo Canopy</button>
		  
		  <script>
		  function add_demo_unit() {
			$.post('<?=root()?>exec/demo/?add_demo_unit=true', $('#new_demo_unit_form').serialize(), null, 'script');
		  }
		  </script>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
  </form>
</div>	



	

<form class="form-horizontal" action="" method="post">
<fieldset>

<legend>Create a Demo Request</legend>

<div class="form-group">
  <label class="col-md-4 control-label" for="name">Customer</label>  
  <div class="col-md-4">
  <select id="customer" name="customer" class="form-control input-md" data-placeholder="Choose a customer..." class="chosen-select">
  <option></option>
  <option value="new">New Customer</option>
  <?
  $customers = mysqli_query($link, 'SELECT * FROM customers ORDER BY name ASC');
  while($customer = mysqli_fetch_assoc($customers)) {
		echo '<option value="'.$customer['id'].'">#'.$customer['id'].' - '.$customer['name'].' - '.$customer['city'].', '.$customer['state'].', '.$customer['country'].'</option>';
  }
  ?>
  </select>
  
	
	<script>
	$(function() {
		$('#customer').change(function() {
			
			if($('#customer').val()=='new') {
				$('#AddCustomerModal').modal('show');
			}
		});
		
		$("#customer").chosen();
		
		
		
	});
	
	</script>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="product">Product Type</label>  
  <div class="col-md-4">
  <select id="product" name="product" class="form-control input-md" required="">
  <?
  $products = mysqli_query($link, 'SELECT * FROM demo_pool ORDER BY name ASC');
  while($product = mysqli_fetch_assoc($products)) {
		echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
  }
  ?>
  </select>
    <script>

	
	
	</script>
  </div>
</div>







<div class="form-group">
  <label class="col-md-4 control-label" for="product">Assigned Demo Unit</label>  
  <div class="col-md-4">
  <select id="demo_unit" name="demo_unit" class="form-control input-md" data-placeholder="Assign a canopy - Optional" class="chosen-select">
  <option></option>
  <option value="new">-- New Demo Canopy</option>
  <?
  $units = mysqli_query($link, 'SELECT * FROM demo_units WHERE status=\'Available\' ORDER BY serial_number ASC');
  while($unit = mysqli_fetch_assoc($units)) {
		echo '<option value="'.$unit['id'].'">'.$unit['serial_number'].' - '.$unit['colors'].'</option>';
  }
  ?>
  </select>
  <script>
	$(function() {
		$('#demo_unit').change(function() {
			
			if($('#demo_unit').val()=='new') {
				$('#AddDemoUnitModal').modal('show');
			}
		});
		
		$("#demo_unit").chosen();
	});
	
	</script>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="status">Status</label>  
  <div class="col-md-4">
  <select id="status" name="status" class="form-control input-md" required="">
	<option value="Pending">Pending Acceptance</option>
	<option value="Accepted">Accepted</option>
	<option value="Shipped">Shipped</option>
	<option value="Received">Received</option>
  </select>
  </div>
</div>


<script>
	$(function() {
		$('#status').change(function() {
			if($('#status').val()=='Shipped' || $('#status').val()=='Received') {
				$('#shipping_info_container').removeClass('hidden');
			} else {
				$('#shipping_info_container').addClass('hidden');
				$('#tracking').val('');
				$('#shipped_date').val('');
			}
			$('#shipping_info_container').show();
		});
	});
	
	</script>

<div id="shipping_info_container" class="hidden">

	<div class="form-group">
	  <label class="col-md-4 control-label">Shipped Date</label>  
	  <div class="col-md-4">
	  <input id="shipped_date" name="shipped_date" placeholder="Shipped Date" class="form-control input-md" type="text">
	  </div>
	</div>
	
	<script>
	  $(function() {
		$( "#shipped_date" ).datepicker();
	  });
	</script>

	<div class="form-group">
	  <label class="col-md-4 control-label">Tracking Number</label>  
	  <div class="col-md-4">
	  <input id="tracking" name="tracking" placeholder="Tracking Number (if shipped)" class="form-control input-md" type="text">
	  </div>
	</div>
	
	

</div>

<div class="form-group">
  <label class="col-md-4 control-label">Verifying Person (S&amp;TA, DZO, etc)</label>  
  <div class="col-md-4">
  <input id="verify_person_name" name="verify_person_name" placeholder="Verifying persons name" class="form-control input-md" type="text">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label">Verifying Persons contact info</label>  
  <div class="col-md-4">
  <input id="verify_person_contact" name="verify_person_contact" placeholder="Phone number or email address" class="form-control input-md" type="text">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="notes">Notes</label>  
  <div class="col-md-4">
  <textarea id="notes" name="notes" placeholder="Notes" class="form-control input-md"></textarea>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary">Next Step</button>
  </div>
</div>



</fieldset>
</form>

