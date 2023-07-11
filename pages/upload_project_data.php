<?

$pq = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');

$project = mysqli_fetch_assoc($pq);

$meta = json_decode($project['metadata'], true);
$global_vars = json_decode($project['global_vars'], true);


if($_POST['profile']) {
	//if post profile = glidehc
	
	    if($_FILES['csv']['tmp_name']) {
		
		    $csv = array_map('str_getcsv', file($_FILES['csv']['tmp_name']));
		
		    array_walk($csv, function(&$a) use ($csv) {
			  $a = array_combine($csv[0], $a);
			});
			
			array_shift($csv);
			
			
			$order_data = $csv[0];
			
			//echo nl2br(print_r($order_data, true)); exit();
			//MATCH vars 
			
			
			
			    //$global_vars[0]['vars']['Name'] = $order_data['Dealer_Customer_Name_Reference'];
    			$global_vars[0]['vars']['Name'] = $order_data['Dealer_Name'];
    			$global_vars[0]['vars']['Height'] = $order_data['Height'];
    			$global_vars[0]['vars']['Weight'] = $order_data['Weight'];
    			$global_vars[0]['vars']['Chest'] = $order_data['Chest'];
    			$global_vars[0]['vars']['Torso'] = $order_data['Torso'];
    			$global_vars[0]['vars']['Waist'] = $order_data['Waist'];
    			$global_vars[0]['vars']['Thigh'] = $order_data['Thigh'];
    			$global_vars[0]['vars']['Inseam'] = $order_data['Inseam'];
    			$global_vars[0]['vars']['Cup'] = $order_data['Cup Size (Female Only)'];
    			$global_vars[0]['vars']['Gender'] = $order_data['Gender_CB'];
    			
    			//$global_vars[1]['vars']['Container Size'] = $order_data['Model Size'];
    			$global_vars[1]['vars']['Container Size'] = $order_data['Main_Container_Size'];
    			$global_vars[1]['vars']['Yoke Size'] = $order_data['Yoke_Size'];
    			//$global_vars[1]['vars']['MLW Type'] = $order_data['Main_Left_Web_Type'];
    			$global_vars[1]['vars']['Hardware Type'] = $order_data['Harness_Hardware_Type'];
    			$global_vars[1]['vars']['MLW Size'] = $order_data['Main_Lift_Web_size'];
    			$global_vars[1]['vars']['Lateral Size'] = $order_data['Lateral_Size'];
    			$global_vars[1]['vars']['Leg Pad Size'] = $order_data['Leg_Pad_Size'];
    			$global_vars[0]['vars']['Offset'] = $order_data['Offset'];
    			$global_vars[1]['vars']['MLW Configuration'] = $order_data['Main_Left_Web_Type'];
    			$global_vars[1]['vars']['Main Canopy'] = $order_data['Canopy_Type'];
    			$global_vars[1]['vars']['Reserve Canopy'] = $order_data['Reserve_Type'];
    			$global_vars[1]['vars']['RSL'] = $order_data['Reserve_Static_Line'];
    			$global_vars[1]['vars']['Hardware Type'] = $order_data['Harness_Hardware_Type'];
    			$global_vars[1]['vars']['Chest Strap Size'] = $order_data['Chest_Strap_Width'];
    			$global_vars[1]['vars']['Chest Strap Type'] = $order_data['Chest_Strap_Type'];
    			$global_vars[1]['vars']['Webbing Color'] = $order_data['Webbing_Color'];
    			$global_vars[1]['vars']['Base Ring Size'] = $order_data['Base_Ring_Size'];
    			$global_vars[1]['vars']['Thread Color'] = (!empty($order_data['Contrasting_Thread_Color']) ? $order_data['Contrasting_Thread_Color'] : $order_data['Binding_Tape_2_Color']);
    			$global_vars[1]['vars']['Pinstripe Color'] = $order_data['Pinstripe_6_Color'];
    			$global_vars[1]['vars']['Fabric Type CB'] = $order_data['Fabric_Type_CB'];
    			
    			$global_vars[2]['vars']['Fit Right Reserve Handle Color'] = $order_data['Reserve_Handle_Color'];
    			$global_vars[2]['vars']['Reserve Static Line Type'] = $order_data['Reserve_Static_Line'];
    			$global_vars[2]['vars']['Main PC Handle Type'] = $order_data['Main_Pilot_Chute_Handle'];
    			$global_vars[2]['vars']['Main PC Handle Color'] = $order_data['MPCH_Color_1'].' '.$order_data['MPCH_Color_2'].' '.$order_data['MPCH_Color_3'];
    			$global_vars[2]['vars']['Release Handle Color'] = $order_data['Release_Handle_Color'];
    			//$global_vars[2]['vars']['Reserve Handle Options'] = $order_data['Reserve_Handle_Options'];
    			$global_vars[2]['vars']['Reserve Deployment handle type'] = $order_data['Reserve_Handle_Options']; 
    			$global_vars[2]['vars']['Main Riser Length'] = $order_data['Main_Riser_Length'];
    			$global_vars[2]['vars']['Main Riser Type'] = $order_data['Main_Riser_Type'];
    			$global_vars[2]['vars']['Main Riser Color'] = $order_data['Main_Riser_Color'];
    			$global_vars[2]['vars']['Main Pilot Chute Type'] = $order_data['Main_Pilot_Chute_Type'];
    			//$global_vars[2]['vars']['Main_Deployment_CB'] = $order_data['Main_Deployment_CB'];
    			$global_vars[2]['vars']['Wing_Suit_Option'] = $order_data['Wing_Suit_Option'];
    			//$global_vars[2]['vars']['Wing_Suit_Bridle_Length_CB'] = $order_data['Wing_Suit_Bridle_Length_CB'];
    			$global_vars[2]['vars']['Reserve Cap Color'] = $order_data['Reserve_PC_Top_Cap_Color'];
    			$global_vars[2]['vars']['Bridle Length'] = $order_data['Bridle_Length'];
    			$global_vars[2]['vars']['Main Deployment Bag Type'] = $order_data['Main_Deployment_CB'];
			
			
			
			mysqli_query($link, 'UPDATE projects SET `global_vars`=\''.sf(json_encode($global_vars)).'\' WHERE id=\''.sf($_GET['id']).'\'');
			
			if(!empty($order_data['Production_Cycle'])) {
				mysqli_query($link, 'UPDATE projects SET `pod`=\''.sf($order_data['Production_Cycle']).'\' WHERE id=\''.sf($_GET['id']).'\'');
			}
			
			//echo nl2br(print_r($global_vars, true));
			//$order_data['Customer_Notes'];
			
			//I realized after designing the system with json vars, that this next part will be painful.. Oh well.
			$parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($_GET['id']).'\'');
			
			while($part = mysqli_fetch_assoc($parts)) {
				
				$update = false;
				
				$vars = json_decode($part['variables'], true);
				
				//echo $part['variables'];
				
				if (is_array($vars) || is_object($vars))
                {
    				foreach($vars as $key=>$var) {
    				
    					if(!empty($var['api_name'])) {
    					
    						if(!empty($order_data[$var['api_name']])) {
    							
    							$vars[$key]['value'] = $order_data[$var['api_name']];
    							
    							$update = true;
    							
    						}
    					}
    				}
                }
				
				if($update==true) {
				    //echo 'UPDATE project_parts SET variables=\''.sf(json_encode($vars)).'\' WHERE id=\''.$part['id'].'\'';
					mysqli_query($link, 'UPDATE project_parts SET variables=\''.sf(json_encode($vars)).'\' WHERE id=\''.sf($part['id']).'\'');
				}
				
				$vars = null;
			}
	    }
	
	//header('location: '.root().'?page=edit_project&id='.$_GET['id']);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=edit_project&id='.$_GET['id'].'">';
	
}



?>
	
<h2 class="form-signin-heading">Order Form Data Upload</h2>
<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" >

	<fieldset>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="profile">Profile</label>  
		  <div class="col-md-4">
		  <select id="profile" name="profile" class="form-control input-md" required="">
			<option value="glidehc">Glide HC Assembly</option>
		  </select>
		  </div>
		</div>


		<div class="form-group">
		  <label class="col-md-4 control-label" for="csv">CSV File</label>  
		  <div class="col-md-4">
		  <input id="csv" name="csv" placeholder="CSV File" class="form-control input-md" required="" type="file">
		  </div>
		</div>




		<div class="form-group">
		  <label class="col-md-4 control-label" for="submit"></label>
		  <div class="col-md-4">
			<button id="submit" name="submit" class="btn btn-primary">Save</button> <button name="skip" class="btn btn-info" onclick="document.location='<?=root()?>?page=edit_project&id=<?=$_GET['id']?>';">Skip</button>
		  </div>
		</div>



	</fieldset>
</form>

