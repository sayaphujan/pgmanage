<?
if(!check_access('demos')) exit();

if($_GET['add_demo_unit']=='true') {
	$check = mysqli_query($link, 'SELECT * FROM demo_units WHERE serial_number = \''.sf($_POST['demo_unit_serial']).'\'');
	if(mysqli_num_rows($check)==1) {
		echo 'alert(\'This canopy already exists in the demo database\');';
		exit();
	}
	mysqli_query($link, 'INSERT INTO demo_units (`demo_pool`,`serial_number`,`colors`,`date_added`, `status`, `notes`) VALUES (\''.sf($_POST['demo_pool']).'\',\''.sf($_POST['demo_unit_serial']).'\',\''.sf($_POST['demo_unit_colors']).'\', NOW(), \'Available\', \''.sf($_POST['demo_unit_notes']).'\')');
	
	$id = mysqli_insert_id($link);
	
	echo '$("#AddDemoUnitModal").modal("hide");'."\n";
	echo '$("#demo_unit").append(\'<option value="'.$id.'">'.$_POST['demo_unit_serial'].' - '.$_POST['demo_unit_colors'].'</option>\');'."\n";
	echo '$("#demo_unit").val("'.$id.'");'."\n";
	echo '$("#demo_unit").trigger("chosen:updated");'."\n";
	
	exit();

}




$q = mysqli_query($link,'SELECT demo_requests.*, c.name, c.address, c.address_2, c.city, c.state, c.zip, c.country, c.email, c.phone, c.email, demo_pool.name as pool_name FROM demo_requests, customers as c, demo_pool WHERE demo_requests.id = \''.sf($_GET['id']).'\' AND demo_requests.customer = c.id AND demo_pool.id = demo_requests.requested_demo_pool');

$d = mysqli_fetch_assoc($q);

$meta = json_decode($d['metadata'], true);

include 'inc/demo_customer_messages.php';


//functionality

if($_GET['mark_as_shipped']=='true') {
	//verify wing is availiable. Mark any other wings as availiable if previously assigned
	
	$meta['steps']['Shipped']['payment_recieved'] = sf($_POST['payment_recieved']);
	$meta['steps']['invoice_number'] = sf($_POST['invoice_number']);
	$meta['steps']['Shipped']['notes'] = sf($_POST['shipping_notes']);
	$meta['steps']['Shipped']['completed']=1;
	
	$metadata = json_encode($meta);
	
	mysqli_query($link,'UPDATE demo_requests SET tracking_number=\''.sf($_POST['tracking_number']).'\', `metadata`=\''.sf($metadata).'\', `shipped_date`= NOW(), return_date = \''.sf(date('Y-m-d', strtotime($_POST['return_date']))).'\', `status`=\'Shipped\', `assigned_unit`=\''.sf($_POST['demo_unit']).'\' WHERE id=\''.sf($_GET['id']).'\'');
	
	mysqli_query($link, 'UPDATE demo_units SET status=\'Assigned\', `assigned_demo_request`=\''.sf($_GET['id']).'\' WHERE id=\''.sf($_POST['demo_unit']).'\'');
	
	$message = $_POST['shipping_customer_message'];
	
	$message = str_replace('%tracking_number', $_POST['tracking_number'], $message);
	$message = str_replace('%product', $d['pool_name'], $message);
	$message = str_replace('%name', $d['name'], $message);
	$message = str_replace('%return_date', $d['return_date'], $message);
	
	mysqli_query($link, 'INSERT INTO customer_messages (customer, date, type, message) VALUES (\''.sf($d['customer']).'\', NOW(), \'Demo Shipped\', \''.sf($message).'\')');
	
	include 'demo_step_email.php';
	$email = demo_step_email($message);
	echo $d['email']; exit();
	sendHTML($d['email'],'Demo Wing Shipped',$email);
	
	echo '
	alert("Wing marked as shipped.");
	document.location="/?page=demos";
	';
	exit();
	
}







?>
<div class="col-md-8">
	<h3>Demo Wing Request for <?=$d['name']?></h3>
</div>

<div class="col-md-6">Demo Info</div>
<div class="col-md-6">Customer Info</div>

<div class="col-md-12">Compatible Demo Containers</div>

<div class="clear"></div>
<h4>Demo Steps</h4>
<?
/*
4 Steps. 
1) User verification - mark as verified button

2) Waiting to Ship - Ship demo button. Modial pop up displaying customer info, requesting tracking number, and checkbox stating payment received. if shipped - show assigned wing and tracking info. If no payment received, bold red text. 

4) Received - show received notes

5) Follow up - show follow up notes

json struct: meta-> $steps[step name]-> [completed=0/1],[alt info, eg tracking_number] 

*/
$demo_steps = array('Pending'=>array('name'=>'Pending Approval'), 'Approved'=>array('name'=>'Assignment and Shipping'), 'Shipped'=>array('name'=>'Demo in progress'), 'Recieved'=>array('name'=>'Recieved'));



foreach($demo_steps as $step=>$step_detail) {
	
	$step_class = null;
	$step_subtext = null;
	$step_btn = null;
	
	$s_data = $meta['steps'][$step];
	
	//check if its completed or not
	if($s_data['completed']==1) {
		$step_class = 'alert-success';
	} else {
		$step_class = 'alert-info'; 	
	}
	
	
	//	$step_class = 'alert-warning'; 
	
	

	
	if($step=='Pending') {
		$step_label = ''; 
		$step_btn = '<span class="pull-right"><button class="btn btn-success" onclick="demo_status(\'Approve\');" type="button">Approve</button>  <button class="btn btn-danger" onclick="demo_status(\'Deny\');" type="button">Deny</button></span>';
		$step_subtext = 'Verifying contact: '.$meta['verifying_person']['name'].' '.$meta['verifying_person']['contact'].'
		<p>Notes: '.$s_data['notes'].'</p>';
		if($s_data['completed']==1) $approved=true;
	}
	
	if($step=='Approved') {
		
		$step_label = ''; 
		$step_btn = '<span class="pull-right"><button class="btn btn-success" onclick="ship_wing();" type="button">Ship Demo Wing</button></span>';
		$payment_recieved = 'Payment Recieved: '.($s_data['payment_recieved']==1 ? 'Yes' : 'No');
		if($s_data['completed']==1 && $s_data['payment_recieved']!==1) {
			$payment_recieved = '<strong class="text-danger">'.$payment_recieved.'</strong>';	
		}
		
		$step_subtext = $payment_recieved.' | Tracking Number: '.$d['tracking_number'].'
		<p>Notes: '.$s_data['notes'].'</p>';
		if($s_data['completed']==1) $shipped=true;
	}
	
	if($step=='Shipped') {
		
		$step_label = ''; 
		if($shipped==true) $step_btn = '<span class="pull-right"><button class="btn btn-success" onclick="view_step(\''.$step.'\');" type="button">Mark as recieved</button></span>';
		
		$step_subtext = 'Notes: '.$s_data['notes'];
		if($s_data['completed']==1) $recieved=true;
	}
	
	if($step=='Recieved') {
		
		$step_label = ''; 
		if($recieved==true) $step_btn = '<span class="pull-right"><button class="btn btn-success" onclick="view_step(\''.$step.'\');" type="button">Follow Up</button></span>';
		
		$step_subtext = 'Notes: '.$s_data['notes'];
		
	}
	
	
	?>
	<div class="product_step <?=$step_class?>">
	<h4><?=$step_detail['name']?> <?=$step_btn?></h4>
	
	<div style="color: black;"><?=$step_subtext?></div>
	<div class="clear"></div>
	</div>
<? } ?>


<!-- Modals -->

<div id="ApprovalModal" class="modal fade" role="dialog">
  <form class="form-horizontal" action="" method="post" id="approval_form">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Shiping Info</h4>
      </div>
      <div class="modal-body">
		  Use the following information to generate a shipping label:
		  <br><br>
		  <?=$d['name']?>
		  <br>
		  <?=$d['address']?>
		  <br>
		  <?=$d['city'].', '.$d['state'].' '.$d['zip']?>
		  <br>
		  <?=$d['country']?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
  </form>
</div>


<div id="ShipWingModal" class="modal fade" role="dialog">
  <form class="form-horizontal" action="" method="post" id="ship_wing_form">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ship Demo Container</h4>
      </div>
      <div class="modal-body">
		  <strong>Demo Unit Set/Pool</strong>: 
		  
			<?
				$demo_pool = mysqli_query($link,'SELECT * FROM demo_pool WHERE demo_pool.id = \''.sf($d['requested_demo_pool']).'\' ORDER BY name ASC');
				
				$pool = mysqli_fetch_assoc($demo_pool);
				
				echo ''.$pool['name'].'';
				
			?>		  
		  
		  <br />
		  <br>
		  
		 
		  <strong>Assigned Demo Unit</strong>  
		 
		  <select id="demo_unit" name="demo_unit" class="form-control input-md chzn-select" data-placeholder="Assign a canopy - Required" class="chosen-select" style="width: 300px;">
		  <option></option>
		  <option value="new">-- New Demo Canopy</option>
		  <?
		  $units = mysqli_query($link, 'SELECT * FROM demo_units WHERE (status=\'Available\' OR  AND pool=\''.sf($pool['id']).'\' ORDER BY serial_number ASC');
		  while($unit = mysqli_fetch_assoc($units)) {
				echo '<option value="'.$unit['id'].'">'.$pool['name'].' - '.$unit['serial_number'].' - '.$unit['colors'].'</option>';
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
			
		<br><br>
		<input type="button" class="btn-info" onclick="$('#ShippingInfoModal').modal('show');" value="Print Shipping Label">
		
		<br><br>
		  
		  
		  <strong>Tracking Number</strong>
		  <input id="demo_unit_serial" name="tracking_number" placeholder="Tracking Number" class="form-control input-md" type="text" value=""><br /><br>
		  
		  <strong>Demo Return Date</strong>
		  <input id="return_date" name="return_date" placeholder="Demo Return Date" class="form-control input-md" type="text" value="<?=date('m/d/Y', strtotime('+3 weeks'))?>"><br />
		  
		  <script>
		  $(function() {
			$( "#return_date" ).datepicker();
		  });
		  </script>
		  
		  
		  <strong>Demo Notes</strong>
		  <textarea id="shipping_notes" name="shipping_notes" placeholder="Notes" class="form-control input-md"></textarea><br /><br />
		  
		  <strong>Payment Recieved</strong>
		  <input id="payment_recieved" name="payment_recieved"  type="checkbox" value="1"> <input id="invoice_number" name="invoice_number"  type="text" placeholder="Invoice Number" value="">
		  
		  <br>
		  
		  <br>
		  
		   <strong>Message to Customer</strong>
		  <textarea id="shipping_customer_message" name="shipping_customer_message" placeholder="Message to Customer" class="form-control input-md" style="height: 300px"><?=$shipping_customer_message?></textarea><br /><br />
		  
		  <br />
		  
		  
		  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="mark_as_shipped();">Mark As Shipped</button>
		<script>
			function mark_as_shipped() {
				$.post('<?=root()?>exec/demo/?mark_as_shipped=true&id=<?=$_GET['id']?>', $('#ship_wing_form').serialize(), null, 'script');
			}
		</script>
      </div>
    </div>

  </div>
  </form>
</div>	


<div id="ShippingInfoModal" class="modal fade" role="dialog">
  <form class="form-horizontal" action="" method="post" id="ship_info_form">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Shiping Info</h4>
      </div>
      <div class="modal-body">
		  Use the following information to generate a shipping label:
		  <br><br>
		  <?=$d['name']?>
		  <br>
		  <?=$d['address']?>
		  <br>
		  <?=$d['city'].', '.$d['state'].' '.$d['zip']?>
		  <br>
		  <?=$d['country']?>
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
				$demo_pool = mysqli_query($link,'SELECT * FROM demo_pool WHERE id=\''.sf($d['requested_demo_pool']).'\' ORDER BY name ASC');
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
		  
		  
		  <script>
		  function add_demo_unit() {
			$.post('<?=root()?>exec/demo/?add_demo_unit=true', $('#new_demo_unit_form').serialize(), null, 'script');
		  }
		  </script>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="add_demo_unit();">Add Demo Canopy</button>
      </div>
    </div>

  </div>
  </form>
</div>	


<script>

function view_step(id, mark_as) {
	
	document.location='<?=root()?>?page=project_step&id=<?=$_GET['id']?>&get_step='+id+'&mark_as='+mark_as;
	
}

function demo_status(status) {

	//send message to customer with approval or denial info	

}

function ship_wing() {
	$('#ShipWingModal').modal('show');
}

function recieve_wing() {
	$('#RecieveWingModal').modal('show');
}


</script>
	
<style>
.chosen-container{
  width: 100% !important;
}


/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 70vh;
    overflow-y: auto;
}
</style>

