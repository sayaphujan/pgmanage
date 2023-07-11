<?
if(!check_access('production')) exit();

$sql = 	mysqli_query($link, 'SELECT peregrine_id, peregrine_date_modified, product, status FROM projects WHERE id=\''.sf($_GET['id']).'\'');
$res = mysqli_fetch_assoc($sql);
    
    $id = $res['peregrine_id'];
    $date = $res['peregrine_date_modified'];
    $product = $res['product'];
    
    if($product == 96)
    {
        $output_folder = root().'/zip/Falkyn_order_';
    }
    else
    {
        $output_folder = root().'/zip/Glide_order_';
    }
    
if($res['status'] == 'started' && $res['peregrine_id'] > 0)
{
    //echo "<pre>";
    //print_r($res);
    //echo "<pre>";
    //die();
    
    $request = "https://design.peregrinemfginc.com/do/api_check_last_update/?id=$id";
    
    // Generate curl request
    $session = curl_init($request);
    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, 0);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    // obtain response
    $response = curl_exec($session);
    //print_r($response);
    curl_close($session);
    
    $json = json_decode($response, true);
    
    if($json['date_modified'] < $date){
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=render&id='.$id.'&s='.md5($id.'peregrin3!').'&product='.$product.'&p=project">';
    }
}

$pq = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');
$project = mysqli_fetch_assoc($pq);
$meta = json_decode($project['metadata'], true);

$pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($project['product']).'\'');
$product = mysqli_fetch_assoc($pq);

$cq = mysqli_query($link, 'SELECT * FROM customers WHERE id=\''.sf($project['customer']).'\'');
$customer = mysqli_fetch_assoc($cq);
$query = 'SELECT * FROM referrals WHERE referrals.project_id = '.sf($project['id']);
$que = mysqli_query($link, $query);
$res = mysqli_fetch_assoc($que);
                                
if(!empty($res['referrer_id'])) { $res['referrer_id'] = $res['referrer_id']; }else{ $res['referrer_id'] = 0; }
$query2 = 'SELECT * FROM referral_users WHERE id = '.sf($res['referrer_id']);
$que2 = mysqli_query($link, $query2);
$ref = mysqli_fetch_assoc($que2);

	        $global_vars = json_decode($project['global_vars'], true);
              
            foreach($global_vars as $index => $group) 
            {
              if($index == 0)
              {
                  foreach($group['vars'] as $var_name=>$val) 
                  {
                    if($var_name=='Name')
                    {
                        $parent_name = strtoupper($val);
                    }
                  }
              }
            }
		
?>


<form class="form-horizontal" id="product_form">
<fieldset>
<?

$status_label_class = 'text-warning';
if($project['status']=='completed') $status_label_class = 'text-success';
if($project['status']=='started') $status_label_class = 'text-primary';

$priority_label_class = '';
if($project['priority']=='Critical') $priority_label_class = 'text-danger';
if($project['priority']=='Low') $priority_label_class = 'text-muted';

?>
<div class="project_heading" style="padding-top: 25px">
		
	<div class="project_step_top_title col-md-3 bold">
		<?=$project['name']?>
	</div>
	
	<div class="col-md-1 text-danger text-center bold">
		Serial: <?=$project['serial']?>
	</div>
	
	<div class="col-md-1 text-success text-center bold">
		Status: <?=($project['status']=='' ? '' : $project['status'])?>
	</div>
	
	<div class="col-md-2 text-center bold">
	    Customer: <br/><?=strtoupper($customer['name'])?>
	</div>
	
	    <?php
	    if (strcmp(strtoupper($customer['name']), $parent_name) !== 0) {
            echo '<div class="col-md-2 text-center bold">Referral/Retailer:<br/><a onclick="document.location=\''.root().'?page=referral&id='.$ref['id'].'\'">'.$parent_name.'</a></div>';
        }
	    ?>
		
	<div class="col-md-2 text-center bold">
		Prod. Cycle: <?=$project['pod']?>
	</div>
	
	<div class="col-md-1 text-right project-edit-btn">
	
		<input type="button" class="btn" style="margin-bottom: 5px" onclick="document.location='<?=root()?>?page=edit_project&id=<?=$_GET['id']?>';" value="Edit Order">
		<input type="button" class="btn btn-warning" style="margin-bottom: 5px" onclick="document.location='<?=root()?>?page=copy_project&id=<?=$_GET['id']?>';" value="Duplicate Order">

	</div>
	
</div>


<div class="clear"></div>
<br/><br/>
<?php
if($project['status']=='completed') {
?>
<div class="form-group col-md-5">
  <label class="col-md-4 control-label" for="date">Date Completed</label>  
  <div class="col-md-6">
  <input type="hidden" id="project_id" value ="<?=$project['id'];?>">
  <!--<input id="date" name="date" placeholder="Date Completed" class="form-control input-md" required="" type="text" value="<?php /*echo $project['completed'];*/ ?>">-->
  <input id="date_completed" name="date" placeholder="yyyy-mm-dd hh:ii:ss" class="form-control input-md" required="" type="text" value="<?=$project['completed']; ?>">
  </div>
  
  <script>
  $(function() {
	$("#date").datepicker({
            //dateFormat: "yy-mm-dd",
            onSelect: function () {
                var date = $("#date").val();
                var id = $("#project_id").val();
                $.ajax({
                     type: "POST", //or GET. Whichever floats your boat.
                     url: "exec/update_date_completed/",
                     data: { date: date, id: id},
                     success: function(data) {
                         console.log(data);

                         //Write code here for you successful update (if you want to)
                     },
                     error: function() {
                         alert("Error.");
                     }
                });
            }
        });
  });
  </script>
<?php } ?>
</div>
<br/><br/>
<div class="panel panel-heading">
    <div class="panel-heading collapsed" data-toggle="collapse" data-target="#main-info" aria-expanded="true" style="background-color: #d8d8d8; cursor: pointer;">
        <h4>Main Information</h3>
    </div>
    <div class="panel-body collapse in" id="main-info" aria-expanded="false">
        <!--<ul class="nav nav-tabs">
            <li><a data-toggle="tab" href="#customer_tab">Customer Parts</a></li>
            <li><a data-toggle="tab" href="#harness_tab">Harness/Container</a></li>
            <li><a data-toggle="tab" href="#accparts_tab">Accessory Parts</a></li>
        	<li class="active"><a data-toggle="tab" href="#show_all">Show All</a></li>
        </ul>
        -->
        <div class="tab-content">
        <?
              $global_vars = json_decode($project['global_vars'], true);
              
            	foreach($global_vars as $index => $group) 
              {
                  //echo"<pre>";
                  //print_r($group);
                  //echo"</pre>";
                  
            	    if($index == 0)
                    {
            	        ?>
            	        <div id="customer_tab" class="tab-pane fade">
                    		<table class="table">
                    		
                    			<tbody>
                    			<? foreach($group['vars'] as $var_name=>$val) { if($var_name=='Cup'){ $var_name = 'Cup (Female Only)'; } if($var_name=='Offset'){ $var_name = '';$val=''; } 
                    			?>
                    				<tr>
                    					<td><?=$var_name?></td>
                    					<td><?=$val?></td>
                    				</tr>
                    			<? } ?>
                    
                    			</tbody>
                    		</table>
                    	</div>
                    	<?
            	    } else if($index == 1) {
            	        ?>
            	        <div id="harness_tab" class="tab-pane fade">
                    		<table class="table">
                    			<tbody>
                    			<? foreach($group['vars'] as $var_name=>$val) { 
                    			    echo '<tr>';
                    			        if($var_name == 'Base Ring Size' || $var_name == 'RSL'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else{
                    					    echo "<td>".$var_name."</td><td>".$val."</td>";
                    					}
                    				} ?>
                    
                    			</tbody>
                    		</table>
                    	</div>
                    	<?
            	    } else if($index == 2) {
            	        ?>
            	        <div id="accparts_tab" class="tab-pane fade">
                    		<table class="table">
                    			<tbody>
                    			<?
                    			    foreach($group['vars'] as $var_name=>$val) { 
                    			        echo '<tr>';
                    			        if($var_name == 'Main PC Handle Color 1' && $val=='None'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Main PC Handle Color 2' && $val=='None'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Main PC Handle Color 3' && $val=='None'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Pinstripe 8/7 Color' && empty($val)){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Pinstripe 7/9 Color' && empty($val)){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Pinstripe 9/9A Color' && empty($val)){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Main PC Handle Color'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Thread Color'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Pinstripe Color'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else{
                    					    echo "<td>".$var_name."</td><td>".$val."</td>";
                    					}
                    					echo '</tr>';
                    			    } ?>
                    			</tbody>
                    		</table>
                    	</div>
                    	<?
            	    }
            	}
            ?>
            <div id="show_all" class="tab-pane fade in active">
                <?
                $global_vars = json_decode($project['global_vars'], true);
                
                if(is_array($global_vars))
                {
                    $order = [
                                'Main Riser Type',
                                'Main Riser Color',
                                'Main Riser Length',
                                'Main Pilot Chute Type',
                                'Main PC Handle Type',
                                'Main PC Handle Color',
                                'Main PC Handle Color 1',
                                'Main PC Handle Color 2',
                                'Main PC Handle Color 3',
                                'Main Deployment Bag Type',
                                'Main Riser Ring Size',
                                'Main Riser HW Type',
                                'Bridle Length',
                                'Release Handle Color',
                                'Fit Right Reserve Handle Color',
                                'Reserve Static Line Type',
                                'Reserve Deployment handle type',
                                'Reserve Cap Color',
                                'Reserve Cap Binding Tape',
                                'Reserve Cap Thread Color',
                                'Reserve Cap Pinstripe',
                                'Binding Tape Color 1',
                                'Binding Tape Color 2',
                                'Thread Color 1',
                                'Thread Color 2',
                                'Main Closing System Type'
                              ];
                    
                    $group_accessory_part = array();
                    foreach($order as $index => $key)
                    {
                        $group_accessory_part[$key] = $global_vars[2]['vars'][$key];
                    }
                    $global_vars[2]['vars'] = $group_accessory_part;
                    
                
                    //echo "<pre>";
                    //print_r($global_vars[2]);
                    //print_r($group_accessory_part);
                    //echo "</pre>";
                    
                    foreach($global_vars as $g_key=>$group) 
                    {    
                        //echo "<pre>";
                        //print_r($group);
                        //echo "</pre>";
                ?>
                
                <div class="col-md-4">
                    <h4><?=$group['name']?></h4>
                    <table class="table">
                    
                        <tbody>
                        <? 
                        if($group['name'] == 'Customer')
                                {
                                    echo "<tr><td>Name</td><td>".$customer['name']."</td></tr>";
                                }
                        if(is_array($group['vars']) && $g_key != 3){
                            foreach($group['vars'] as $var_name=>$val) {
                                //print_r($group['vars']);
                                echo '<tr>';
                                        if($var_name=='Cup')
                                        { 
                                            $var_name = 'Cup (Female Only)'; 
                                        }else if($var_name == 'Main Riser Color'){
                                            if($val == '#F44E2F'){
                    					        echo "<td>".$var_name."</td><td>Neon Orange</td>";
                                            }else{
                                                echo "<td>".$var_name."</td><td>".$val."</td>";
                                            }
                    					}else if($var_name == 'RSL'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Base Ring Size'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Thread Color'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Pinstripe Color'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Main PC Handle Color'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Main PC Handle Color 1' && $val=='None'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Main PC Handle Color 2' && $val=='None'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Main PC Handle Color 3' && $val=='None'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Pinstripe 8/7 Color' && empty($val)){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Pinstripe 7/9 Color' && empty($val)){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Pinstripe 9/9A Color' && empty($val)){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Color' && $project['product'] == '96'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Binding Tape' && $project['product'] == '96'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Thread Color' && $project['product'] == '96'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else if($var_name == 'Reserve Cap Pinstripe' && $project['product'] == '96'){
                    					    echo "<td style='display:none'>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					}else  if($var_name=='Name'){
                    					    $parent_name = strtoupper($val);
                        					if($parent_name != strtoupper($customer['name'])){
                                                echo '<td>Referral/Retailer</td><td><a onclick="document.location=\''.root().'?page=referral&id='.$ref['id'].'\'">'.$parent_name.'</a></td>';
                        					}else{
                        					    echo "<td style='display:none'>Referral/Retailer</td><td style='display:none'>-</td>";
                        					}
                    					}else if($var_name=='Main Closing System Type'){
                    					    if($val == '')
                    					    {
                    					        echo "<td>".$var_name."</td><td>Grommets and Plastic</td>";   
                    					    }
                    					    else
                    					    {
                    					        echo "<td>".$var_name."</td><td style='display:none'>".$val."</td>";
                    					    }
                    					}else{
                    					    echo "<td>".$var_name."</td><td>".$val."</td>";
                    					}
                    					
                    			echo '</tr>';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    
                </div>
                <?
                }
                }
                ?>
              
              
                <div class="col-md-12">
                    <strong>Project Notes:</strong><br />
                    <?=$project['notes']?>
                    <br />
                    <br />
                    
                    <a target="_blank" href="<?php echo root('?page=final_design&id='.$project['id']); ?>" title="Click to view in fullscreen">
                        <div style="width: 100%; height: 480px; overflow: hidden;">
                            <div class="row common-design-container">
                                <div class="glide-box" id="html" contenteditable="">
                                    <img style="width: 100%" src="<?php echo $output_folder.$project['id'].'/final_design_'.$project['id'].'.svg?time='.time(); ?>">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
<div class="clear"></div>
<br/>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#main_tab">Production Workflow</a></li>
    <li><a data-toggle="tab" href="#parts_tab">Parts</a></li>
</ul>
	
<div class="tab-content">
	<div id="main_tab" class="tab-pane fade in active">
		<h4>Project Steps</h4>
		<?
		$steps = mysqli_query($link, 'SELECT project_steps.* FROM project_steps WHERE project=\''.sf($project['id']).'\' ORDER BY `order` ASC');


		while($step = mysqli_fetch_assoc($steps)) { 
			$meta = json_decode($step['metadata'], true);

			$step_btn = '';
			
			if($step['status']=='') { 
				$step_class = 'alert-warning'; 
				$step_label = 'Not Started'; 
				$step_btn = '<span class="pull-right"><button class="btn btn-success" onclick="view_step(\''.$step['id'].'\', \'started\');" type="button">Start</button>  <button class="btn" onclick="view_step(\''.$step['id'].'\');" type="button">View</button></span>';
				$step_subtext = '';
			}
			
			if($step['status']=='In Progress') { 
				$step_class = 'alert-info'; 
				$step_label = 'In Progress'; 
				$step_btn = '<span class="pull-right"><button class="btn btn-primary pull-right" onclick="view_step(\''.$step['id'].'\');" type="button">Continue</button></span>';
				$step_subtext = '
				<strong>Started: </strong>'.$step['started'].' 
				<strong>Completed: </strong> '.$step['completed'].'
				<strong>Total Time: </strong>'.floor($meta['timing']['total_time']/60).' Min
				'.(floor($meta['timing']['rework_time']/60)>0 ? '<div class="text-danger"><strong>Rework Time:</strong> '.floor($meta['timing']['rework_time']/60).' Min</div>' : '');
			}
			
			if($step['status']=='Completed') { 
				$step_class = 'alert-success'; 
				$step_label = 'Completed'; 
				$step_btn = '<span class="pull-right"><button class="btn pull-right" onclick="view_step(\''.$step['id'].'\');" type="button">View</button></span>';
				$step_subtext = '
				<strong>Started: </strong>'.$step['started'].' 
				<strong>Completed: </strong> '.$step['completed'].'
				<strong>Total Time: </strong>'.floor($meta['timing']['total_time']/60).' Min
				'.(floor($meta['timing']['rework_time']/60)>0 ? '<div class="text-danger"><strong>Rework Time:</strong> '.floor($meta['timing']['rework_time']/60).' Min</div>' : '');
			}
			
			?>
			<div class="product_step <?=$step_class?>">
			<h4>Step <?=($step['order']+1)?> - <?=$step['name']?> <?=$step_btn?></h4>
			
			<p><?=$step_subtext?></p>
			<div class="clear"></div>
			</div>

		<? } ?>

		<script>

		function view_step(id, mark_as) {
			
			document.location='<?=root()?>?page=project_step&id=<?=$_GET['id']?>&get_step='+id+'&mark_as='+mark_as;
		}
		
		</script>
	</div>
	
	<div id="parts_tab" class="tab-pane fade">
	
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
					<? 
					//attempt to look up a valid batch lot #
                                  //$batch_lot = get_next_batch_lot($part_vars);
                                    $part_vars = json_decode($part['variables'], true);
        						    $material = '';
        						    $color = '';
        						    
                                  
				    if(!empty($part['batch_lot']) || $part['capture_batch_lot']==1) { 
				        if(is_array($part_vars))
                        {
                              $batch_lot = $part['batch_lot'];
                              if(empty($batch_lot)) 
                              {
                                  
                                  foreach($part_vars as $key=>$var) {
        				                if($var['name'] == 'Material'){ $material = $var['value']; }
        				                if (strpos($var['name'], 'Color') !== false) { $color = $var['value']; }
        				                //echo $material.'--'.$color.'<hr/>';
        				                
        				                if(!empty($material) && !empty($color)){
        				                    
        				                    $lot = mysqli_query($link, 'SELECT material,color,lot_number,name FROM batch_lots WHERE archived=\'0\' AND material=\''.$material.'\' AND color=\''.$color.'\' AND lot_number != \'\' ORDER BY name ASC');
        				                    while($batch = mysqli_fetch_assoc($lot)) { 
        				                        
        				                        $batch_lot = $batch['lot_number'];
        				                        //echo $batch_lot;
        				                    }   
        				                }
        				            }
        				            
                                  if(!empty($batch_lot)) {
                                      $batch_lot_subtxt = '<div class="text-danger bold">Auto-populated</div>';
                                  } else {
                                      $batch_lot_subtxt = '';
                                  }
                              }
							
							//echo '<input type="input" class="form-control input-md step_input" placeholder="Batch Lot #" step_id="'.$part['id'].'" name="batch_lot" value="'.$batch_lot.'">';
							            if(!empty($batch_lot)){
            							    echo $batch_lot.'<br/>'.$batch_lot_subtxt;
            							}else{
            							    echo 'NONE';
            							}
							
                        }
				            
						    //echo "<pre>";
						    //print_r($part_vars);
						    //echo "</pre>";
					 } else { ?>
						Not required
					<? } ?>
					</div>
				<div class="col-md-6 col-xs-12 project_substep_parts">
				<?
				$part_vars = json_decode($part['variables'], true);
				
				$i = 1;
				if(is_array($part_vars)){
				    foreach($part_vars as $key=>$var) {
    				    if($var['name'] == 'Embroidery Logo' && $var['value'] != 'PMI' && $var['value'] != 'Glide' && $var['value'] != 'None' && $var['value'] != 'PMI TATTOO Logo' && $var['value'] != 'F TATTOO Logo' && $var['value'] != 'F Logo' && $var['value'] != 'FALKYN Logo') {
    				        //$var['value'] = '<a href="'.$output_forder.$project['id'].'/'.$var['value'].'" target="_blank">'.$var['value'].'</a>';
    				        $var['value'] = 'Custom Logo';
    				    }
    				    else if($var['name'] == 'Logo' && $var['value'] != 'PMI' && $var['value'] != 'Glide' && $var['value'] != 'None' && $var['value'] != 'PMI TATTOO Logo' && $var['value'] != 'F TATTOO Logo' && $var['value'] != 'F Logo' && $var['value'] != 'FALKYN Logo') {
    				        //$var['value'] = '<a href="'.$output_forder.$project['id'].'/'.$var['value'].'" target="_blank">'.$var['value'].'</a>';
    				        $var['value'] = 'Custom Logo';
    				    }
    					//if(!empty($var['value'])) {
    						echo '';
    						echo '<div class="col-md-6 col-sm-6 part-vars part-var-int">'.$var['name'].'&nbsp;</div>';
    						echo '<div class="col-md-6 col-sm-6 part-vars">'.$var['value'].'&nbsp;</div>';
    						$i++;
    					//}
    				}
				}
				
				?>
				
				</div>
			</div>
		<? } ?>
	
	</div>
</div>
	
</div>
<script src="<?php echo root();?>/js/svg-pan-zoom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.svg.min.js"></script>
<script>
    $(function(){
        //set height container design according to screen size
        //set height container design according to screen size
        //set height container design according to screen size
        $('.common-design-container').parent().height(550/1260*$('.common-design-container').parent().width())
        
        <?php if($_SESSION['success'] != ''){ ?>
        $.notify('<?php echo $_SESSION['success']; ?>', 'success')
        <?php unset($_SESSION['success']); } ?>
        
        <?php if($_SESSION['error'] != ''){ ?>
        $.notify('<?php echo $_SESSION['success']; ?>', 'error')
        <?php unset($_SESSION['error']); } ?>
    })
    //$(function() {
    //$('html, body').animate({
    //    scrollTop: $("#html").offset().top
    //}, 300);
    //
    //panZoomInstance = svgPanZoom('#glide', {
    //    zoomEnabled: true,
    //    controlIconsEnabled: true,
    //    mouseWheelZoomEnabled: false,
    //    fit: true,
    //    center: true,
    //    minZoom: 0.8,
    //    maxZoom: 4.8,
    //    zoomScaleSensitivity: 0.07,
    //    onZoom: function(){},
    //    onPan: function(){},
    //});
    ////zoom out
    //panZoomInstance.zoom(0.9);
    //
    //});
</script>