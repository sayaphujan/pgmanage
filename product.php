<?
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if(isset($_GET['add'])) {

if($_POST['name']) {
	echo '<br/>INSERT INTO products (`name`) VALUES (\''.sf($_POST['name']).'\')';
	
	mysqli_query($link, 'INSERT INTO products (`name`) VALUES (\''.sf($_POST['name']).'\')');
	header('location: '.root().'?page=product&id='.mysqli_insert_id($link));
	
}

?>

<form class="form-horizontal" action="" method="post">
<fieldset>

<legend>Add A Product</legend>

<div class="form-group">
  <label class="col-md-4 control-label" for="name">Product Name</label>  
  <div class="col-md-4">
  <input id="name" name="name" placeholder="Product Name" class="form-control input-md" required="" type="text">
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

	
<? 
} else {
if(isset($_POST['part_variable_name'])) {
    
    unset($_POST['part_variable_name']);
    foreach($_POST as $key=>$var){
        if($key== 'part_variable_name_'.$_GET['id']){
            $data['name'] = $var[0];
        }
        if($key== 'part_variable_apiname_'.$_GET['id']){
            $data['api_name'] = $var[0];
        }
    }
    
	$var = array();
    array_push($var,$data);

        $que = mysqli_query($link, 'SELECT variables FROM product_parts WHERE id=\''.sf($_GET['id']).'\'');
	    $res = mysqli_fetch_assoc($que);
        $var_parts = json_decode($res['variables'], true);
        
        if(!array($var_parts)){
            $var_parts = array();    
        }
        
        $final = array_merge($var_parts, $var);        
	    $add = 'UPDATE product_parts SET variables=\''.json_encode($final).'\' WHERE id=\''.sf($_GET['id']).'\'';
	    echo $add;
		$que = mysqli_query($link, $add);
		?>
		$(".save").attr('style','display:none');
		<?
}

if($_POST['name']) {
    
    foreach($_POST as $key=>$val){
        echo "<pre>";
        print_r($key);
        echo "</pre>";
    }
    
	$steps = array();

	for($i = 0; $i<count($_POST['steps']); $i++) {
		
		$step_id = $_POST['steps'][$i];
		$steps[$i]=array();
		$steps[$i]['variables'] = array();
		$steps[$i]['name'] = sf($_POST['step_name'][$i]);
		$step_meta = null; 
		$step_meta = array();
		
		for($x = 0; $x < count($_POST['step_variable_id_'.$step_id]); $x++) {
			
			$steps[$i]['variables'][] = array('name'=>sf($_POST['step_variable_'.$step_id][$x]), 'type'=>$_POST['step_variable_type_'.$step_id][$x], 'part'=>$_POST['step_variable_part_'.$step_id][$x], 'id'=>$_POST['step_variable_id_'.$step_id][$x]);
			
			$step_meta[] = array('name'=>sf($_POST['step_variable_'.$step_id][$x]), 'type'=>$_POST['step_variable_type_'.$step_id][$x], 'part'=>$_POST['step_variable_part_'.$step_id][$x], 'id'=>$_POST['step_variable_id_'.$step_id][$x]);

			mysqli_query($link, 'UPDATE product_sub_steps SET s_order=\''.$x.'\', `name`=\''.sf($_POST['step_variable_'.$step_id][$x]).'\', `part`=\''.sf($_POST['step_variable_part_'.$step_id][$x]).'\' WHERE id=\''.sf($_POST['step_variable_id_'.$step_id][$x]).'\'');
			
		}
		
		
		if($_POST['step_id'][$i]>0 && $_POST['step_id'][$i]!=='new') {
			mysqli_query($link, 'UPDATE product_steps SET name=\''.sf($_POST['step_name'][$i]).'\', metadata = \''.sf(json_encode($step_meta)).'\', `order`=\''.$i.'\', `email_template`=\''.sf($_POST['email_template'][$i]).'\' WHERE product=\''.sf($_GET['id']).'\' AND id=\''.sf($_POST['step_id'][$i]).'\'');
		} else {
		    
			mysqli_query($link, 'INSERT INTO product_steps (`name`, `product`, `order`, `metadata`, `email_template`) VALUES (\''.sf($_POST['step_name'][$i]).'\', \''.sf($_GET['id']).'\', \''.$i.'\', \''.sf(json_encode($step_meta)).'\', \''.sf($_POST['email_template'][$i]).'\')');
		}
	
	}
	
	$parts = array();

	for($i = 0; $i<count($_POST['part']); $i++) {
		$part_id = $_POST['part'][$i];
		
		$parts[$part_id]['name'] = $_POST['part_name_'.$part_id];
		
		$vars = null;
		$part_vars = null;
		
		for($x = 0; $x<count($_POST['part_variable_name_'.$part_id]); $x++) {
			
			$vars[]=array('name'=>$_POST['part_variable_name_'.$part_id][$x], 'api_name'=>$_POST['part_variable_apiname_'.$part_id][$x],);
		
		}
		
		$part_vars = json_encode($vars);
		
		echo '<br/>UPDATE product_parts SET `name`=\''.sf($_POST['part_name_'.$part_id]).'\', `batch_lot`=\''.sf($_POST['batch_lot_required_'.$part_id]).'\', `p_order`=\''.$i.'\', `variables`=\''.sf($part_vars).'\' WHERE id=\''.sf($part_id).'\'';
		
		mysqli_query($link, 'UPDATE product_parts SET `name`=\''.sf($_POST['part_name_'.$part_id]).'\', `batch_lot`=\''.sf($_POST['batch_lot_required_'.$part_id]).'\', `p_order`=\''.$i.'\', `variables`=\''.sf($part_vars).'\' WHERE id=\''.sf($part_id).'\'');
		
		
	}
	
	for($i = 0; $i<count($_POST['categories']); $i++) {
	
		$cat_id = $_POST['categories'][$i];
		
		echo '<br/>UPDATE product_part_categories SET `name`=\''.sf($_POST['category_name'][$i]).'\', `c_order`=\''.$i.'\' WHERE id=\''.sf($cat_id).'\'';
		
		mysqli_query($link, 'UPDATE product_part_categories SET `name`=\''.sf($_POST['category_name'][$i]).'\', `c_order`=\''.$i.'\' WHERE id=\''.sf($cat_id).'\'');
		
		
	}
	
	//global variables woot!
	
	$g_vars = array();

    if(isset($_POST['global_var_group'])){
	foreach($_POST['global_var_group'] as $key=>$g_id) {
		$g_vars[] = array('name'=>sf($_POST['var_group_name_'.$g_id]), 'vars'=>$_POST['global_var_name_'.$g_id]);
	}
	
	echo '<br/>UPDATE products SET name=\''.sf($_POST['name']).'\', `workflow`=\''.json_encode($steps).'\', `global_vars`=\''.json_encode($g_vars).'\' WHERE id=\''.sf($_GET['id']).'\'';
	
	mysqli_query($link, 'UPDATE products SET name=\''.sf($_POST['name']).'\', `workflow`=\''.json_encode($steps).'\', `global_vars`=\''.json_encode($g_vars).'\' WHERE id=\''.sf($_GET['id']).'\'');
    }
	//echo '<br/>alert(\'Product Saved\'); //document.location=\''.root().'?page=products\';';
	//product-saved-alert echo '<br/>$("#error_'.$_GET['i'].'").fadeOut("slow");'."\n";
	echo '$(".product-saved-alert").show();'."\n";
	echo '$(".product-saved-alert").fadeOut("slow");'."\n";
	//echo '<br/>$(".product-save-btn").removeClass("btn-primary").addClass("btn-success").html("Saved");'."\n";
	//echo '<br/>$(".product-save-btn").removeClass("btn-success").fadeIn(1000);'."\n";
	//echo '<br/>$(".product-save-btn").addClass("btn-primary").fadeIn(1000);'."\n";
	
	
	exit();
	
}

$pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($_GET['id']).'\'');

$product = mysqli_fetch_assoc($pq);

$workflow = json_decode($product['workflow'], true);

$global_vars = json_decode($product['global_vars'], true);

//$parts = json_decode($product['parts'], true);


$pq = mysqli_query($link, 'SELECT product_parts.id, product_parts.name, product_parts.p_order, product_part_categories.name as category FROM product_parts, product_part_categories WHERE product_parts.product=\''.sf($_GET['id']).'\' AND product_parts.category = product_part_categories.id ORDER BY product_part_categories.c_order, product_parts.p_order ASC');

$product_parts = array();
		
while($part = mysqli_fetch_assoc($pq)) {
	$product_parts[] = array('id'=>$part['id'], 'name'=>$part['name'], 'category'=>$part['category']);
}


function product_parts_select_options($selected_part) {
	//pick from global parts array to avoid doing a full query for each part
	global $product_parts;
	$html = '<option value="0">Select a part (optional)</option>';
	foreach($product_parts as $key=>$part) {
		$html .= '<option value="'.$part['id'].'" '.($part['id']==$selected_part ? 'selected="selected"' : '').'>'.$part['category'].' - '.$part['name'].'</option>';
	}
	
	return $html;
}

function documentation($step, $order) {
    global $link;
	$html = '';
	
    $q = mysqli_query($link, 'SELECT * FROM project_step_documents WHERE product=\''.sf($_GET['id']).'\' AND step=\''.sf($step).'\' AND m_order=\''.sf($order).'\'');

    if(mysqli_num_rows($q) > 0){
        while($r = mysqli_fetch_assoc($q)) {
    	//$doc[] = array('id'=>$r['id'], 'name'=>$r['name'], 'type'=>$r['type'], 'order'=>$r['m_order']);
    		$html .= '<b><a target="_blank" href="'.root().'media/documentation/'.$r['id'].'.'.$r['type'].'"><button type="button" class="btn" id="step_documentation_'.$order.'">'.$r['name'].'</button></a></b>&nbsp;[<a href="javascript: ;" onclick="remove_docm('.$r['id'].', '.$step.', '.$order.');">remove</a>]<br/>';
        }
    }
	return $html;
}

?>
	
<div id="step_parts_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Pick the default parts associated with this step</h4>
	  </div>
	  
	  <form class="form-horizontal" id="step_parts_form" action="" method="post">
		  <div class="modal-body" id="step_parts_container"></div>
	  </form>
	  
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary" onclick="save_step_parts()">Save</button>
	  </div>
	</div>

  </div>
</div>

<div id="step_documentation_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Upload MP4 files or PDFs</h4>
	  </div>
	  
		<div class="modal-body" id="step_documentation_container"></div>
	  
	  
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary"  data-dismiss="modal">Close</button>
	  </div>
	</div>

  </div>
</div>
	

<form class="form-horizontal" id="product_form">
	

<script>
$(function() {
    var $affixElement = $('div[data-spy="affix"]');
    $affixElement.width($('.container').width());
});
</script>

<div data-spy="affix" data-offset-top="0" style="">
	<div class="product_top">
		
		<div class="col-md-1 text-right product_top_title">Name:</div>
		<div class="col-md-4">
		
		<input id="name" name="name" placeholder="Product Name" class="form-control input-md" required="" type="text" value="<?=$product['name']?>">
		
		</div>
		
		<div class="col-md-4 text-center">
			
		</div>
		
		<div class="col-md-1 text-center">
			<div class="product-saved-alert text-success">
			Saved!
			</div>
		</div>
		
		
		
		<div class="col-md-1 text-right">
			<button class="btn btn-primary product-save-btn" onclick="save_product();" type="button">Save Product</button>
		</div>
			
		
	</div>

</div>


<div class="container-fluid">
<br />
<br />
	

	<fieldset>


	<!--Tabs-->
	<ul class="nav nav-tabs">

		<li class="active"><a data-toggle="tab" href="#main_tab">Steps</a></li>

		<li ><a data-toggle="tab" href="#parts_tab">Parts</a></li>
		
		<li ><a data-toggle="tab" href="#global-vars">Product Heading Info</a></li>
		
	</ul>


<div class="tab-content">
  <div id="main_tab" class="tab-pane fade in active">
	
		
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Enter a step name</h4>
			  </div>
			  <div class="modal-body">
				  <input id="new_step_name" name="new_step_name" placeholder="Step Name" class="form-control input-md" type="text" value=""><br />
				  <br />
				  <button type="button" class="btn btn-info" data-dismiss="modal" data-target="#myModal" onclick="add_product_step();">Add Step</button>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>

		  </div>
		</div>

		<div id="email_modal" class="modal fade" role="dialog">
		  <div class="modal-dialog">


			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Email template sent on step completion</h4>
			  </div>
			  <div class="modal-body">
			  
				  <input id="email_id" type="hidden">
				  
				  <textarea id="email_template" name="email_template" placeholder="Dear %name, ...." class="form-control input-md"></textarea>
				  
				  <br />
				  <br />
				  <button type="button" class="btn btn-info" onclick="save_email_template();">Save Template</button>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>

		  </div>
		</div>
		
		

		<h4>Product Workflow <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add Step</button></h4> 
		
		<ul id="product_steps">

			<?
			$product_steps = mysqli_query($link, 'SELECT * FROM product_steps WHERE product=\''.sf($_GET['id']).'\' ORDER BY `order` ASC');

			$i = 0;
			//for($i = 0; $i<count($workflow); $i++) {
			while($step = mysqli_fetch_assoc($product_steps)) {
				$workflow = json_decode($step['metadata'], true);
				
				echo '<br/><li class="product_step" id="product_step_'.$step['id'].'">
					<input type="hidden" name="steps[]" value="'.$step['id'].'">
					<input type="hidden" name="step_id[]" value="'.$step['id'].'">
					<input type="hidden" name="step_parts_'.$step['id'].'" value="'.$step['parts'].'">
					<input type="hidden" name="email_template[]" value="'.$step['email_template'].'" id="email_template_'.$step['id'].'">
						<h4>Step '.($i+1).' - <input type="text" placeholder="Step Name" name="step_name[]" value="'.$step['name'].'">
							<small>
								<button type="button" class="btn btn-primary" onclick="add_sub_step('.$step['id'].', \'checkbox\');">Add Sub Step</button>
								<button type="button" class="btn btn-warning" onclick="step_parts('.$step['id'].');">Assign Parts</button>
								<button class="btn btn-info" type="button" onclick="set_email('.$step['id'].');" >Completed Email Template</button>
								<button class="btn btn-danger" type="button" onclick="remove_step('.$step['id'].');">Remove Step</button>
							</small>
						</h4>
						<div class="step_inputs" id="product_step_inputs_'.$step['id'].'">';
				
				//for($x = 0; $x<count($workflow); $x++) {
				$sq = mysqli_query($link, 'SELECT * FROM product_sub_steps WHERE `product`=\''.sf($_GET['id']).'\' AND `step`=\''.$step['id'].'\' ORDER BY `s_order` ASC');
				$x = 0;
				while($sub_step = mysqli_fetch_assoc($sq)) {
				
					if($sub_step['type']=='checkbox') {
						echo '<br/><div class="step_input" id="sub_step_'.$sub_step['id'].'">
							<input type="hidden" name="step_variable_type_'.$step['id'].'[]" value="checkbox">
							<input type="hidden" name="step_variable_id_'.$step['id'].'[]" value="'.$sub_step['id'].'">
							
							<div>
								<div class="col-md-3">
									<label class="control-label">Checkbox Input</label> &nbsp; [<a href="javascript: ;" onclick="remove_sub_step('.$sub_step['id'].');">remove</a>]
									<br>
									<input name="step_variable_'.$step['id'].'[]" placeholder="Variable Name" class="form-control input-md" required="" type="text" value="'.$sub_step['name'].'">
								</div>
								
								<div class="col-md-3">
									<label class="control-label">Paired Part</label>
									<br>
									
									<select name="step_variable_part_'.$step['id'].'[]" placeholder="Part" class="form-control input-md parts-select" required="" type="text">
										'.product_parts_select_options($sub_step['part']).'
									</select>
								</div>
								
								<div class="col-md-2">
								    <label class="control-label">&nbsp;</label>
									<br>
									
									<button type="button" class="btn" onclick="step_documentation('.$step['id'].', '.$x.');">Documentation</button>
								</div>
								<div class="col-md-4">
								    <label class="control-label">&nbsp;</label>
									<br>
									'.documentation($step['id'], $x).'
								</div>
							</div>
							<div class="clear"></div>
								
							</div>';
					}
					
					if($sub_step['type']=='text') {
						echo '<br/><div class="step_input" id="sub_step_'.$sub_step['id'].'">
							
							<input type="hidden" name="step_variable_type_'.$step['id'].'[]" value="text">
							<input type="hidden" name="step_variable_id_'.$step['id'].'[]" value="'.$sub_step['id'].'">

							<div>
								<div class="col-md-4">
									<label class="control-label">Text Input</label> &nbsp; [<a href="javascript: ;" onclick="remove_sub_step('.$sub_step['id'].');">remove</a>]
									<br>
									<input name="step_variable_'.$step['id'].'[]" placeholder="Variable Name" class="form-control input-md" required="" type="text" value="'.$sub_step['name'].'">
								</div>
								
								<div class="col-md-4">
									<label class="control-label">Paired Part</label>
									<br>
									
									<select name="step_variable_part_'.$step['id'].'[]" placeholder="Part" class="form-control input-md parts-select" required="" type="text">
										'.product_parts_select_options($sub_step['part']).'
									</select>
								</div>
								
								
								
							</div>
							<div class="clear"></div>
							
							
						</div>';
					}
					
					$x++;
					
				}
				
				echo'</div></li>';
				$i++;
				
			}
			?>

		</ul>


	</div>

	<div id="global-vars" class="tab-pane fade">
		<h4>Product Heading Variables <button type="button" class="btn btn-info" onclick="add_global_var_group()">Add Variable Group</button></h4> 
		
		<ul id="product_global_vars">
		
		<?
		
		foreach($global_vars as $key=>$group) {
			$g_id = uni_id();
			?>
			<li class="product_vars" id="product_var_group_<?=$g_id?>">
			<input type="hidden" name="global_var_group[]" value="<?=$g_id?>">
				<h4>Group <input type="text" name="var_group_name_<?=$g_id?>" value="<?=$group['name']?>">
					<small>
						<button type="button" class="btn" onclick="add_global_var(<?=$g_id?>)">Add Variable</button>
						<button class="btn btn-danger" type="button" onclick="remove_global_var_group(<?=$g_id?>)">Remove Category</button>
					</small>
				</h4>
				<div class="part_inputs" id="var_inputs_<?=$g_id?>">
				<?
				
				foreach($group['vars'] as $vkey=>$var) {
					$v_id = uni_id();
				?>
					
					<div class="global_var_input col-md-12" id="global_var_<?=$v_id?>">
						<div class="col-md-6">
							<label class="control-label">Variable Name</label> &nbsp; [<a href="javascript: ;" onclick="remove_global_var('<?=$v_id?>');">remove</a>]
							<input name="global_var_name_<?=$g_id?>[]" placeholder="Variable Name" class="form-control input-md" required="" type="text" value="<?=$var?>">
						</div>
					</div>
					
				<? } ?>
				</div>
				<hr></hr>
				<div class="clear"></div>
			</li>
			
			<?
		}
		
		?>
		
		</ul>
		
	</div>
	  

	<div id="parts_tab" class="tab-pane fade">

	  
		<div id="add_part_modal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Enter a part category name</h4>
			  </div>
			  <div class="modal-body">
				  <input id="new_part_name" name="new_part_name" placeholder="Part Category Name" class="form-control input-md" type="text" value=""><br />
				  <br />
				  <button type="button" class="btn btn-info" data-dismiss="modal" data-target="#add_part_modal" onclick="add_part_category();">Add Part Category</button>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>

		  </div>
		</div>
		  
		<!--Parts  content-->
		<h4>Parts<h4>

		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_part_modal">Add Part Category</button>

		</h4> 
		<ul id="product_parts">

		<?

		$z = 0;
		$y = 0;

		//this is fucking messy, but fuck it.
		$cq = mysqli_query($link,'SELECT * FROM product_part_categories WHERE `product`=\''.sf($_GET['id']).'\' ORDER BY c_order ASC');

		while($c = mysqli_fetch_assoc($cq)) {
			$z++;
			?>
			
			<li class="product_part" id="part_category_<?=$c['id']?>">
				<input type="hidden" name="categories[]" value="<?=$c['id']?>">
					<h4>Category <input type="text" name="category_name[]" value="<?=$c['name']?>">
						<small>
							<button type="button" class="btn" onclick="add_part(this, <?=$c['id']?>)">Add Part</button>
							<button class="btn btn-danger" type="button" onclick="remove_part_category(<?=$c['id']?>)">Remove Category</button>
						</small>
					</h4>
					<div class="part_inputs" id="part_inputs_<?=$c['id']?>"><?
					
					//this feels like fucking amature hour here..
					$pq = mysqli_query($link, 'SELECT * FROM product_parts WHERE `product`=\''.sf($_GET['id']).'\' AND `category`=\''.sf($c['id']).'\' ORDER BY p_order ASC');
					
					while($p = mysqli_fetch_assoc($pq)) {
						$y++;
						?>
						<div class="part_input col-md-12" id="part_<?=$p['id']?>">
							<input type="hidden" name="part[]" value="<?=$p['id']?>">
							<div class="col-md-4">
								<label class="control-label">Part Name</label> &nbsp; [<a href="javascript: ;" onclick="remove_part('<?=$p['id']?>');">remove</a>]
								<input name="part_name_<?=$p['id']?>" placeholder="Part Name" class="form-control input-md" required="" type="text" value="<?=$p['name']?>">
							</div>
							<div class="col-md-4">
								<br />
								Require Batch Lot Number: <input type="checkbox" value="1" name="batch_lot_required_<?=$p['id']?>" <?=($p['batch_lot']==1 ? 'checked="checked"' : '')?>>
							</div>
							<div class="col-md-4">
								<strong>Available Variables</strong> [<a href="javascript: ;" onclick="add_part_variable(<?=$p['id']?>);">Add</a>]<br />
								
								
								<div id="part_variables_<?=$p['id']?>"><?
									
									$vars = json_decode($p['variables'], true);
									
									if(is_array($vars)){
									    $i=1;
									  foreach($vars as $key=>$var) {
									    if(!empty($var['name'])){
										?>
										<div class="row">
											<div class="row" id="part_variables_<?=$p['id']?>_<?=$i?>">
												<div class="col-md-3">
													<input name="part_variable_name_<?=$p['id']?>[]" type="text" placeholder="Part Variable Name" style="width: auto;" value="<?=$var['name']?>">
												</div>
												<div class="col-md-4">
													<input name="part_variable_apiname_<?=$p['id']?>[]" type="text" placeholder="API matching name" style="width: auto;" value="<?=$var['api_name']?>">
												</div>
												<div class="col-md-3 remove" style="float:right">
												    [<a href="javascript: ;" onclick="del_part_variable(<?=$p['id']?>, '<?=$var['name']?>', '<?=$var['api_name']?>', '<?=$i?>');">Remove</a>]
												</div>
											</div>
										</div>
										<?
									
									        
									    }
									    $i++;
									  }
									}
									
								?></div>
							</div>
							
							
							
						</div>
						<?
					
					}
					
					?></div>
					<button type="button" class="btn" onclick="add_part(this, <?=$c['id']?>)">Add Part</button>
					<div class="clear"></div>
			</li>
			
			
			<?
			
		}

		?>

		</ul>

		<script>


		var i = <?=$i?>;
		var z = <?=$z?>;
		var y = <?=$y?>;
		var g = <?=$y?>;
		
		function add_product_step() {
			$.get( "exec/product_options/?option=add_product_step&product=<?=$_GET['id']?>&name="+$('#new_step_name').val()+"&i="+i, function( data ) {
				$('#product_steps').append(data);
				$('#step_name').val('');
				i++;
			});
		
		}

		function remove_step(id) {
			if(confirm('Are you sure?')) {
				
				$.get( "exec/product_options/?option=remove_product_step&product=<?=$_GET['id']?>&id="+id , null, null, 'script');
				
			}
		}

		function add_sub_step(id, type) {
		
			$.get( "exec/product_options/?option=add_product_sub_step&product=<?=$_GET['id']?>&step="+id+"&type="+type, function( data ) {
				$('#product_step_inputs_'+id).append(data);
			});
			
		}

		function remove_sub_step(id) {
			if(confirm('Are you sure?')) {
				
				$.get( "exec/product_options/?option=remove_sub_step&product=<?=$_GET['id']?>&id="+id , null, null, 'script');
				
			}
		}
		
		function step_parts(id) {
			$.get( "exec/product_options/?option=step_parts&product=<?=$_GET['id']?>&step="+id, function( data ) {
				$('#step_parts_modal').modal('show');
				$('#step_parts_container').html(data);
			});
		}
		
		function step_documentation(id, order) {
			$.get( "exec/product_options/?option=step_documentation&product=<?=$_GET['id']?>&step="+id+"&order="+order, function( data ) {
				$('#step_documentation_modal').modal('show');
				$('#step_documentation_container').html(data);
			});
		}
		
		function remove_doc(id, step) {
			$.get( "exec/product_options/?option=remove_step_documentation&delete="+id, function( data ) {
				step_documentation(step);
			});
		}
		
		function remove_docm(id, step, order) {
			$.get( "exec/product_options/?option=remove_step_documentation&delete="+id, function( data ) {
				$('#step_documentation_'+order).remove();
			});
		}
		
		function save_step_parts() {
			$.post('exec/product_options/?option=save_step_parts&product=<?=$_GET['id']?>', $('#step_parts_form').serialize(), '', 'script');
		}
		
		function save_step_documentation() {
			$.post('exec/product_options/?option=save_step_documentation&product=<?=$_GET['id']?>', $('#step_documentation_form').serialize(), '', 'script');
		}

		function set_email(id) {
			$('#email_modal').modal('show');
			$('#email_id').val(id);
			$('#email_template').val($('#email_template_'+id).val());
		}

		function save_email_template() {
			$('#email_template_'+$('#email_id').val()).val($('#email_template').val());

			$('#email_modal').modal('hide');
			
			$('#email_id').val('');
			$('#email_template').val('');
			
			$('#email_modal').modal('hide');
		}

			





		function add_part_category() {
				
			$.get( "exec/product_options/?option=add_part_category&product=<?=$_GET['id']?>&name="+$('#new_part_name').val()+"&z="+z, function( data ) {
				$('#product_parts').append(data);
				$('#part_name').val('');
				z++;
			});
			
		}

		function remove_part_category(id) {
			if(confirm('Are you sure?')) {
				
				$.get( "exec/product_options/?option=remove_part_category&product=<?=$_GET['id']?>&id="+id , null, null, 'script');
				
			}
		}

		function add_part(obj, id) {
			$.get( "exec/product_options/?option=add_part&product=<?=$_GET['id']?>&id="+id+"&y="+y, function( data ) {
				//$(obj).parent().parent().parent().find('.part_inputs').append(data);
				$('#part_inputs_'+id).append(data);
				y++;
			});
		}


		function add_part_variable(id) {
			$.get( "exec/product_options/?option=add_part_variable&id="+id, function( data ) {
				$('#part_variables_'+id).append(data);
			});
		}
		
		function del_part_variable(id, name, api_name, order) {
			$.get( "exec/product_options/?option=del_part_variable&id="+id+"&name="+name+"&api_name="+api_name+"&order="+order, function( data ) {
			    console.log('#part_variables_'+id+'_'+order);
			    $('#part_variables_'+id+'_'+order).remove();
			});
		}
		
		function save_part_variable(id) {
		    $.post('<?=root()?>exec/product/?id='+id, $('#part_variable_form').serialize(), '', 'script');
		    $('.save').attr('style','display:none');    
		}
		

		function remove_part(id) {
			$.get( "exec/product_options/?option=remove_part&product=<?=$_GET['id']?>&part="+id , null, null, 'script');
		}
		
		function add_global_var_group() {
			$.get( "exec/product_options/?option=add_global_var_group", function( data ) {
				$('#product_global_vars').append(data);
			});
		}
		
		function remove_global_var_group(id) {
			$('#product_var_group_'+id).remove();
		}
		
		function add_global_var(group) {
			$.get( "exec/product_options/?option=add_global_var&id="+group, function( data ) {
				$('#var_inputs_'+group).append(data);
			});
		}
		
		function remove_global_var(id) {
			$('#global_var_'+id).remove();
		}

		
		function save_product() {
			$.post('<?=root()?>exec/product/?id=<?=$_GET['id']?>', $('#product_form').serialize(), '', 'script');
		}
		
		
		
		$( "#product_parts" ).sortable({});
		
		$( "#product_steps" ).sortable({});
		
		$( "#product_global_vars" ).sortable({});


		</script>

		<style>
		.product_steps { list-style-type: none; list-style:none; }
		.product_step { list-style:none; }

		.product_parts { list-style-type: none; list-style:none; }
		.product_part { list-style:none; border: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 10px; }
		
		.product_global_vars { list-style-type: none; list-style:none; }
		.product_vars { list-style:none; }
		</style>
  
  </div>
</div>

<br />
<br />
<div class="clear"></div>

<div class="form-group">
  <label class="control-label" for="submit"></label>
    <div class="row">
		<div class="col-md-2">
			<button class="btn btn-primary" onclick="save_product();" type="button">Save Product</button>
		</div>
		<div class="col-md-1">
			<div class="product-saved-alert txt-success bold none"> Saved!</div>
		</div>
		<div class="col-md-9" id="print">
		</div>
  </div>
</div>


</fieldset>

</div>
</form>
	
<? 
}
?>