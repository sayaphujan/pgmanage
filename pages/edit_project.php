<?php

$pq = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');
$project = mysqli_fetch_assoc($pq);
$meta = json_decode($project['metadata'], true);
$global_vars = json_decode($project['global_vars'], true);

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

if(isset($_POST['global_vars']['Cup (Female Only)'])){
    $_POST['global_vars']['Cup'] = $_POST['global_vars']['Cup (Female Only)'];
}

if($_POST['project_name']) 
{
	$global_vars = json_decode($project['global_vars'], true);

	foreach($global_vars as $g_key=>$group) {
		foreach($group['vars'] as $var_name=>$var) {
			$global_vars[$g_key]['vars'][$var_name]=$_POST['global_vars'][$var_name];
		}
	}
	
	
	$meta['parts'] = $_POST['part_variable'];
	
	$que = 'UPDATE projects SET `customer`= \''.sf($_POST['customer']).'\'
	                        , `product`= \''.sf($_POST['product']).'\'
	                        , `name`= \''.sf($_POST['project_name']).'\'
	                        , `location`= \''.sf($_POST['location']).'\'
	                        , `serial`= \''.sf($_POST['serial']).'\'
	                        , `payment`= \''.sf($_POST['payment']).'\'
	                        , `colors`= \''.sf($_POST['colors']).'\'
	                        , `notes`= \''.sf($_POST['notes']).'\'
	                        , `estimated_completion`= \''.sf(date('Y-m-d h:i:s' ,strtotime($_POST['date']))).'\'
	                        , `priority`= \''.sf($_POST['priority']).'\'
	                        , `pod`= \''.sf($_POST['pod']).'\'
	                        , `metadata`=\''.sf(json_encode($meta)).'\'
	                        , `global_vars`=\''.sf(json_encode($global_vars)).'\' 
	                        WHERE id=\''.sf($_GET['id']).'\'';
	                        //echo $que;
$update = mysqli_query($link, $que);

	if($update){
	   // call_designer_to_edit_project(sf($_GET['id']));
	    $_SESSION['success'] = 'Project has been updated successfully!';
	} else {
	    $_SESSION['error']   = 'Error occured when saving project';
	}
	                        
	
	$part_q = mysqli_query($link, 'SELECT project_parts.*
	                                    , product_parts.batch_lot as capture_batch_lot
	                                    , product_part_categories.name as category_name
	                                    , product_part_categories.c_order as c_order 
	                                    FROM project_parts 
	                                    LEFT JOIN product_parts ON project_parts.part = product_parts.id  
	                                    LEFT JOIN product_part_categories ON product_part_categories.id = project_parts.category_id 
	                                    WHERE `project`=\''.sf($project['id']).'\' 
	                                    ORDER BY c_order, name ASC');
		
	
	while($part = mysqli_fetch_assoc($part_q)) 
	{ 
		$part_vars = json_decode($part['variables'], true);
		
		if(is_array($part_vars))
		{
    		foreach($part_vars as $key=>$var) 
    		{
    			$part_vars[$key]['value'] = sf($_POST['part_var_'.$part['id']][$var['name']]);
    		}
    
    		mysqli_query($link, 'UPDATE project_parts SET
    		                        `batch_lot`=\''.sf($_POST['batch_lot_'.$part['id']]).'\'
    		                      , `variables`=\''.sf(json_encode($part_vars)).'\' 
    		                      WHERE id=\''.sf($part['id']).'\'');
		}
	}
	
	
	//header('location: '.root().'?page=project&id='.$_GET['id']);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=project&id='.$_GET['id'].'">';
	exit();
	
}

if($_GET['delete'] && $_SESSION['type']=='admin') {
	mysqli_query($link, 'UPDATE projects SET `status`= \'deleted\' WHERE id=\''.sf($_GET['id']).'\'');
	//header('location: '.root().'?page=projects');
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=projects">';
	exit();
}


?>
	
<style>
    select.read-only{
        pointer-events: none;
        background-color: lightgrey;
    }
</style>
<form class="form-horizontal" action="" method="post">
<fieldset>

<legend>
	<div class="col-md-6"><b>Edit Project - <?=$project['name']?> - Serial # <?=$project['serial']?></b></div>
	
	<div class="col-md-12 text-right">
	    <input type="button" class="btn btn-primary" style="margin-bottom: 5px" onclick="javascript:$('#submit').click();" value="Save Order">
		<input type="button" class="btn btn-success" style="margin-bottom: 5px" onclick="document.location='<?=root()?>?page=project&id=<?=$_GET['id']?>';" value="View Order">
		<input type="button" class="btn btn-info" style="margin-bottom: 5px" onclick="document.location='<?=root()?>?page=upload_project_data&id=<?=$_GET['id']?>';" value="Upload Order Data">
		<input type="button" class="btn btn-default" style="margin-bottom: 5px" onclick="document.location='<?=root()?>?page=generate&id=<?=$_GET['id']?>';" value="Re-generate Document">
		<? if($_SESSION['type']=='admin') { ?>
			<input type="button" class="btn btn-danger" style="margin-bottom: 5px" onclick="if(confirm('Are you sure you want to delete this project?')) { document.location='<?=root()?>?page=edit_project&id=<?=$_GET['id']?>&delete=true'; }" value="Delete Project">
		<? } ?>
		</div>
</legend>

<div class="form-group">
  <label class="col-md-4 control-label" for="name">Customer</label>  
  <div class="col-md-4">
  <select id="customer" name="customer" class="form-control input-md" required="">
  <option></option>
  <option value="new">New Customer</option>
  <?
  $customers = mysqli_query($link, 'SELECT * FROM customers ORDER BY name ASC');
  while($customer = mysqli_fetch_assoc($customers)) {
		echo '<option value="'.$customer['id'].'"'.($project['customer']==$customer['id'] ? ' selected="selected"' : '').'>#'.$customer['id'].' - '.$customer['name'].' - '.$customer['city'].', '.$customer['state'].', '.$customer['country'].'</option>';
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
	});
	
	</script>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="product">Product</label>  
  <div class="col-md-4">
  <!-- <select id="product" name="product" class="form-control input-md" required="">-->
  <select id="product" name="product" class="form-control input-md read-only">
  <option></option>
  <?
  $products = mysqli_query($link, 'SELECT * FROM products ORDER BY name ASC');
  while($product = mysqli_fetch_assoc($products)) {
		echo '<option value="'.$product['id'].'"'.($project['product']==$product['id'] ? ' selected="selected"' : '').'>'.$product['name'].'</option>';
  }
  ?>
  </select>
  <script>
	$(function() {
		$('#product').change(function() {
		    
		    var product = "<?=$project['product'];?>";
		    var selected = $("#product").val();
		    var id = "<?=$project['id'];?>";
		    
		    if(selected != product){
			    alert("Sorry we can't sync the order because product setting doesn't match, please delete Project #"+id+" in Manage System, then try again");
			    $("#product").val(product);
		    }
		    
			$('#project_name').val($("#product option:selected").text());
			
		});
	});
	
	</script>
  </div>
</div>



<div class="form-group">
  <label class="col-md-4 control-label" for="project_name">Project Name</label>  
  <div class="col-md-4">
  <input id="project_name" name="project_name" placeholder="ProjectName" class="form-control input-md" required="" type="text" value="<?=$project['name']?>">
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="date">Desired Completion Date</label>  
  <div class="col-md-4">
  <input id="date" name="date" placeholder="Desired Completion Date" class="form-control input-md" required="" type="text" value="<?=date('m/d/Y',strtotime($project['estimated_completion']))?>">
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
  <input id="serial" name="serial" placeholder="Serial Number" class="form-control input-md" required="" type="text" value="<?=$project['serial']?>">
  </div>
  <script>
      
	 var timer = null;
    $('#serial').keyup(function() {
        clearTimeout(timer);
        var $this = $(this);
        var id = '<?=$project['id']?>';
    
        timer = setTimeout(function() {
            var serial = {'serial' : $this.val(), 'id' : id}
            
            if (serial != '') {
                //alert(serial);
                $.post('<?php echo root("do/check_serial_ajax/"); ?>', serial, function(result){
                    if(result == 1){
                        $.notify('Serial Number Already Exists', 'error')
                    }
                    else{
                        $.notify('Serial Number Available', 'success')
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
  <input id="location" name="location" placeholder="Manufacturing Location" class="form-control input-md" required="" type="text" value="<?=$project['location']?>">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="pod">Production Cycle</label>  
  <div class="col-md-4">
  <input id="pod" name="pod" placeholder="POD" class="form-control input-md" required="" type="text" value="<?=$project['pod']?>">
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="priority">Priority</label>  
  <div class="col-md-4">
  <select id="priority" name="priority" class="form-control input-md" required="">
	<option value="Low" <?=($project['priority']=='Low' ? ' selected="selected"' : '')?>>Low</option>
	<option value="Standard" <?=($project['priority']=='Standard' ? ' selected="selected"' : '')?>>Standard</option>
	<option value="Critical" <?=($project['priority']=='Critical' ? ' selected="selected"' : '')?>>Critical</option>
  </select>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="location">Payment</label>  
  <div class="col-md-4">
  <textarea id="payment" name="payment" placeholder="Payment Information" class="form-control input-md"><?=$project['payment']?></textarea>
  </div>
</div>

<a name="parts"></a>
<div class="form-group">
  <label class="col-md-4 control-label" for="location">Notes</label>  
  <div class="col-md-4">
  <textarea id="notes" name="notes" placeholder="Project Notes" class="form-control input-md"><?=$project['notes']?></textarea>
  </div>
</div>

<h3>Global Vars</h3>

<?
$global_vars = json_decode($project['global_vars'], true);

if(is_array($global_vars)){
foreach($global_vars as $g_key=>$group) {
	?>
	<div class="row">
		<h4><?=$group['name']?></h4>
		<div class="row">
		<?
        if(is_array($group['vars'])){		
		foreach($group['vars'] as $var_name=>$var) {
		    if($var_name=='Cup'){ $var_name = 'Cup (Female Only)'; }
		?>
			
			<div class="col-md-12">
				<div class="col-md-3 text-right">
					<label class="control-label"><?=$var_name?></label>
					
				</div>
				<div class="col-md-9">
				<input name="global_vars[<?=$var_name?>]" placeholder="<?=$var_name?>" class="form-control input-md" type="text" value="<?=$var?>">
				</div>
			</div>
			
		<? } } ?>
		</div>

		<div class="clear"></div>
	</div>
	
	<?
} }
?>

<h3>Parts</h3>
		<?
		$part_q = mysqli_query($link, 'SELECT project_parts.*, product_parts.batch_lot as capture_batch_lot, product_part_categories.name as category_name, product_part_categories.c_order as c_order FROM project_parts LEFT JOIN product_parts ON project_parts.part = product_parts.id  LEFT JOIN product_part_categories ON product_part_categories.id = project_parts.category_id WHERE `project`=\''.sf($project['id']).'\' ORDER BY c_order, name ASC');
				
				
		$cur_cat = '';
		?>
		
		
		<? 
		while($part = mysqli_fetch_assoc($part_q)) { 
		
			if($cur_cat!==$part['category_name']) {
				
				echo '<h3>'.$part['category_name'].'</h3>
				
				
				<div class="row parts-table parts-table-heading row-eq-height">
					<div class="col-md-2 part-heading">Part Name</div>
					<div class="col-md-4 part-heading">Batch Lot</div>
					<div class="col-md-6 part-heading">Part Info</div>
				</div>
				';
				
				$cur_cat = $part['category_name'];
			}
		?>
			<div class="row parts-table parts-table-data">
				<div class="col-md-2"><?=$part['name']?></div>
				<div class="col-md-4">
				    <?php //$part['batch_lot'].'<br/>'.$part['capture_batch_lot'].'<br/>'.$part['id'].'<br/>';?>
					<? if(!empty($part['batch_lot']) || $part['capture_batch_lot']==1) { ?>
						<input type="input" class="form-control input-md step_input" placeholder="Batch Lot #" name="batch_lot_<?=$part['id']?>" value="<?=$part['batch_lot']?>">
					<? } else { ?>
						Not required
					<? } ?>
					</div>
				<div class="col-md-6 col-xs-12 project_substep_parts">
				<?
				$part_vars = json_decode($part['variables'], true);
				
				$i = 1;
				if(is_array($part_vars)){
				  foreach($part_vars as $key=>$var) {
					//if(!empty($var['value'])) {
						echo '';
						echo '<div class="col-md-6 col-sm-6">'.$var['name'].'</div>';
						echo '<div class="col-md-6 col-sm-6"><input type="text" class="form-control input-md" name="part_var_'.$part['id'].'['.$var['name'].']" value="'.$var['value'].'" placeholder="'.$var['name'].'"></div>';
						echo '';
						$i++;
					//}
				  }
				}
				?>
				
				</div>
			</div>
		<? } ?>

<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary">Save</button>
  </div>
</div>



</fieldset>
</form>

