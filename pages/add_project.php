<?

if($_POST['customer_email']) {

    //echo 'INSERT INTO customers (`name`,`address`,`address_2`, `city`, `state`, `zip`, `country`, `email`, `phone`, `sponsor`, `notes`) VALUES (\''.sf($_POST['customer_name']).'\',\''.sf($_POST['customer_address']).'\',\''.sf($_POST['customer_address_2']).'\', \''.sf($_POST['customer_city']).'\', \''.sf($_POST['customer_state']).'\', \''.sf($_POST['customer_zip']).'\', \''.sf($_POST['customer_country']).'\', \''.sf($_POST['customer_email']).'\', \''.sf($_POST['customer_phone']).'\', \''.sf($_POST['customer_sponsor']).'\', \''.sf($_POST['customer_notes']).'\')';
    
	mysqli_query($link, 'INSERT INTO customers (`name`,`address`,`address_2`, `city`, `state`, `zip`, `country`, `email`, `phone`, `sponsor`, `notes`) VALUES (\''.sf($_POST['customer_name']).'\',\''.sf($_POST['customer_address']).'\',\''.sf($_POST['customer_address_2']).'\', \''.sf($_POST['customer_city']).'\', \''.sf($_POST['customer_state']).'\', \''.sf($_POST['customer_zip']).'\', \''.sf($_POST['customer_country']).'\', \''.sf($_POST['customer_email']).'\', \''.sf($_POST['customer_phone']).'\', \''.sf($_POST['customer_sponsor']).'\', \''.sf($_POST['customer_notes']).'\')');
	
	$id = mysqli_insert_id($link);
	
	echo '$("#myModal").modal("hide");'."\n";
	echo '$("#customer").append(\'<option value="'.$id.'">#'.$id.' - '.$_POST['customer_name'].' - '.$_POST['customer_city'].', '.$_POST['customer_state'].', '.$_POST['customer_country'].'</option>\');'."\n";
	echo '$("#customer").val("'.$id.'");'."\n";
	echo '$("#customer").trigger("chosen:updated");'."\n";
	
	exit();

}

if($_POST['project_name']) {
	//echo 'SELECT * FROM products WHERE id=\''.sf($_POST['product']).'\'';
	
	$pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($_POST['product']).'\'');
	$product = mysqli_fetch_assoc($pq);
	
	
	//prep global variables
	$g_vars = array();
	
	$p_g_vars = json_decode($product['global_vars'], true);
	
	//print_r($p_g_vars);
	
	foreach($p_g_vars as $key=>$group) {
		
		$v_vars = array();
		
		foreach($group['vars'] as $v_key=>$var) {
			$v_vars[$var] = null;
		}
		
		$g_vars[] = array('name'=>$group['name'], 'vars'=>$v_vars);
		
		$v_vars = null;
		
		//print_r($v_vars);
	}
	
	//echo 'INSERT INTO projects (`customer`,`product`,`name`, `location`, `serial`, `status`, `payment`, `notes`, `estimated_completion`, `priority`, `started`, `pod`, `global_vars`) VALUES (\''.sf($_POST['customer']).'\', \''.sf($_POST['product']).'\', \''.sf($_POST['project_name']).'\', \''.sf($_POST['location']).'\', \''.sf($_POST['serial']).'\', \'started\', \''.sf($_POST['payment']).'\', \''.sf($_POST['notes']).'\', \''.date('Y-m-d h:i:s' ,strtotime($_POST['date'])).'\', \''.sf($_POST['priority']).'\', NOW(), \''.sf($_POST['pod']).'\', \''.sf(json_encode($g_vars)).'\')';
	
	mysqli_query($link, 'INSERT INTO projects (`customer`,`product`,`name`, `location`, `serial`, `status`, `payment`, `notes`, `estimated_completion`, `priority`, `started`, `pod`, `global_vars`) VALUES (\''.sf($_POST['customer']).'\', \''.sf($_POST['product']).'\', \''.sf($_POST['project_name']).'\', \''.sf($_POST['location']).'\', \''.sf($_POST['serial']).'\', \'started\', \''.sf($_POST['payment']).'\', \''.sf($_POST['notes']).'\', \''.sf(date('Y-m-d h:i:s' ,strtotime($_POST['date']))).'\', \''.sf($_POST['priority']).'\', NOW(), \''.sf($_POST['pod']).'\', \''.sf(json_encode($g_vars)).'\')');
	
	$id = mysqli_insert_id($link);
	
	//echo '<br/><br/>id projects '.$id.'<br/><br/>';
	
	//echo 'SELECT * FROM product_steps WHERE product=\''.sf($_POST['product']).'\' ORDER BY `order` ASC';
	
	// add all steps
	$steps = mysqli_query($link, 'SELECT * FROM product_steps WHERE product=\''.sf($_POST['product']).'\' ORDER BY `order` ASC');
    
	while($step = mysqli_fetch_assoc($steps)) { 
	    
	    //echo 'INSERT INTO project_steps (`project`, `step`, `order`, `name`, `metadata`, `parts`) VALUES (\''.$id.'\', \''.$step['id'].'\', \''.$step['order'].'\', \''.sf($step['name']).'\', \''.sf($step['metadata']).'\', \''.sf($step['parts']).'\')';
		
		mysqli_query($link, 'INSERT INTO project_steps (`project`, `step`, `order`, `name`, `metadata`, `parts`) VALUES (\''.sf($id).'\', \''.sf($step['id']).'\', \''.sf($step['order']).'\', \''.sf($step['name']).'\', \''.sf($step['metadata']).'\', \''.sf($step['parts']).'\')');
		   
		$sid = mysqli_insert_id($link);
		
        echo '<br/><br/>id project_steps '.$sid.'<br/><br/>';
        
        echo '<hr/>';
        
        echo 'SELECT * FROM product_sub_steps WHERE step=\''.sf($step['id']).'\' ORDER BY `s_order` ASC';
        
		$s_steps = mysqli_query($link, 'SELECT * FROM product_sub_steps WHERE step=\''.sf($step['id']).'\' ORDER BY `s_order` ASC');
		
		while($sub_step = mysqli_fetch_assoc($s_steps)) { 
			
			//print_r($sub_step);
			
			//echo 'INSERT INTO project_sub_steps (`project`, `s_order`, `step`, `sub_step`, `type`, `name`, `part`, `variables`, `value`, `notes`, `completed`, `completed_time`, `completed_by`) VALUES (\''.$id.'\', \''.sf($sub_step['s_order']).'\', \''.sf($sid).'\', \''.sf($sub_step['id']).'\', \''.sf($sub_step['type']).'\', \''.sf($sub_step['name']).'\', \''.sf($sub_step['part']).'\', \''.sf($sub_step['variables']).'\', \'\', \'\', 0, null, \'\')';
			
			mysqli_query($link, 'INSERT INTO project_sub_steps (`project`, `s_order`, `step`, `sub_step`, `type`, `name`, `part`, `variables`, `value`, `notes`, `completed`, `completed_time`, `completed_by`) VALUES (\''.sf($id).'\', \''.sf($sub_step['s_order']).'\', \''.sf($sid).'\', \''.sf($sub_step['id']).'\', \''.sf($sub_step['type']).'\', \''.sf($sub_step['name']).'\', \''.sf($sub_step['part']).'\', \''.sf($sub_step['variables']).'\', \'\', \'\', 0, null, \'\')');
		}
		
	}
	
	//echo 'SELECT product_parts.* FROM product_parts WHERE product=\''.sf($_POST['product']).'\' ORDER BY `p_order` ASC';
	// add all parts
	$parts = mysqli_query($link, 'SELECT product_parts.* FROM product_parts WHERE product=\''.sf($_POST['product']).'\' ORDER BY `p_order` ASC');

	while($part = mysqli_fetch_assoc($parts)) {
	    
	    //print_r($part);
	    
	    //echo 'INSERT INTO project_parts (`project`, `part`, `category_id`, `name`, `variables`) VALUES (\''.$id.'\', \''.sf($part['id']).'\', \''.sf($part['category']).'\', \''.sf($part['name']).'\' , \''.sf($part['variables']).'\')';
		
		mysqli_query($link, 'INSERT INTO project_parts (`project`, `part`, `category_id`, `name`, `variables`) VALUES (\''.sf($id).'\', \''.sf($part['id']).'\', \''.sf($part['category']).'\', \''.sf($part['name']).'\' , \''.sf($part['variables']).'\')');
		
	}
	
	//echo $id;
	
	//echo 'https://projects.ndevix.com/peregrinemanage/?page=edit_project&id='.$id.'&step2=true';
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=edit_project&id='.$id.'&step2=true">';
	
	//header('location: '.root().'?page=upload_project_data&id='.$id.'&step2=true');
	//header('Location: https://projects.ndevix.com/peregrinemanage/?page=edit_project&id='.$id.'&step2=true');
	exit();
}

?>

<div id="myModal" class="modal fade" role="dialog">
<form class="form-horizontal" action="" method="post" id="new_customer_form">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add A Customer</h4>
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
		  <strong>Sponsor</strong>
		  <input id="customer_sponsor" name="customer_sponsor" placeholder="Sponsor" class="form-control input-md" type="text" value=""><br />
		  <strong>Notes</strong>
		  <textarea id="customer_notes" name="customer_notes" placeholder="Notes" class="form-control input-md"></textarea><br /><br />
		  
		  
		  <br />
		  <button type="button" class="btn btn-info" onclick="add_customer();">Add Customer</button>
		  
		  <script>
		  function add_customer() {
			$.post('<?=root()?>exec/add_project/?add_customer=true', $('#new_customer_form').serialize(), null, 'script');
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

<legend>Start a project</legend>

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
				$('#myModal').modal('show');
			}
		});
		
		$("#customer").chosen();
		
		
		
	});
	
	</script>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="product">Product</label>  
  <div class="col-md-4">
  <select id="product" name="product" class="form-control input-md" required="">
  <option></option>
  <?
  $products = mysqli_query($link, 'SELECT * FROM products ORDER BY name ASC');
  while($product = mysqli_fetch_assoc($products)) {
		echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
  }
  ?>
  </select>
  <script>
	$(function() {
		$('#product').change(function() {
			
			$('#project_name').val($("#product option:selected").text());
			
		});
	});
	
	</script>
  </div>
</div>



<div class="form-group">
  <label class="col-md-4 control-label" for="project_name">Project Name</label>  
  <div class="col-md-4">
  <input id="project_name" name="project_name" placeholder="ProjectName" class="form-control input-md" required="" type="text">
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="date">Desired Completion Date</label>  
  <div class="col-md-4">
  <input id="date" name="date" placeholder="Desired Completion Date" class="form-control input-md" required="" type="text">
  </div>
  
  <script>
  $(function() {
	$( "#date" ).datepicker();
  });
  </script>
  
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="serial">Serial Number</label>  
  <div class="col-md-4">
  <input id="serial" name="serial" placeholder="Serial Number" class="form-control input-md" required="" type="text">
  </div>
  
  <script>
      
	 var timer = null;
    $('#serial').keyup(function() {
        clearTimeout(timer);
        var $this = $(this);
    
        timer = setTimeout(function() {
            var serial = {'serial' : $this.val()}
            
            if (serial != '') {
                //alert(serial);
                $.post('<?php echo root("do/check_serial_ajax/"); ?>', serial, function(result){
                    if(result == 1){
                        $.notify('Serial Number Already Exists', 'error')
                    }
                    else{
                        $('#serial').val(result);
                    }
                })
            }
        }, 1000);
    });
	
  </script>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="location">Manufacturing Location</label>  
  <div class="col-md-4">
  <input id="location" name="location" placeholder="Manufacturing Location" class="form-control input-md" required="" type="text" value="Peregrine">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="pod">Production Cycle</label>  
  <div class="col-md-4">
  <input id="pod" name="pod" placeholder="Production Cycle" class="form-control input-md" required="" type="text">
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="priority">Priority</label>  
  <div class="col-md-4">
  <select id="priority" name="priority" class="form-control input-md" required="">
	<option value="Low">Low</option>
	<option value="Standard" selected="selected">Standard</option>
	<option value="Critical">Critical</option>
  </select>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="location">Payment</label>  
  <div class="col-md-4">
  <textarea id="payment" name="payment" placeholder="Payment Information" class="form-control input-md"></textarea>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="location">Notes</label>  
  <div class="col-md-4">
  <textarea id="notes" name="notes" placeholder="Project Notes" class="form-control input-md"></textarea>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary">Next Step</button>
  </div>
</div>



</fieldset>
</form>

