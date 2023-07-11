<?
if(!check_access('production')) exit();

$sql = 	mysqli_query($link, 'SELECT peregrine_id, 
                                    peregrine_date_modified, 
                                    product 
                                FROM projects 
                                WHERE id=\''.sf($_GET['id']).'\'');
$res = mysqli_fetch_assoc($sql);

if($res['peregrine_id'] > 0)
{
    $id = $res['peregrine_id'];
    $date = $res['peregrine_date_modified'];
    $product = $res['product'];
    
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
       echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=render&id='.$id.'&s='.md5($id.'peregrin3!').'&product='.$product.'&p=project_step&get_step='.$_GET['get_step'].'&mark_as='.$_GET['mark_as'].'">';
    }
}
//echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=render&id='.$id.'&s='.md5($id.'peregrin3!').'&p=project_step&get_step='.$_GET['get_step'].'&mark_as='.$_GET['mark_as'].'">';
$pq = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');

$project = mysqli_fetch_assoc($pq);
$project_meta = json_decode($project['metadata'], true);

$pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($project['product']).'\'');
$product = mysqli_fetch_assoc($pq);

$cq = mysqli_query($link, 'SELECT * FROM customers WHERE id=\''.sf($project['customer']).'\'');
$customer = mysqli_fetch_assoc($cq);

//this part is kindof a hack, but it works
$step_q = mysqli_query($link, 'SELECT project_steps.*, 
                                      product_steps.email_template, 
                                      (SELECT COUNT(*) as sub_step_count 
                                          FROM project_sub_steps
                                          WHERE step=\''.sf($_GET['get_step']).'\') 
                                      as sub_step_count 
                                  FROM project_steps 
                                  LEFT JOIN product_steps ON project_steps.step = product_steps.id 
                                  WHERE project_steps.project=\''.sf($project['id']).'\' AND project_steps.id=\''.sf($_GET['get_step']).'\'');

$step = mysqli_fetch_assoc($step_q);

$step_meta = json_decode($step['metadata'], true);

//set progress
$ssq = mysqli_query($link, 'SELECT (SELECT COUNT(*) as completed FROM project_sub_steps WHERE project_sub_steps.step=\''.sf($step['id']).'\' AND project_sub_steps.completed=1) as completed, (SELECT COUNT(*) as completed FROM project_sub_steps WHERE project_sub_steps.step=\''.sf($step['id']).'\') as total');


$counts = mysqli_fetch_assoc($ssq);

if($counts['total'] != 0)
    $pct_complete = $counts['completed'] / $counts['total'];
else
    $pct_complete = 0;

$step_time = get_step_time($step['id']);


if($_GET['mark_as']=='started' && empty($step['status'])) 
{
	mysqli_query($link, 'UPDATE project_steps SET `status`=\'In Progress\', `started`=NOW() WHERE `project`=\''.sf($project['id']).'\' AND `id`=\''.sf($_GET['get_step']).'\'');
	mysqli_query($link, 'INSERT INTO project_log (`project`, `step`, `log`, `user`, `date`) VALUES (\''.sf($project['id']).'\', \''.sf($_GET['get_step']).'\', \'Step marked as in progress\', \''.sf($_SESSION['uid']).'\', NOW())');
	$step['status'] = 'In Progress';
	time_event($step['id'], 'start');
}

if($_GET['mark_as']=='Completed' && $_GET['act']=='resend') 
{
	if($_POST['email']) 
	{
		include '../inc/step_email.php';
		$email = get_step_email($_POST['email'], $_POST['images'], $_POST['pdfs']);
		
		if($_POST['send_email']==1) 
		{
		    sendHTML($customer['email'], 'Container Update', $email);
		    sendHTML('irawan.wijanarko@gmail.com', 'Container Update', $email);
		}
	}
	
	exit();
}


if($_GET['mark_as']=='Completed' && $step['status']=='In Progress') {
	
	if($_POST['email']) 
	{
		include '../inc/step_email.php';
		$email = get_step_email($_POST['email'], $_POST['images'], $_POST['pdfs']);
		
		if($_POST['send_email']==1) 
		{
		    sendHTML($customer['email'],'Container Update', $email);
		    sendHTML('irawan.wijanarko@gmail.com', 'Container Update', $email);
		}
	}
	
	
	time_event($step['id'], 'stop');
	
	$step_time = get_step_time($step['id']);
	$step_meta['timing']=array('total_time'=>$step_time['total_time'], 'rework_time'=>$step_time['rework_time']);
	
	mysqli_query($link, 'UPDATE project_steps SET `status`=\'Completed\', `completed`=NOW(), `metadata`=\''.sf(json_encode($step_meta)).'\' WHERE `project`=\''.sf($project['id']).'\' AND `id`=\''.sf($_GET['get_step']).'\'');
	mysqli_query($link, 'INSERT INTO project_log (`project`, `step`, `log`, `user`, `date`) VALUES (\''.sf($project['id']).'\', \''.sf($_GET['get_step']).'\', \'Step marked as completed\', \''.sf($_SESSION['uid']).'\', NOW())');
	mysqli_query($link, 'UPDATE projects SET last_step=\''.sf($step['name']).'\' WHERE `id`=\''.sf($project['id']).'\'');
	
	$check_steps = mysqli_query($link, 'SELECT (SELECT COUNT(*) FROM project_steps WHERE project=\''.sf($project['id']).'\' AND status=\'Completed\') as project_step_count, (SELECT COUNT(*) FROM project_steps WHERE project=\''.sf($project['id']).'\') as product_step_count ');
	
	$counts = mysqli_fetch_assoc($check_steps);
	if($counts['product_step_count'] == $counts['project_step_count']) 
	{
		mysqli_query($link, 'UPDATE projects SET status=\'completed\', completed = NOW() WHERE `id`=\''.sf($project['id']).'\'');
	}
	
	echo 'document.location=\''.root().'?page=project&id='.$_GET['id'].'\';';
	exit();
}

if($_GET['mark_as']=='Completed') 
{
	if($_POST['email']) 
	{
		include '../inc/step_email.php';
		$email = get_step_email($_POST['email'], $_POST['images'], $_POST['pdfs']);
		
		if($_POST['send_email']==1) 
		{
		    sendHTML($customer['email'],'Container Update', $email);
		    sendHTML('irawan.wijanarko@gmail.com', 'Container Update', $email);
		}
	}
	
	exit();
}



$vars = json_decode($step['metadata'], true);

$values = json_decode($step['step_values'], true);

function check_step_complete($variables, $values) {
	
	foreach($variables as $key=>$val) {
		if(empty($values[$val['name']])) {
			return false;
		}
	}
	
	return true;
}

if($_GET['save_step']) {

    $que = 'SELECT project_sub_steps.*, project_parts.name as part_name, project_parts.batch_lot, project_parts.variables as part_variables, project_parts.id as part_id, product_parts.batch_lot as capture_batch_lot FROM project_sub_steps LEFT JOIN project_parts ON project_sub_steps.part = project_parts.part AND project_parts.project = \''.sf($project['id']).'\' LEFT JOIN product_parts ON project_sub_steps.part = product_parts.id WHERE project_sub_steps.step=\''.sf($step['id']).'\' AND project_sub_steps.id=\''.sf($_GET['i']).'\'';
    $ssq = mysqli_query($link, $que);
	
    $sub_step = mysqli_fetch_assoc($ssq);
	
    if($_POST['name'] || $_POST['value'] || $_POST['notes'] || $_POST['batch_lot']) {
		
      if(empty($step['status'])) 
      {
          mysqli_query($link, 'UPDATE project_steps SET `status`=\'In Progress\', `started`=NOW() WHERE `id`=\''.sf($step['id']).'\'');
          
          mysqli_query($link, 'INSERT INTO project_log (`project`, `step`, `log`, `user`, `date`) VALUES (\''.sf($project['id']).'\', \''.sf($step['id']).'\', \'In progress\', \''.sf($_SESSION['uid']).'\', NOW())');
          
          echo '$("#step_started").html("'.date('Y-m-d h:i:s').'");'."\n";
          echo '$("#step_status").html("In Progress");'."\n";
          $step['status'] = 'In Progress';  
      }
		
	
		//mysqli_query($link, 'UPDATE project_sub_steps SET `value`=\''.sf($_POST['value']).'\', `notes`=\''.sf($_POST['notes']).'\' WHERE `project`=\''.$project['id'].'\' AND id=\''.sf($_GET['i']).'\'');
		
		if(!empty($_POST['batch_lot'])) {
			mysqli_query($link, 'UPDATE project_parts SET project_parts.batch_lot=\''.sf($_POST['batch_lot']).'\' WHERE project_parts.id=\''.sf($sub_step['part_id']).'\'');
		}
		
		mysqli_query($link, 'INSERT INTO project_log (`project`, `step`, `sub_step`, `log`, `user`, `date`) VALUES (\''.sf($project['id']).'\', \''.sf($_GET['get_step']).'\', \''.sf($_GET['i']).'\', \''.sf($_POST['name']).': '.sf($_POST['value']).' - Notes: '.sf($_POST['notes']).' Batch: '.sf($_POST['batch_lot']).'\', \''.sf($_SESSION['uid']).'\', NOW())');
		
		
		//echo '$("#save_'.$_GET['i'].'").hide();';
		//required value is not present. Do not complete
		
		if((!empty($_POST['value']) && (empty($_POST['batch_lot']) && $sub_step['capture_batch_lot']==1)) || empty($_POST['value'])) 
		{
			if(!empty($_POST['value'])) 
			{
				//echo '$("#error_'.$_GET['i'].'").html("Batch lot value is required to be marked as complete.");'."\n";
				//echo '$("#error_'.$_GET['i'].'").show();'."\n";
				echo '$.notify("part '.$sub_step['part_name'].' is missing, please check avaiable variable in product setting and try to resend the order", "error");';
				echo '$.notify("Please set batch lot number first for this material before completed this step", "error");';
				echo '$("#input_'.$_GET['i'].'").prop("checked", false);'."\n";
				echo 'updatecompletedbuttons();'."\n";
				if($_POST['value']==1) $_POST['value']='';
			}
			
			mysqli_query($link, 'UPDATE project_sub_steps SET `value`=\''.sf($_POST['value']).'\', `notes`=\''.sf($_POST['notes']).'\', completed=\'0\' WHERE `project`=\''.sf($project['id']).'\' AND id=\''.sf($_GET['i']).'\'');
			
			$success_html =  '';
			
		} elseif(!empty($_POST['value']) && (!empty($_POST['batch_lot']) || $sub_step['capture_batch_lot']==0)) {
		
			echo '$("#error_'.$_GET['i'].'").hide();'."\n";
			echo '$("#save_'.$_GET['i'].'").show();'."\n";
			echo '$("#save_'.$_GET['i'].'").fadeOut("slow");'."\n";
			
			if($sub_step['completed']==0) {
				mysqli_query($link, 'UPDATE project_sub_steps SET `value`=\''.sf($_POST['value']).'\', `notes`=\''.sf($_POST['notes']).'\', `completed`=\'1\', `completed_by`=\''.sf($_SESSION['name']).'\', `completed_time`=NOW() WHERE `project`=\''.sf($project['id']).'\' AND id=\''.sf($_GET['i']).'\'');
				
				mysqli_query($link, 'INSERT INTO project_log (`project`, `step`, `sub_step`, `log`, `user`, `date`) VALUES (\''.sf($project['id']).'\', \''.sf($_GET['get_step']).'\', \''.sf($_GET['i']).'\', \'Marked as complete\', \''.sf($_SESSION['uid']).'\', NOW())');
			
				$success_html =  'Completed on '.date('Y-m-d h:i:s').' by '.$_SESSION['name'];
				
			
			} else {
				
				mysqli_query($link, 'UPDATE project_sub_steps SET `value`=\''.sf($_POST['value']).'\', `notes`=\''.sf($_POST['notes']).'\' WHERE `project`=\''.sf($project['id']).'\' AND id=\''.sf($_GET['i']).'\'');
				
				$success_html = 'Completed on '.$sub_step['completed_time'].' by '.$sub_step['completed_by'];
				
			}
			
			//echo '$("#error_'.$_GET['i'].'").fadeOut("slow");'."\n";
		}
		
		//if(check_step_complete($vars, $values) && $step['status']=='In Progress') {
		//	echo '$("#mark_as_complete").show();';
		//}
	
	} else {
		echo '$("#error_'.$_GET['i'].'").fadeOut("slow");'."\n";
	}
	
	echo '$("#step_success_'.$_GET['i'].'").html("'.$success_html.' [<a href=\"javascript: ;\" onclick=\"show_step_log('.$_GET['i'].' ,\'sub_step\');\">Log</a>]");'."\n";
	
	//set progress
	$ssq = mysqli_query($link, 'SELECT (SELECT COUNT(*) as completed FROM project_sub_steps WHERE project_sub_steps.step=\''.sf($step['id']).'\' AND project_sub_steps.completed=1) as completed, (SELECT COUNT(*) as completed FROM project_sub_steps WHERE project_sub_steps.step=\''.sf($step['id']).'\') as total');
	
	
	$counts = mysqli_fetch_assoc($ssq);
	
	$pct = $counts['completed'] / $counts['total'];
	
	echo 'set_step_progress('.number_format(($pct*100), 2, '.', '').');'."\n";
	
	if($pct==1) {
		echo '$("#mark_as_complete").show();'."\n";
	} else {
		echo '$("#mark_as_complete").hide();'."\n";
	}
	
	if($_SESSION['timer_running']!==$step['id']) {
		
		echo 'start_timer('.$step_time['my_time'].');'."\n";
	}
	
	exit();
}

if($_GET['refresh_images']) {
    echo show_step_images($_GET['id'], $_GET['get_step']);
    exit();
}

if($_GET['delete_image']) {
    mysqli_query($link, 'DELETE FROM images WHERE id=\''.sf($_GET['delete_image']).'\'');
    echo '$("#image_'.$_GET['delete_image'].'").remove();';
    exit();
}

if($_GET['get_completed_message']) 
{
    $email = $step['email_template'];
    
    $que = mysqli_query($link, "SHOW columns FROM customers");
    while($row = mysqli_fetch_array($que)){
        $variable = '%'.$row['Field'].'';
        $text = $customer[''.$row['Field'].''];
        $email_replace = str_replace($variable, $text, $email);   
        $email = $email_replace;
    }
	
    $que = mysqli_query($link, "SHOW columns FROM projects WHERE field IN ('id','product','name','serial','notes','pod','started','completed','estimation_completion','assignment_notes')");
    while($row = mysqli_fetch_array($que)){
        if($row['Field'] == 'id'){ $row['Field'] = 'id_projects';}
        if($row['Field'] == 'name'){ $row['Field'] = 'product_name';}
        if($row['Field'] == 'product'){ $row['Field'] = 'id_product';}
        
        if($row['Field'] == 'id_projects'){
            $text = $project['id'];
        }else if($row['Field'] == 'product_name'){
            $text = $project['name'];
        }else if($row['Field'] == 'id_product'){
            $text = $project['product'];
        }else{
            $text = $project[''.$row['Field'].''];
        }
        $variable = '%'.$row['Field'].'';
        $email_replace = str_replace($variable, $text, $email);   
        $email = $email_replace;
    }
	//$email = str_replace('%name', $customer['name'], $step['email_template']);
	
	echo '<form id="message_form"><strong>Customer Email</strong><br /><textarea name="email" class="email_template">'.$email."\n\nSerial #: ".$project['serial'].'</textarea><br />
	<br />
	<strong>Send email message: <input type="checkbox" value="1" name="send_email" checked="checked"><br />
	<br />';
	
	//includes the pdf
	//includes the pdf
	//includes the pdf
  if($project['product'] == 96)
  {
      $design_final = root().'zip/Falkyn_order_'.$project['id'].'/Final_design_'.$project['id'].'.pdf';
      $design_data  = root().'zip/Falkyn_order_'.$project['id'].'/Falkyn_order_'.$project['id'].'.pdf';    
      $product_traveler = root().'zip/Falkyn_order_'.$project['id'].'/Product_Traveler_'.$project['id'].'.pdf';    
      $build_certificate = root().'zip/Falkyn_order_'.$project['id'].'/Build_Certificate_'.$project['id'].'.xlsx';    
  }
  else
  {
      $design_final = root().'zip/Glide_order_'.$project['id'].'/Final_design_'.$project['id'].'.pdf';
      $design_data  = root().'zip/Glide_order_'.$project['id'].'/Glide_order_'.$project['id'].'.pdf';
      $product_traveler = root().'zip/Glide_order_'.$project['id'].'/Product_Traveler_'.$project['id'].'.pdf';    
      $build_certificate = root().'zip/Glide_order_'.$project['id'].'/Build_Certificate_'.$project['id'].'.xlsx';    
  }
  $em_breakout = root().'?page=em_breakout&design_id='.$project['peregrine_id'].'&product='.$project['product'].'&time='.time().'</a>';
  
	echo '<br><strong>PDF to include:</strong><br />';
	echo '<input checked="checked" type="checkbox" name="pdfs[]" value="'.$design_final.'"> 
	        <a target="_blank" href="'.$design_final.'&time='.time().'">'.basename($design_final).'</a><br>';
	echo '<input checked="checked" type="checkbox" name="pdfs[]" value="'.$design_data.'"> 
	        <a target="_blank" href="'.$design_data.'&time='.time().'">'.basename($design_data).'</a><br>';
	echo '<input checked="checked" type="checkbox" name="pdfs[]" value="'.$design_data.'"> 
	        <a target="_blank" href="'.$product_traveler.'&time='.time().'">'.basename($product_traveler).'</a><br>';
	
	
	//includes the xls
	//includes the xls
	//includes the xls
	echo '<br><strong>Excel to include:</strong><br />';
	echo '<input checked="checked" type="checkbox" name="xls[]" value="'.$build_certificate.'"> 
	        <a target="_blank" href="'.$build_certificate.'&time='.time().'">'.basename($build_certificate).'</a><br>';
	
	//includes the images
	//includes the images
	//includes the images
	echo '<br><strong>Images to include:</strong><br />';
	
	$images = mysqli_query($link, 'SELECT * FROM images WHERE project=\''.sf($_GET['id']).'\' AND step=\''.sf($_GET['get_step']).'\'');
	
	$i = 0;
	while($img = mysqli_fetch_assoc($images)) 
	{
        echo '<div class="col-sm-2" id="image_'.$img['id'].'">
		        <a href="'.root().'media/images/'.$img['id'].'.png?time='.time().'" data-toggle="lightbox" data-gallery="multiimages" data-title="'.$img['name'].'">
		            <img src="'.root().'media/images/'.$img['id'].'.png?time='.time().'" class="img-responsive">
		        </a>
		        <p class="text-center"><input type="checkbox" name="images[]" value="'.$img['id'].'"></p>
		    </div>';
		
		$i++;
		
		if($i == 6) {
			$i = 0;
			echo  '<div class="clear"></div>';
		}

	}
	
	echo '<br><strong>Embroidery Breakout:</strong><br />';
	 echo '<div class="col-sm-2">
		        <input checked="checked" type="checkbox" name="em_breakout[]" value="em_breakout"> 
	        <a target="_blank" href="'.$em_breakout.'">Embroidery Breakout</a><br>
		    </div>';
	echo  '<div class="clear"></div></form>';
	echo '<button type="button" class="btn btn-success" onclick="send_message_complete()">Send</button>';
	echo '<div id="email-notif"></div>';
	exit();
}

if($_POST['engineering_message']) {
	
	
	mysqli_query($link, 'INSERT INTO engineering_messages (user, date, project, step, message, status) VALUES (\''.sf($_SESSION['uid']).'\', NOW(), \''.sf($_GET['id']).'\', \''.sf($_GET['get_step']).'\', \''.sf($_POST['engineering_message']).'\', \'active\');');
	
	$html = '<html><body>'.nl2br(sf($_POST['engineering_message'])).'</body></html>';
	
	sendHTML('dave@peregrinemfginc.com','New Engineering Message',$html);
	
	echo '$(\'#step_message\').modal(\'hide\');'."\n";
	echo '$(\'#engineering_message\').val(\'\');'."\n";
	exit();
}

if($_GET['get_log']) {
	if($_GET['get_log']=='sub_step') {
		
		$lq = mysqli_query($link, 'SELECT project_log.*, users.name FROM project_log, users WHERE project_log.sub_step=\''.sf($_GET['log']).'\' AND project_log.user = users.id ORDER BY date DESC');
		
		echo '<table class="table">
		<thead>
			<tr>
				<th width="20%">Date</td>
				<th>Log</th>
				<th width="20%">User</td>
			</tr>
		</thead>
		<tbody>
		';
		
		while($log = mysqli_fetch_assoc($lq)) {
			echo '<tr>
					<td>
						'.$log['date'].'
					</td>
					<td>
						'.$log['log'].'
					</td>
					<td>
						'.$log['name'].'
					</td>
				</tr>';
		}
		
		echo '</tbody></table>';
		
	} else {
	
		$lq = mysqli_query($link, 'SELECT project_log.*, users.name FROM project_log, users WHERE project_log.step=\''.sf($_GET['log']).'\' AND project_log.user = users.id ORDER BY date DESC');
		
		echo '<table class="table">
		<thead>
			<tr>
				<th width="20%">Date</td>
				<th>Log</th>
				<th width="20%">User</td>
			</tr>
		</thead>
		<tbody>
		';
		
		while($log = mysqli_fetch_assoc($lq)) {
			echo '<tr>
					<td>
						'.$log['date'].'
					</td>
					<td>
						'.$log['log'].'
					</td>
					<td>
						'.$log['name'].'
					</td>
				</tr>';
		}
		
		echo '</tbody></table>';
	
	}
	exit();
}

if($_GET['take_picture']) {
	//echo 'alert(\''.$_POST['base64'].'\');';
	exit();
}

if($_GET['get_batch_lot']) {
	$id = sf($_GET['get_batch_lot']);
	
	$ssq = mysqli_query($link, 'SELECT project_sub_steps.*, project_parts.name as part_name, project_parts.batch_lot, project_parts.variables as part_variables FROM project_sub_steps, project_parts WHERE project_sub_steps.id=\''.sf($_GET['get_batch_lot']).'\' AND project_sub_steps.part = project_parts.part AND project_parts.project = \''.sf($project['id']).'\'');
	
	$substep = mysqli_fetch_assoc($ssq);
	
	$part_vars = json_decode($substep['part_variables'], true);
	
	$batch_lot = get_next_batch_lot($part_vars);
	
	//$batch_lot = get_next_batch_lot($vars);
	echo 'alert(\''.$batch_lot.'\');';
	exit();
}
if($_GET['time_event'])
{
    echo'<h1>WORKS</h1>';
    echo '<script>alert("WORKS");</script>';
}

if($_GET['assign_batch_lot'])
{
    //echo'<h1>BATCH LOT</h1>';
            $a = 'SELECT 
                        project_sub_steps.*, 
                        project_parts.id as part_id, 
                        project_parts.name as part_name, 
                        project_parts.batch_lot, 
                        project_parts.variables as part_variables 
                        FROM 
                        project_sub_steps, 
                        project_parts 
                        WHERE 
                        project_sub_steps.part = project_parts.part 
                        AND 
                        project_parts.project = \''.sf($_GET['id']).'\'
                        AND
                        project_sub_steps.step=\''.sf($_GET['get_step']).'\'';
            //echo $a.'<br/>';
            $ssq = mysqli_query($link, $a);
            //$substep = mysqli_fetch_assoc($ssq);
            
            while($substep = mysqli_fetch_assoc($ssq))
            {
                $part_vars = json_decode($substep['part_variables'], true);
                if(is_array($part_vars))
                {
                    foreach($part_vars as $key=>$val) 
                    {
                          if($val['name'] == 'Material'){ $material = $val['value']; }
                          if (strpos($val['name'], 'Color') !== false) { $color = strtoupper($val['value']); }
                          
                            if(!empty($color) && !empty($material)) 
                            {
                                $sql = 'SELECT lot_number FROM batch_lots WHERE archived=\'0\' AND material=\''.sf($material).'\' AND color=UPPER(\''.sf($color).'\') AND lot_number != \'\' LIMIT 1';
                        		$q = mysqli_query($link, $sql);
                        		
                        		if(mysqli_num_rows($q)>0) 
                        		{	
                        			$result = mysqli_fetch_assoc($q);	
                        			$batch_lot = $result['lot_number'];
                        			$q = 'UPDATE project_parts SET project_parts.batch_lot=\''.sf($batch_lot).'\' WHERE project_parts.id=\''.sf($substep['part_id']).'\'';
                        			$upd = mysqli_query($link, $q);
                        			//echo $q.'<br/>';
                        		}
                        	}
                        	
                    }
                }
            }
    //echo    "<script>
    //        window.location.href = '?page=project_step&id=".$_GET['id']."&get_step=".$_GET['get_step']."&mark_as=".$_GET['mark_as']."';
    //     </script>";   
    //echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=project_step&id='.$project_id.'&get_step='.$_GET['get_step'].'&mark_as='.$_GET['mark_as'].'">';
    //echo 'header("Refresh:1");';
    
    echo "window.location.href = '?page=project_step&id=".$_GET['id']."&get_step=".$_GET['get_step']."';";
    exit();
}

if($_GET['time_event']) {
	$ssq = mysqli_query($link, 'SELECT project_sub_steps.* FROM project_sub_steps
	                                WHERE   project_sub_steps.step=\''.sf($_GET['id']).'\' AND 
	                                        project_sub_steps.completed=\'1\' 
	                                        ORDER BY completed_time DESC LIMIT 1');
	
	$sub_step = mysqli_fetch_assoc($ssq);


	if($_GET['time_event']=='start') {
		$time = time_event($step['id'], 'start', $sub_step['id'], $_GET['type']);
	} else {
		$time = time_event($step['id'], 'stop', $sub_step['id'], $_GET['type']);
	}
	
	
	
	exit();
}

?>



<div data-spy="affix" data-offset-top="0" style="">
	<div class="project_step_top">
		
		<div class="project_step_top_title col-md-3 bold"><b><?=$project['name']?></b></div>
		
		<div class="col-md-1 text-danger text-center bold" id="step_status">
			Serial: <?=$project['serial']?>
		</div>

		<div class="col-md-1">
			<div class="progress">
				<div class="progress-bar" style="" id="progress-bar"></div>
			</div>
		</div>
		
		<div class="col-md-1 text-success text-center bold" id="step_status">
			<?=($step['status']=='' ? '' : $step['status'])?>
		</div>
		
		<div class="col-md-6 text-right">
		<button class="btn" onclick="$('#step_message').modal('show');" style="margin-top:-2px;">Send Message</button>
		&nbsp;<a href="<?php echo root('/?page=project&id='.$_GET['id']); ?>"><button class="btn" style="margin-top:-2px;">Back to Project</button></a>
		&nbsp;<button class="btn btn-info pull-right right_btn" onclick="reassign_batch_lot();" id="assign_batch_lot" style="margin-top:-2px;">Reassign Batch Lot</button>
		<? if($step['status']!=='Completed') { ?>
			&nbsp;<button class="btn btn-success pull-right right_btn <?=(($pct_complete==1 || $step['sub_step_count']==0) ? '' : 'none')?>" onclick="complete_step();" id="mark_as_complete" style="margin-top:-2px;">Mark As Complete</button>
		<? } ?>
			
		<button class="btn btn-success pull-right right_btn <?=(($step['status']=='') ? '' : 'none')?>" onclick="document.location='<?=root()?>/?page=project_step&id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&mark_as=started';" id="mark_as_started" style="margin-top:-2px;">Start Now</button>
		
		<? if($step['status'] == 'Completed') { ?>
			&nbsp;<button class="btn btn-success pull-right right_btn" onclick="resend_email();" id="resend_email" style="margin-top:-2px;">Resend Email</button>
		<? } ?>
	
		
		</div>
		
	</div>
</div>


<div class="container-fluid">
<br />
<br />
<legend>Step #<?=($step['order']+1)?> - <?=$step['name']?> </legend>
<div class="row">
  
  <div class="col-sm-4">
		
	<strong>Time</strong>
	
	
	<div class="project_timer">
		<strong>Production Time</strong><br />
		
		<div id="step_timer">00:00:00</div>
		
		<? if($step['status']!=='Completed') { ?>
			
			<button type="button" class="btn btn-success" <?=($step['status']=='' ? 'onclick="document.location=\''.root().'/?page=project_step&id='.$_GET['id'].'&get_step='.$_GET['get_step'].'&mark_as=started\';"' : 'id="timer_start"')?>>Start</button>
		
			<? 
			if($step['status']=='In Progress') { 
			?>
				<button type="button" class="btn btn-warning" id="timer_rework">Rework</button>
			<? 
			}
			
			
		} elseif($step['status']=='Completed' && $step_meta['timing']['rework_time']>0) { 
			
			echo '<div class="text-danger"><strong>Rework Time: </strong>'.floor($step_meta['timing']['rework_time']/60).' Min</div>';
			
		}
		?>
	</div>
	<br />
	<br />
	
	<strong>Step Started</strong> <span id="step_started"><?=$step['started']?></span><br />
	<br />
	
	<strong>Step Completed</strong> <span id="step_started"><?=$step['completed']?></span><br />
	
	<br />
	<br />
	<button type="button" class="btn btn-info " onclick="show_step_log(<?=$step['id']?> ,'step');" id="mark_as_started" style="margin-top:-2px;">View Log</button>
  </div>
 
  
  <div class="col-sm-8">
      <?
      $parts = json_decode($step['parts'], true);
      
    if($parts ==''){ echo '';} else {
    ?>
      
  <strong>Parts</strong><br />
  <div class="row">
  <div class="row parts-table row-eq-height">
	<div class="col-sm-4 col-xs-4 part-heading">Part Name</div>
	<div class="col-sm-8 col-xs-8 part-heading">Part Info</div>
  </div>
	<?
	
	if(is_array($parts))
	{
    	foreach($parts as $key=>$part_id) {
    		
    		$part_q = mysqli_query($link, 'SELECT project_parts.*, product_parts.batch_lot as capture_batch_lot FROM project_parts LEFT JOIN product_parts ON project_parts.part = product_parts.id WHERE `project`=\''.sf($project['id']).'\' AND `part`=\''.sf($part_id).'\'');
    		
    
    		$part = mysqli_fetch_assoc($part_q);
    		
    		$part_vars = json_decode($part['variables'], true);
    		
    		if(!empty($part)){
    		?>
    		
    		
    		<div class="row parts-table-data row-eq-height">
    			<div class="col-md-4 col-xs-4">
    				<?=$part['name']?>
    			</div>
    			
    			<div class="col-md-8 col-xs-8">
    			<? 
    			
    			if(!empty($part['batch_lot']))  echo '<div class="col-xs-4">Lot: '.$part['batch_lot'].'</div>'; 
    			
    			if(is_array($part_vars)){
    			foreach($part_vars as $key=>$var) {
    				if(!empty($var['value'])) echo '<div class="col-xs-4">'.$var['name'].': &nbsp;'.$var['value'].'</div>';
    			}}
    			?>
    			
    			</div>
    		</div>
    		
    		<?		
    		}
    	}
	}
	?>
	</div>
	
	<br />
	<br />
	
	<strong>Documentation</strong><br />
	<div class="row">
  
	<?
	$docq = mysqli_query($link, 'SELECT * FROM project_step_documents WHERE product=\''.sf($project['product']).'\' AND step=\''.sf($step['step']).'\' ORDER BY name ASC');
	
	
	while($doc = mysqli_fetch_assoc($docq)) {
		?>
		
		<div class="row parts-table row-eq-height">
			<div class="col-sm-12 col-xs-12 part-heading">
		
			<a href="/media/documentation/<?=$doc['id']?>.<?=$doc['type']?>" target="_blank"><?=$doc['name']?></a>
			
			</div>
		</div>
		
		<?
	}

	?>
	</div>
	<? } ?>
</div>

</div>

<br />

<br />
<legend>Workflow</legend>




<?
$query = 'SELECT project_sub_steps.*
                                , project_parts.name as part_name
                                , project_parts.batch_lot
                                , project_parts.variables as part_variables
                                , product_parts.batch_lot as capture_batch_lot 
                                FROM project_sub_steps 
                                LEFT JOIN project_parts ON project_sub_steps.part = project_parts.part AND project_parts.project = \''.sf($project['id']).'\' 
                                LEFT JOIN product_parts ON project_sub_steps.part = product_parts.id 
                                WHERE project_sub_steps.step=\''.sf($step['id']).'\' 
                                ORDER BY s_order ASC';
                                //echo $query;
$ssq = mysqli_query($link, $query);

while($sub_step = mysqli_fetch_assoc($ssq)) {

	if($sub_step['type']=='text') {
		$input_checked = '';
		$input_value = $sub_step['value'];
	} else {
		$input_checked = ($sub_step['value']==1 ? 'checked="checked"' : '');
		$input_value = '1';
	}

	$notes_value = $sub_step['notes'];
	
//echo "<pre>";
//print_r($sub_step);
//echo "</pre>";
	?> 
	<div class="project_step_input">
		<form id="step_<?=$sub_step['id']?>">
			<input type="hidden" name="name" value="<?=$sub_step['name']?>">
			<div class="col-md-4 project_sub_step_title sub-step-header"><?=$sub_step['name']?></div>
			
			<div class="col-md-6 col-md-offset-2 text-success bold text-right sub-step-header" id="step_success_<?=$sub_step['id']?>"><?=($sub_step['completed']==1 ? 'Completed on '.$sub_step['completed_time'].' by '.$sub_step['completed_by'] : '')?> [<a href="javascript: ;" onclick="show_step_log(<?=$sub_step['id']?> ,'sub_step');">Log</a>]</div>
			
			<div class="clear"></div>
			<div style="height: 25px;">
				
				<div class="p-3 mb-2 bg-success text-white text-center" style="display: none;" id="save_<?=$sub_step['id']?>">Saved!</div>
				
				<div class="p-3 mb-2 bg-danger text-center" style="display: none;" id="error_<?=$sub_step['id']?>"></div>
				
			</div>
			
			<div class="col-md-2 step-input-container">
				
				<? if($sub_step['type']=='checkbox') { 
				?>
				
					<span class="button-checkbox">
						<button type="button" class="btn btn-lg btn-default" data-color="success">Complete</button>
						<input step_id="<?=$sub_step['id']?>" id="input_<?=$sub_step['id']?>" name="value" placeholder="<?=$sub_step['name']?>" class="step_input none" value="<?=$input_value?>" type="<?=$sub_step['type']?>" <?=$input_checked?>>
					</span>
					
					
					
					
				<? } else { ?>
				
					Enter value:<br>
					<input step_id="<?=$sub_step['id']?>" id="input_<?=$sub_step['id']?>" name="value" placeholder="<?=$sub_step['name']?>" class="step_input input-md" value="<?=$input_value?>" type="<?=$sub_step['type']?>" <?=$input_checked?>>
					
				<? } ?>
				
				
			</div>
			<!--
			<div class="col-md-3 project_step_notes_container">
				<textarea step_id="<?=$sub_step['id']?>" name="notes" class="project_step_notes step_input form-control input-md" id="notes_<?=$sub_step['id']?>" placeholder="Enter notes here"><?=$notes_value?></textarea>
			</div>-->
			<div class="col-md-10">
				
				<?
				$part_vars = json_decode($sub_step['part_variables'], true);
        
                //echo "<pre>";
                //print_r($sub_step);
                //echo "</pre>";
				
				if(!empty($sub_step['part_name'])){
				?>
				<div class="row parts-table parts-table-heading row-eq-height">
					<div class="col-md-2 part-heading">Part Name</div>
					<div class="col-md-2 part-heading">Batch Lot</div>
					<div class="col-md-8 part-heading">Part Info</div>
				</div>
				<div class="row parts-table parts-table-data">
					<div class="col-md-2"><?=$sub_step['part_name']?></div>
					<div class="col-md-2 text-center">
						<?php 
            //if(!empty($sub_step['batch_lot']) || $sub_step['capture_batch_lot']==1)
            //{
            //echo "<pre>";
            //print_r($part_vars);
            //echo "</pre>";
            
            /*
              $q = mysqli_query($link, 'SELECT * FROM project_steps WHERE id=\''.sf($_GET['id']).'\'');
              $r = mysqli_fetch_assoc($q);
              
              if($r['order'] == 1 && $r['completed'] != null){
                  if($r['order'] == 2 && $r['completed'] != null){    
                      if($r['order'] == 3 && $r['completed'] != null){
                          if(empty($step['completed']) || $step['completed'] == null){
                            $step['completed'] = '0000-00-00 00:00:00';
                          }
                                          
                          if(is_array($part_vars))
                          {
                              $batch_lot = $sub_step['batch_lot'];
                              if(empty($batch_lot)) 
                              {
                                  //attempt to look up a valid batch lot #
                                  $batch_lot = get_next_batch_lot($part_vars);
                                  if(!empty($batch_lot)) {
                                      $batch_lot_subtxt = '<div class="text-danger bold">Auto-populated</div>';
                                  } else {
                                      $batch_lot_subtxt = '';
                                  }
                              }
                          
                              if(!empty($batch_lot)){
                                  echo $batch_lot.'<br/>'.$batch_lot_subtxt.'<br/>'.$step['completed'];
                              }else{
                                  echo 'NONE';
                              }
                          }
                      }
                  }
              } else {
                  echo 'Waiting to be completed';
              }
            */
            
            if(is_array($part_vars))
            {
                //echo"<pre>";
                //print_r($sub_step);
                //echo"</pre>";
                
                $batch_lot = $sub_step['batch_lot'];
                echo '<input type=\'hidden\' name=\'lot\' value=\''.$batch_lot.'\'><br/>';
                
                if(empty($batch_lot)) 
                {
                    //attempt to look up a valid batch lot #
                    $batch_lot = get_next_batch_lot($part_vars);
                    if(!empty($batch_lot)) {
                        $batch_lot_subtxt = '<div class="text-danger bold">Auto-populated</div>';
                    } else {
                        $batch_lot_subtxt = '';
                    }
                }
            
                if(!empty($batch_lot) && $sub_step['completed'] == 1)
                {
                    echo $batch_lot.'<br/>'.$batch_lot_subtxt.'<br/>'; //.date("Y-m-d", strtotime($sub_step['completed_time']));
                    echo '<input type=\'hidden\' name=\'batch_lot\' value=\''.$batch_lot.'\'><br/>';
                } 
                elseif(!empty($batch_lot) && $sub_step['completed'] == 0)
                {
                    $batch_lot = get_next_batch_lot($part_vars);
                    echo '<input type=\'hidden\' name=\'batch_lot\' value=\''.$batch_lot.'\'><br/>';
                    echo 'Waiting to be completed';
                }
                else 
                {
                    echo 'NONE';
                }
            }
            //else{
            //    echo 'Like part '.$sub_step['part_name'].' is missing';
            //}
            ?>
					</div>
					<div class="col-md-8 col-xs-12 project_substep_parts">
					<?
					$i = 1;
					
					if(is_array($part_vars))
                    {
						foreach($part_vars as $key=>$var) {
							if(!empty($var['value'])) {
								echo '';
								echo '<div class="col-md-4 col-sm-6 part-vars  part-var-int">'.$var['name'].'&nbsp;</div>';
								echo '<div class="col-md-8 col-sm-6 part-vars">'.$var['value'].'&nbsp;</div>';
								$i++;
							}
						}
                    }
					?>
					
					</div>
				</div>
				<? } ?>
			</div>
			
			<div class="clear"></div>
			<div class="clear"></div>
			
			<div>
			</div>
			
		</form>
	</div>
<?
}
?>


<script type="text/javascript">

function reassign_batch_lot()
{
    $.post('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&mark_as=<?=$_GET['mark_as']?>&assign_batch_lot=yes', null, null, 'script'); 
}

function save_step(id) {
	
	var val = '';
	
	if($('#input_'+id).attr('type')=='checkbox') {
		if($('#input_'+id).prop( "checked" )) {
			val = 1;
		} else {
			val = 0;
		}
	}
	
	if($('#input_'+id).attr('type')=='text') {
		val = $('#input_'+id).val();
	}
	
	
	
	var data = new Array();
	
	
	$.post('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&i='+id+'&save_step=true', $('#step_'+id).serialize(), null, 'script');
	
}

function show_step_log(id, type) {
	$.get( 'exec/project_step/?id=<?=$_GET['id']?>&get_log='+type+'&log='+id+'', function( data ) {
			$('#step_log').modal('show');
			$('#step_log_content').html(data);
	});
}

function set_step_progress(pct) {
	$('#progress-bar').css('width', pct+'%');
	$('#progress-bar').html('Step '+pct+'% Complete');
}

function complete_step() {
	<? if(!empty($step['email_template'])) { ?>
		$('#complete_step').modal('show');
		$('#complete_step').find('.modal-title').text('Mark Step As Complete');
		$('#completed_step_data').load('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&get_completed_message=true');
	<? } else { ?>
		$.post('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&mark_as=Completed', null, null, 'script');
	<? } ?>
}

function send_message_complete() {
	
	$.post('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&mark_as=Completed', $('#message_form').serialize(), null, 'script');
	$("#email-notif").html("Step have been completed and email have been sent to customer");
	
    setTimeout(function(){
        $("#complete_step").modal('hide');
    }, 5000);
    
    location.reload();
}

function resend_email() {
		$('#complete_step').modal('show');
		$('#complete_step').find('.modal-title').text('Resend Email');
		$('#completed_step_data').load('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&get_completed_message=true');
}

function get_auto_batch_lot(id) {
	$.post('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_batch_lot='+id, null, null, 'script');
	
}


var timer;
var timerSeconds = <?=$step_time['my_time']?>;
var timerType = '<?=$_SESSION['timer_type']?>';
var timerRunning = false;

function toggle_timer() {
	
	if(timerRunning==true) {
		stop_timer();
	} else {
		start_timer(timerSeconds);
	}
	
}

function start_timer(seconds, type='') {
	
	if(timerRunning==true) {
		stop_timer();
	}
	
	timerSeconds = seconds;
	
	timerRunning = true;
	
	
	
	timer_interval_up();
	
	if(type=='rework') {
		
		$('#timer_start').removeClass( "btn-info" ).addClass( "btn-success" ).html('Start');
		$('#timer_rework').removeClass( "btn-warning" ).addClass( "btn-success" ).html('Rework Complete');
		$('#step_timer').addClass( "text-danger" )
		$('#timer_start').hide();
	
	} else {
	
		$('#timer_start').removeClass( "btn-success" ).addClass( "btn-info" ).html('Pause');
		$('#timer_rework').removeClass( "btn-success" ).addClass( "btn-warning" ).html('Rework');
		$('#step_timer').removeClass( "text-danger" );
		$('#timer_start').show();
	
	}
	
	timer = setInterval("timer_interval_up()", 1000 );
	
	report_time_event('start',type);
	
	console.log('timer started');
	
}

function stop_timer() {

	//timerSeconds = 0;
	
	timerRunning = false;
	
	clearInterval(timer);
	
	//$('#step_timer').html('00:00:00');
	
	$('#timer_start').removeClass( "btn-info" ).addClass( "btn-success" ).html('Start');
	$('#timer_rework').removeClass( "btn-success" ).addClass( "btn-warning" ).html('Rework');
	$('#step_timer').removeClass( "text-danger" )
	$('#timer_start').show();
	
	report_time_event('stop','');
	
	console.log('timer stopped');
	
}

function timer_interval_up() {
	
	show_timer();
	
	timerSeconds++;
	
}

function show_timer() {
	var seconds = formatTimeInt(Math.floor(timerSeconds % 60));
	var minutes = formatTimeInt(Math.floor((timerSeconds % 3600) / 60));
	var hours = formatTimeInt(Math.floor(timerSeconds / 3600));
	 
	$('#step_timer').html(hours + ':' + minutes + ':' + seconds);
}

function toggle_rework() {
	
	if(timerType!=='rework') {
		
		timerType = 'rework';
		
		start_timer(timerSeconds, 'rework');
		
	} else {
		timerType = '';
		start_timer(timerSeconds, '');
	}
	
}

function formatTimeInt(val) {
	var valString = val + "";
	if(valString.length < 2)
	{
		return "0" + valString;
	}
	else
	{
		return valString;
	}
}

function report_time_event(event,type) {
	$.post('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&time_event='+event+'&type='+type, null, null, 'script');
}

function submit_engineering_message(event,type) {
	$.post('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&engineering_message=true', $('#engineering_message_form').serialize(), null, 'script');
}

function updatecompletedbuttons() {
	$('.button-checkbox').each(function () {
	
		var $widget = $(this),
		$button = $widget.find('button'),
		$checkbox = $widget.find('input:checkbox'),
		color = $button.data('color'),
		settings = {
			on: {
				icon: 'glyphicon glyphicon-check'
			},
			off: {
				icon: 'glyphicon glyphicon-unchecked'
			}
		};

       
		
		var isChecked = $checkbox.is(':checked');

		// Set the button's state
		$button.data('state', (isChecked) ? "on" : "off");

		// Set the button's icon
		$button.find('.state-icon')
			.removeClass()
			.addClass('state-icon ' + settings[$button.data('state')].icon);

		// Update the button's color
		if (isChecked) {
			$button
				.removeClass('btn-default')
				.addClass('btn-' + color + ' active');
		}
		else {
			$button
				.removeClass('btn-' + color + ' active')
				.addClass('btn-default');
		}
	});
}


$(function() {
	pageload();
});

function pageload() {

	var $affixElement = $('div[data-spy="affix"]');
    //$affixElement.width($('.container').width());
    $affixElement.width($('.container-fluid').width());
	
	$('.step_input').change(function() {
		var step_id = $(this).attr('step_id');
		//$('#save_'+step_id).show();
		save_step(step_id);
	});
	
	var pct = <?=($pct_complete*100)?>;
	
	set_step_progress(pct.toFixed(2));
	
	$('#timer_start').click(toggle_timer);
	
	$('#timer_rework').click(toggle_rework);
	
	<? if($_SESSION['timer_running']==$_GET['get_step']) { ?>
		start_timer(timerSeconds, '<?=$_SESSION['timer_type']?>');
	<? } else { ?>
		show_timer();
	<? } ?>


    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
	
}

</script>



<div id="step_log" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Log</h4>
      </div>
      <div class="modal-body" id="step_log_content">
			
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="step_message" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Submit Engineering Message</h4>
      </div>
      <div class="modal-body" id="step_message_content">
			<strong>Please enter message below</strong>
			<br />
			<form id="engineering_message_form" >
			<textarea name="engineering_message" id="engineering_message" style="width: 100%; height: 250px"></textarea>
			</form>
			<br />
			<br />
			<button class="btn btn-info" onclick="submit_engineering_message();">Send</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="complete_step" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" id="completed_step_data">
			
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>


<div id="img_upload" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Image</h4>
      </div>
      <div class="modal-body">
			<form id="uploadimage" action="" method="post" enctype="multipart/form-data">
				
				<label>Select Your Image</label><br/>
				<input type="file" name="file" id="file" required /><br />
				<br />
				
				<input class="btn" type="submit" value="Upload" class="submit" />
				
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="take-picture" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Take Picture</h4>
      </div>
      <div class="modal-body">
			
			<div class="select">
				<select id="videoSource"><option value="">Pick a camera</option></select>
			</div>
			<br />
			<div id="camera_window">
			<video muted autoplay id="camera_video"></video>
			<canvas style="display:none;" id="picture_canvas"></canvas>
			
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
$(function() {
	$("#uploadimage").on('submit',(function(e) {
		e.preventDefault();
		
		$.ajax({
			url: "<?=root()?>exec/upload_image/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>", 
			type: "POST",             
			data: new FormData(this), 
			contentType: false,       
			cache: false,             
			processData:false,        
			success: function(data)   
			{
				$('#img_upload').modal('hide');
				$('#step_images').load('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&refresh_images=true');
			}
		});

	}));
	
	$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});

});

function remove_image(id) {
	$.get('<?=root()?>exec/project_step/?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&delete_image='+id, null, null, 'script');
}

var image_post_url = '<?=root()?>exec/upload_image//?id=<?=$_GET['id']?>&get_step=<?=$_GET['get_step']?>&take_picture=1';
	
</script>

<script src="<?=root()?>js/ekko-lightbox.min.js"></script>
<script src="<?=root()?>js/take_picture.js"></script>
<link rel="stylesheet" href="<?=root()?>js/ekko-lightbox.min.css" type="text/css" media="all" />

<div class="pull-right col-sm-1">
	<button type="button" class="btn right_btn btn-info"  data-toggle="modal" data-target="#img_upload" id="add_img_btn">Add Image</button> 
</div>
<div class="pull-right col-sm-2">
	<button type="button" class="btn right_btn btn-danger take-picture-btn" id="take_img_btn">Take Picture</button>
</div>
<br />
<br />

<legend>PDF</legend>
<div class="row" id="step_pdf">
    <div class="col-md-12">
        <?php 
        //if it is FALKYN
        //if it is FALKYN
        //if it is FALKYN
        if($project['product'] == 96){ 
        ?>
        
        <a target="_blank" href="<?php echo root().'zip/Falkyn_order_'.$_GET['id'].'/Falkyn_order_'.$_GET['id'].'.pdf?time='.time(); ?>">Falkyn_order_<?=$_GET['id'];?>.pdf</a>
        <br>
        <a target="_blank" href="<?php echo root().'zip/Falkyn_order_'.$_GET['id'].'/Final_design_'.$_GET['id'].'.pdf?time='.time(); ?>">Final_design_<?=$_GET['id'];?>.pdf</a>
        <br>
        <a target="_blank" href="<?php echo root().'zip/Falkyn_order_'.$_GET['id'].'/Product_Traveler_'.$_GET['id'].'.pdf?time='.time(); ?>">Product_Traveler_<?=$_GET['id'];?>.pdf</a>
        
        <?php } else { ?>
        
        <a target="_blank" href="<?php echo root().'zip/Glide_order_'.$_GET['id'].'/Glide_order_'.$_GET['id'].'.pdf?time='.time(); ?>">Glide_order_<?=$_GET['id'];?>.pdf</a>
        <br>
        <a target="_blank" href="<?php echo root().'zip/Glide_order_'.$_GET['id'].'/Final_design_'.$_GET['id'].'.pdf?time='.time(); ?>">Final_design_<?=$_GET['id'];?>.pdf</a>
        <br>
        <a target="_blank" href="<?php echo root().'zip/Glide_order_'.$_GET['id'].'/Product_Traveler_'.$_GET['id'].'.pdf?time='.time(); ?>">Product_Traveler_<?=$_GET['id'];?>.pdf</a>
        
        <?php } ?>
    </div>
</div>
<br>
<legend>Excel</legend>
<div class="row" id="step_xls">
    <div class="col-md-12">
        <?php 
        //if it is FALKYN
        //if it is FALKYN
        //if it is FALKYN
        if($project['product'] == 96){ 
        ?>
        
        <a target="_blank" href="<?php echo root().'zip/Falkyn_order_'.$_GET['id'].'/Build_Certificate_'.$_GET['id'].'.xlsx?time='.time(); ?>">Build_Certificate_<?=$_GET['id'];?>.xlsx</a>
        <br>
        
        <?php } else { ?>
        
        <a target="_blank" href="<?php echo root().'zip/Glide_order_'.$_GET['id'].'/Build_Certificate_'.$_GET['id'].'.xlsx?time='.time(); ?>">Build_Certificate_<?=$_GET['id'];?>.xlsx</a>
        <br>
        
        <?php } ?>
    </div>
</div>
<br>
<legend>Images</legend>
<div class="row" id="step_images">
	<? 
		
		echo show_step_images($_GET['id'], $_GET['get_step']);

	?>
	
                           
</div>
<br>
<legend>Embroidery Breakout</legend>
<div class="row" id="step_em">
    <div class="col-md-12">
        <?php echo '<a target="_blank" href="'.root().'?page=em_breakout&id='.$project['id'].'&design_id='.$project['peregrine_id'].'&product='.$project['product'].'&time='.time().'">Embroidery Breakout</a>'; ?>
    </div>
</div>
<br>
<a href="<?php echo root('/?page=project&id='.$_GET['id']); ?>"><button class="btn" style="margin-top:-2px;">Back to Project</button></a>
</div>


