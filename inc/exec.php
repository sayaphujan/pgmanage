<?
require_once('functions.php');
if(empty($_SESSION['uid'])) {

	switch ($_GET['act']) {

		default:
		header('Location: '.root());
		exit();
		break;

        case 'check_color':
            if(!empty(make_safe($_POST["keyword"]))) {
                $query = mysqli_query($link, "SELECT * FROM colors WHERE deleted='0' AND colors_name LIKE '%".make_safe($_POST['keyword'])."%'");
                //$colors = mysqli_fetch_assoc($query);
                echo'<ul id="color-list" style="list-style-type:none">';
                //foreach($colors as $key => $val) {
                while($colors = mysqli_fetch_assoc($query)){
                    echo '<li class="color_list"><a onClick="selectColor(\''.make_safe($_POST['id']).'\', \''.$colors['colors_name'].'\');">'.$colors['colors_name'].'</a></li>';
                    
                }
                echo'</ul>';
                
            }

            break;
            
        case 'check_color_designer':
            if(!empty(make_safe($_POST["keyword"]))) {
                
                    $url = 'https://projects.ndevix.com/pgdesign_dev/do/api_color/';
             
                    $curl = curl_init();
                     
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                     
                    $data = curl_exec($curl);
                    $colors = json_decode($data, true);
                    curl_close($curl);
                    
                echo'<ul id="color-list" style="list-style-type:none">';
                foreach($colors as $colors) {
                    //if (strpos($colors['colors_name'], $_POST['keyword']) !== false ) {
                        echo '<li class="color_list"><a onClick="selectColor(\''.make_safe($_POST['id']).'\', \''.$colors['colors_name'].'\');">'.$colors['colors_name'].'</a></li>';
                    //}
                }
                echo'</ul>';
                
            }

            break;
            
		case 'preview':
		echo 'preview';
		//echo '../zip/'.$_GET['d'].'/'.$_GET['f'];
		break;
		
		case 'pull_order':
		include '../pages/pull_order.php';
		break;
		
		case 'export_referral':
		    $sn = api_referral($_GET['id'], $_GET['ref_id']);
		    
    		if(!empty($sn)){
                $result = array(
                    'status' => true,
                    'message' => "Success",
                    'data' => $sn,
                        );
            }else{
                $result = array(
                    'status' => false,
                    'message' => "Sorry you're not allowed to access this api",
                        );
            }
            
            echo json_encode($result);
		break;
		
		case 'export_referral_parent':
		    $sn = api_referral_parent($_GET['id']);
		    
    		if(!empty($sn)){
                $result = array(
                    'status' => true,
                    'message' => "Success",
                    'data' => $sn,
                        );
            }else{
                $result = array(
                    'status' => false,
                    'message' => "Sorry you're not allowed to access this api",
                        );
            }
            
            echo json_encode($result);
		break;
		
		case 'export_referral_member':
		    $sn = api_referral_member($_GET['id']);
		    
    		if(!empty($sn)){
                $result = array(
                    'status' => true,
                    'message' => "Success",
                    'data' => $sn,
                        );
            }else{
                $result = array(
                    'status' => false,
                    'message' => "Sorry you're not allowed to access this api",
                        );
            }
            
            echo json_encode($result);
		break;
		
		case 'pull_products':
		    $sn = api_product();
		    
    		if(!empty($sn)){
                $result = array(
                    'status' => true,
                    'message' => "Success",
                    'data' => $sn,
                        );
            }else{
                $result = array(
                    'status' => false,
                    'message' => "Sorry you're not allowed to access this api",
                        );
            }
            
            echo json_encode($result);
		break;
		
		case 'check_project':
		    $sn = api_check_project($_GET['design_id']);
		    
            echo json_encode($sn);
		break;
		
		case 'api_serial':
		    $sn = api_serial();
		    
    		if(!empty($sn)){
                $result = array(
                    'status' => true,
                    'message' => "Success",
                    'data' => $sn,
                        );
            }else{
                $result = array(
                    'status' => false,
                    'message' => "Sorry you're not allowed to access this api",
                        );
            }
            
            echo json_encode($result);
		break;
		
		case 'api_serial_active':
		    $sn = api_serial_active($_GET['id']);
		    
    		if(!empty($sn)){
                $result = array(
                    'status' => true,
                    'message' => "Success",
                    'data' => $sn,
                        );
            }else{
                $result = array(
                    'status' => false,
                    'message' => "Sorry you're not allowed to access this api",
                        );
            }
            
            echo json_encode($result);
		break;
		
		case 'api_project':
            echo edit_project($_GET['id']);
		break;
		
		case 'login':
			$q = mysqli_query($link, 'SELECT * FROM users WHERE email=\''.sf($_POST['email']).'\' AND active=\'1\'');
			
			
			if(mysqli_num_rows($q)==1) {
				$u = mysqli_fetch_assoc($q);
				if(password_verify($_POST['password'], $u['password'])) {
					
					$_SESSION['uid'] = $u['id'];
					$_SESSION['type'] = $u['type'];
					$_SESSION['name'] = $u['name'];
					
					$_SESSION['previous_login'] = $u['last_login'];
					
					$check_new_projects = mysqli_query($link, 'SELECT id FROM projects WHERE started >= \''.sf($_SESSION['previous_login']).'\'');
					
					if($_SESSION['type']=='admin') {
					
						$_SESSION['new_projects'] = mysqli_num_rows($check_new_projects);
						
					} else {
						
						$_SESSION['access_production'] = $u['access_production'];
						$_SESSION['access_demos'] = $u['access_demos'];
						
					}
					
					
					
					mysqli_query($link, 'UPDATE users SET last_login=NOW() WHERE id=\''.sf($u['id']).'\'');
					
					header('location: '.root().'?page=main');
					
					exit();
				}
			}else{
			    $q = mysqli_query($link, 'SELECT * FROM inspectors WHERE email=\''.sf($_POST['email']).'\' AND active=\'1\'');
			    $u = mysqli_fetch_assoc($q);
				if(password_verify($_POST['password'], $u['password'])) {
					
					$_SESSION['uid'] = $u['id'];
					$_SESSION['type'] = $u['type'];
					$_SESSION['name'] = $u['name'];
					
					$_SESSION['previous_login'] = $u['last_login'];
					
					$check_new_projects = mysqli_query($link, 'SELECT id FROM projects WHERE started >= \''.sf($_SESSION['previous_login']).'\'');
					$_SESSION['new_projects'] = mysqli_num_rows($check_new_projects);
					
					mysqli_query($link, 'UPDATE inspectors SET last_login=NOW() WHERE id=\''.sf($u['id']).'\'');
					
					header('location: '.root().'?page=main');
					
					exit();
				}
			}
			
			header('location: '.root().'?error=loginfailed');
		break;

		case 'logout':
		session_destroy();
		header('Location: '.root());
		exit();
		break;
	}

}

if($_SESSION['uid']>0) {

	switch ($_GET['act']) {
	    case 'remove_batch_lot':
	        //print_r($_POST);
	        
	        $q = mysqli_query($link, 'SELECT * FROM batch_lots WHERE `id`=\''.sf($_POST['id']).'\'');
        	$lot = mysqli_fetch_assoc($q);
	
	        mysqli_query($link, 'INSERT INTO batch_lot_history (`name`, `inventory_id`, `type`, `material`, `color`, `lot_number`, `added`) VALUES ( \''.sf($lot['name']).'\', \''.sf($lot['inventory_id']).'\', \''.sf($lot['type']).'\', \''.sf($lot['material']).'\', \''.sf($lot['color']).'\', \''.sf($lot['lot_number']).'\', NOW() )');
		
		    mysqli_query($link, 'DELETE FROM batch_lots WHERE `id`=\''.sf($_POST['id']).'\'');    
    		echo json_encode('1');

		    break;
		    
	    case 'save_batch_lot':
	        
		    if(empty(make_safe($_POST['color'])))
		    {
                mysqli_query($link, 'UPDATE batch_lots SET `inventory_id`=\''.make_safe($_POST['inventory_id']).'\', `name`=\''.make_safe($_POST['name']).'\', `type`=\''.make_safe($_POST['type']).'\', `material`=\''.make_safe($_POST['material']).'\', `color`=\''.make_safe($_POST['color']).'\', `lot_number`=\''.make_safe($_POST['lot_number']).'\' WHERE id=\''.make_safe($_POST['id']).'\'');
		    }
		    else
		    {
                    //print_r($_POST);
            	$qc = mysqli_query($link, "SELECT * FROM colors WHERE deleted='0' AND colors_name='".make_safe($_POST['color'])."'");
		        
		        if(mysqli_num_rows($qc) > 0){
            		mysqli_query($link, 'UPDATE batch_lots SET `inventory_id`=\''.make_safe($_POST['inventory_id']).'\', `name`=\''.make_safe($_POST['name']).'\', `type`=\''.make_safe($_POST['type']).'\', `material`=\''.make_safe($_POST['material']).'\', `color`=\''.make_safe($_POST['color']).'\', `lot_number`=\''.make_safe($_POST['lot_number']).'\' WHERE id=\''.make_safe($_POST['id']).'\'');
            		echo json_encode('1');
            	}else{
            	    echo json_encode('0');
            	}
		    }
	    //echo "<script>window.location.href ='?page=settings'</script>";
		    break;
		    
		case 'check_color':
            if(!empty(make_safe($_POST["keyword"]))) {
                $query = mysqli_query($link, "SELECT * FROM colors WHERE deleted='0' AND colors_name LIKE '%".make_safe($_POST['keyword'])."%'");
                //$colors = mysqli_fetch_assoc($query);
                
                if(mysqli_num_rows($query)>0){
                echo'<ul id="color-list" style="list-style-type:none">';
                //foreach($colors as $key => $val) {
                while($colors = mysqli_fetch_assoc($query)){
                    echo '<li class="color_list"><a onClick="selectColor(\''.make_safe($_POST['id']).'\', \''.$colors['colors_name'].'\');">'.$colors['colors_name'].'</a></li>';
                    
                }
                echo'</ul>';
                }else{
                    if(strlen($_POST["keyword"]) > 2){
                        echo '';
                    }
                }
                
            }

            break;
            
        case 'check_color_designer':
            if(!empty(make_safe($_POST["keyword"]))) {
                
                    $url = 'https://projects.ndevix.com/pgdesign_dev/do/api_color/';
             
                    $curl = curl_init();
                     
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                     
                    $data = curl_exec($curl);
                    $colors = json_decode($data, true);
                    curl_close($curl);
                    
                echo'<ul id="color-list" style="list-style-type:none">';
                foreach($colors as $colors) {
                    //if (strpos($colors['colors_name'], $_POST['keyword']) !== false ) {
                        echo '<li class="color_list"><a onClick="selectColor(\''.make_safe($_POST['id']).'\', \''.$colors['colors_name'].'\');">'.$colors['colors_name'].'</a></li>';
                    //}
                }
                echo'</ul>';
                
            }

            break;
            
	    case 'pull_order':
		include '../pages/pull_order.php';
		break;
		
		case 'pull_products':
		    $sn = api_product();
		    
    		if(!empty($sn)){
                $result = array(
                    'status' => true,
                    'message' => "Success",
                    'data' => $sn,
                        );
            }else{
                $result = array(
                    'status' => false,
                    'message' => "Sorry you're not allowed to access this api",
                        );
            }
            
            echo json_encode($result);
		break;
		
		case 'api_serial':
		echo api_serial();
		break;
		
		case 'api_project':
		echo edit_project($_GET['id']);
		break;
		
		case 'product':
		include '../pages/product.php';        
		
		break;
		
		case 'product_options':
		include 'ui/product_options.php';
		break;
		
		case 'add_project':
		include '../pages/add_project.php';
		break;
		
		case 'project':
		include '../pages/project.php';
		break;
		
		case 'project_step':
		include '../pages/project_step.php';
		break;
		
		case 'upload_image':
		include 'upload_image.php';
		break;
		
		case 'add_customer':
		include '../pages/add_customer.php';
		break;
		
		case 'edit_customer':
		include '../pages/edit_customer.php';
		break;
		
		case 'add_color':
		include '../pages/add_color.php';
		break;
		
		case 'edit_color':
		include '../pages/edit_color.php';
		break;

		
		case 'add_inspector':
		include '../pages/add_inspector.php';

		case 'edit_inspector':
		include '../pages/edit_inspector.php';
		break;
		
		case 'del_sn_status':
		    $que = 'UPDATE projects SET 
    	                        `status`=\'deleted\',
    	                        `not_used_sn`=\'0\'
    	                        WHERE id=\''.sf($_GET['id']).'\' AND serial=\''.sf($_POST['serial']).'\'';
    		$update = mysqli_query($link, $que);	
    		//echo $que;
    	                        
        	if($update)
        	{
        	    echo json_encode($que);
        	}

		break;
		
		case 'edit_sn_status':
		    $que = 'UPDATE projects SET 
    	                        `not_used_sn`=\''.sf($_POST['not_used_sn']).'\'
    	                        WHERE id=\''.sf($_GET['id']).'\' AND serial=\''.sf($_POST['serial']).'\'';
    		$update = mysqli_query($link, $que);	
    		//echo $que;
    	                        
        	if($update)
        	{
        	    echo json_encode($que);
        	}

		break;
		
		case 'add_lock_sn':
		     $query = 'INSERT INTO projects (`customer`,`product`,`name`, `location`, `serial`, `status`, `payment`, `notes`, `estimated_completion`, `priority`, `started`, `pod`, `global_vars`, `peregrine_id`, `final_design`, `peregrine_date_modified`, `last_step`, `not_used_sn`) VALUES (0,0, \'\', \'\', \''.sf($_POST['serial']).'\', \'started\', \'\', \'\', \''.sf(date('Y:m:d H:i:s')).'\', \'\', \''.sf(date('Y:m:d H:i:s')).'\', \'\', \'\', 0, \'\', \''.sf(date('Y:m:d H:i:s')).'\', \'ORDER ENTRY\', \''.sf($_POST['not_used_sn']).'\')';
        
            $insert = mysqli_query($link, $query);
    	   //echo json_encode($query);
        	if($insert)
        	{
        	    echo json_encode($query);
        	}

		break;

        case 'referral':
		include '../pages/referral.php';
		break;
		
		case 'edit_referral':
		include '../pages/edit_referral.php';
		break;
		
		case 'edit_referral_user':
		include '../pages/edit_referral_user.php';
		break;
		
		case 'add_user':
		include '../pages/add_user.php';

		case 'edit_user':
		include '../pages/edit_user.php';
		break;
		
		case 'customers':
		include '../pages/customers.php';
		break;
		
		case 'projects':
		include '../pages/projects.php';
		break;
		
		case 'serial_number':
		include '../pages/serial_number.php';
		break;
		
		case 'check_batch_lot':
		include '../pages/check_batch_lot.php';
		break;
		
		case 'products':
		include '../pages/products.php';
		break;
		
		case 'demos':
		include '../pages/demos.php';
		break;
		
		case 'masks':
		include '../pages/masks.php';
		break;
		
		case 'add_demo':
		include '../pages/add_demo.php';
		break;
		
		case 'demo':
		include '../pages/demo.php';
		break;
		
		case 'settings':
		include '../pages/settings.php';
		break;
		
		
		case 'input_batch_lot':
		include '../pages/add_batch_lot.php';
		break;
		
		case 'edit_batch_lot':
		include '../pages/edit_batch_lot.php';
		break;
		
		
		case 'logout':
		session_destroy();
		header('Location: '.root());
		exit();
		break;
		
		case 'customer_list':
        $search = $_POST['search']['value'];
        $limit  = $_POST['length'];
        $start  = $_POST['start'];

        $sql          = mysqli_query($link, "SELECT * FROM `customers`");
        $sql_count    = mysqli_num_rows($sql);
        
        if($search != '')
        {
            $where = " AND (customers.name LIKE '%".$search."%' OR customers.email LIKE '%".$search."%' OR serial LIKE '%".$search."%')";
        }
        else
        {
            $where = '';
        }

        $query = "SELECT customers.*
                      , GROUP_CONCAT(DISTINCT(serial) SEPARATOR '<br>') as serial 
                      FROM customers
                      LEFT JOIN projects ON projects.customer = customers.id
                      WHERE customers.name != '' ".$where." GROUP BY customers.id";
                      
        //echo $query;

        $order_field    = $_POST['order'][0]['column'];
        $order_ascdesc  = $_POST['order'][0]['dir'];
        $order          = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;

        $sql_data = mysqli_query($link, $query.$order." LIMIT ".$limit." OFFSET ".$start);
        $sql_filter = mysqli_query($link, $query);
        $sql_filter_count = mysqli_num_rows($sql_filter);
        
        //echo $query.$order." LIMIT ".$limit." OFFSET ".$start;

        $data = mysqli_fetch_all($sql_data, MYSQLI_ASSOC);
        $callback = array(
            'draw'=>$_POST['draw'],
            'recordsTotal'=>$sql_count,
            'recordsFiltered'=>$sql_filter_count,
            'data'=>$data
        );
        header('Content-Type: application/json');
        echo json_encode($callback);

        break;
        
     case 'update_date_completed':
         $d = substr($_POST['date'],3,2);
         $m = substr($_POST['date'],0,2);
         $y = substr($_POST['date'],-4);
         $date = $y.'-'.$m.'-'.$d;
         
         $date = sf($date);
         $id = $_POST['id'];
         
         $query = "UPDATE projects SET completed='".$date."' WHERE id='".$id."'";
         $sql = mysqli_query($link, $query);
         echo $query;
     break;     
     
     case 'serial_number_list':
       
        $cat = $_POST['cat'];
        $livesearch = $_POST['livesearch'];
        $search = $_POST['search']['value'];
        $limit  = $_POST['length'];
        $start  = $_POST['start'];

        //$sql          = mysqli_query($link, "SELECT * FROM `projects`");
        //$sql_count    = mysqli_num_rows($sql);
        
        $where = '';
        
        if($cat != '')
        {
            if($cat == 'customer_name')
            {
                $cat = "customers.name";
            }
            else
            {
                $cat = "projects.".$cat;
            }
            
            if($livesearch != '')
            {
                $where = "AND (".$cat." LIKE '%".$livesearch."%')";    
            }
            
            if($search != '')
            {
                $where = " AND (projects.serial LIKE '%".$search."%' OR customers.name LIKE '%".$search."%' OR projects.id LIKE '%".$search."%')";
            }
        }
        else
        {
            if($livesearch != '')
            {
                $where = " AND (projects.serial LIKE '%".$search."%' OR customers.name LIKE '%".$search."%' OR projects.id LIKE '%".$search."%')";
            }
            
            if($search != '')
            {
                $where = " AND (projects.serial LIKE '%".$search."%' OR customers.name LIKE '%".$search."%' OR projects.id LIKE '%".$search."%')";
            }
        }


        $sql          = mysqli_query($link, "SELECT COUNT(*) as total FROM `projects`");
        $result       = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        $sql_count    = $result[0]['total'];
        
        $query = "SELECT 
                      projects.id as id
                      , projects.status
                      , projects.serial
                      , projects.global_vars
                      , projects.not_used_sn
                      , customers.name as customer_name
                      , projects.estimated_completion
                      , DATE(projects.completed) as completed
                      , projects.pod
                      , '' as yoke_size
                      , '' as mlw_size
                      , '' as hardware_type
                      , '' as rcs
                      , '' as mcs
                      , CASE 
                        WHEN projects.product = 96 THEN 'Falkyn' 
                        WHEN projects.product = 82 THEN 'Glide' 
                        WHEN projects.product = 0 THEN '' 
                    END AS line
                      FROM projects 
                      LEFT JOIN customers on customers.id = projects.customer
                      WHERE serial != '' AND projects.status != 'deleted' $where 
                      GROUP BY projects.id";

        $order_field    = $_POST['order'][0]['column'];
        $order_ascdesc  = $_POST['order'][0]['dir'];
        $order          = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;
        
        //echo $query.$order." LIMIT ".$limit." OFFSET ".$start;

        $sql_data = mysqli_query($link, $query.$order." LIMIT ".$limit." OFFSET ".$start);
        $sql_filter = mysqli_query($link, $query);
        $sql_filter_count = mysqli_num_rows($sql_filter);
        
        $data = mysqli_fetch_all($sql_data, MYSQLI_ASSOC);
        
        foreach($data as $index => $row)
        {
            $row['global_vars'] = json_decode($row['global_vars']);
            $row['hardware_type'] = "";
            
            if(is_array($row['global_vars'] )){
                foreach($row['global_vars'] as $vars)
                {
                    //echo "<pre>";
                    //print_r($vars);
                    //echo "</pre>";
                    
                    if($vars->name == "Harness/Container")
                    {
                        $data[$index]['hardware_type'] = $vars->vars->{'Hardware Type'};
                        $data[$index]['yoke_size'] = $vars->vars->{'Yoke Size'};
                        $data[$index]['mlw_size'] = $vars->vars->{'MLW Size'};
                        
                        $var = $vars->vars->{'Container Size'};
                        $arr = explode("-", $var, 2);
                        $mcs = $arr[1];
                        $rcs = $arr[0];
                        $data[$index]['rcs'] = $rcs;
                        $data[$index]['mcs'] = $mcs;
                    }
                    else
                    {
                        if(isset($vars->vars->{'Hardware Type'}))
                            $data[$index]['hardware_type'] = $vars->vars->{'Hardware Type'};
                        if(isset($vars->vars->{'Yoke Size'}))
                            $data[$index]['yoke_size'] = $vars->vars->{'Yoke Size'};
                        if(isset($vars->vars->{'MLW Size'}))
                            $data[$index]['mlw_size'] = $vars->vars->{'MLW Size'};
                        if(isset($vars->vars->{'Container Size'}))
                        {
                            $var = $vars->vars->{'Container Size'};
                            $arr = explode("-", $var, 2);
                            $mcs = $arr[1];
                            $rcs = $arr[0];
                            $data[$index]['rcs'] = $rcs;
                            $data[$index]['mcs'] = $mcs;
                        }
                    }
                }
            }
            
            $data[$index]['not_used_sn'] = ($data[$index]['not_used_sn'] == 1) ? 'NOT USED' : 'USED';
        }
        
        //$data['que'] = $query.$order." LIMIT ".$limit." OFFSET ".$start;
        
        $callback = array(
            'draw'=>$_POST['draw'],
            'recordsTotal'=>$sql_count,
            'recordsFiltered'=>$sql_filter_count,
            'data'=>$data
        );
        header('Content-Type: application/json');
        echo json_encode($callback);

        break;

 case 'batch_lot_list':
       
        $livesearch = $_POST['livesearch'];
        $search = $_POST['search']['value'];
        $limit  = $_POST['length'];
        $start  = $_POST['start'];

        $where = '';
            if($livesearch != '')
            {
                $where = " AND (batch_lots.name LIKE '%".$livesearch."%' OR batch_lots.inventory_id LIKE '%".$livesearch."%' OR batch_lots.lot_number LIKE '%".$livesearch."%' OR batch_lots.type LIKE '%".$livesearch."%' OR batch_lots.material LIKE '%".$livesearch."%' OR batch_lots.color LIKE '%".$livesearch."%')";
            }
            
            if($search != '')
            {
                $where = " AND (batch_lots.name LIKE '%".$search."%' OR batch_lots.inventory_id LIKE '%".$search."%' OR batch_lots.lot_number LIKE '%".$search."%' OR batch_lots.type LIKE '%".$search."%' OR batch_lots.material LIKE '%".$search."%' OR batch_lots.color LIKE '%".$search."%')";
            }


        $sql          = mysqli_query($link, "SELECT COUNT(*) as total FROM `batch_lots`");
        $result       = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        $sql_count    = $result[0]['total'];
        
        $query = "SELECT * FROM batch_lots WHERE archived=0 $where ";

        //$order_field    = $_POST['order'][0]['column'];
        $order_field    = 1;
        $order_ascdesc  = $_POST['order'][0]['dir'];
        $order          = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;
        
        //echo  $query.$order." LIMIT ".$limit." OFFSET ".$start;
        
        $sql_data = mysqli_query($link, $query.$order." LIMIT ".$limit." OFFSET ".$start);
        $sql_filter = mysqli_query($link, $query);
        $sql_filter_count = mysqli_num_rows($sql_filter);
        
        $data = mysqli_fetch_all($sql_data, MYSQLI_ASSOC);
        
        $callback = array(
            'draw'=>$_POST['draw'],
            'recordsTotal'=>$sql_count,
            'recordsFiltered'=>$sql_filter_count,
            'data'=>$data
        );
        header('Content-Type: application/json');
        echo json_encode($callback);

        break;

     case 'update_project_ajax':
        
        /*if($_POST['id'])
        {
            $project = $_POST;
            $project_id = sf($_POST['id']);
            unset($project['id']);
            
            $data = array();
            foreach($project as $index => $value)
    	    {
    	       $data[] = "$index = '".sf($value)."'";
    	    }
    	    
    	    $set = implode(', ', $data);
    	    
            $query = "UPDATE projects SET $set WHERE id = ".$project_id;
            $update = mysqli_query($link, $query);
            if($update)
                echo "1";
            else
                echo "0";
                
            exit();
        }
        else
        {
            echo "0";    
        }*/
        
        if($_POST['id'])
        {
            $project = $_POST;
            $project_id = sf($_POST['id']);
            $vars       = sf($_POST['var']);
            $tab        = sf($_POST['tab']);
            unset($project['id']);
            unset($project['var']);
            
            $data = array();
            
            foreach($project as $index => $value)
    	    {
    	       $index = substr($index, 0, strpos($index, "-"));
    	       
    	       if(strpos($index,'cut_ready') !== false || strpos($index,'production_complete') !== false){
    	           $value = (sf($value) == 0) ? '1' : 0;    
    	       }else{
    	            $value = sf($value);
    	       }
    	       
    	    }
    	    
    	    //echo $index.'<br/>';
    	    
            $pq = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($project_id).'\'');
    
            $project = mysqli_fetch_assoc($pq);
            				
            $global_vars = json_decode($project['global_vars'], true);
    	    
    	        if(is_array($global_vars))
    	        {
            		$global_vars[3]['vars'][$index]                = (sf($value) !== null) ? sf($value) : $global_vars[3]['vars'][$index];
            		array_values($global_vars);
                }
    	    
            $upd = "UPDATE projects SET global_vars=\"". sf(json_encode($global_vars))."\" WHERE id=\"".sf($project_id)."\"";
		    $update = mysqli_query($link, $upd);
    		
    		if($update)
                echo "1";
            else
                echo "0";
              
              echo '<br/>'.$upd;
              //echo json_encode($global_vars);
            exit();
        }
        break;
        
        case 'update_referral_ajax':
        
        if($_POST['id'])
        {
            $ref = $_POST;
            $id = sf($_POST['id']);
            unset($ref['id']);
            
            $data = array();
            foreach($ref as $index => $value)
    	    {
    	       $data[] = "$index = '".sf($value)."'";
    	    }
    	    
    	    $set = implode(', ', $data);
    	    
            $query = "UPDATE referrals SET $set WHERE id = ".$id;
            $update = mysqli_query($link, $query);
            if($update)
                echo "1";
            else
                echo "0";
                
            exit();
        }
        else
        {
            echo "0";    
        }
        break;
        
        case 'attach_referral_ajax':

            $check = mysqli_query($link, "SELECT * FROM customers WHERE id='".$_POST['cust_id']."'");
            $res = mysqli_fetch_assoc($check);
            $insert = mysqli_query($link, "INSERT INTO referrals (name, address, email, phone, contact_method, created, contact_window, referrer_id, deleted, project_id)
                                                VALUES ('".$res['name']."', '".$res['address']."', '".$res['email']."', '".$res['phone']."', 'Email', NOW(), NOW(), '".$_POST['referrer_id']."', '', 0)");
            $referral = mysqli_insert_id($link);
            $insert = mysqli_query($link, "INSERT INTO project_referrals (referrer_id, referral_id, customer_id)
                                                VALUES ('".$_POST['referrer_id']."', '".$referral."', '".$_POST['cust_id']."')");
        /*
        echo "INSERT INTO referrals (name, address, email, phone, contact_method, created, contact_window, referrer_id, deleted, project_id)
                                                VALUES ('".$res['name']."', '".$res['address']."', '".$res['email']."', '".$res['phone']."', 'Email', NOW(), NOW(), '".$_GET['id']."', '', 0)";
                                                echo "<br>";
        echo "INSERT INTO project_referrals (referrer_id, referral_id, customer_id)
                                                VALUES ('".$_GET['id']."', '".$referral."', '".$_POST['cust_id']."')";
          */                                      
            if($insert){
                echo $referral;
            }else{
                echo "0";
            }

        break;
        
        case 'detach_referral_ajax':
            
            $que = 'UPDATE referrals SET 
	                        referrer_id = 0
	                        WHERE id=\''.sf($_POST['id']).'\'';
	                        //echo $que;
	        $update = mysqli_query($link, $que);	
	                    
            if($update)
                echo $_POST['id'];
            else
                echo "0";

        break;
        
        case 'check_serial_ajax':

        if($_POST['serial'])
        {
            $que = "SELECT * FROM projects WHERE serial='".sf($_POST['serial'])."' AND id !='".sf($_POST['id'])."' AND status !='deleted'";
            //echo $que;
            $pq = mysqli_query($link,$que);
    
            $check = mysqli_num_rows($pq);
    		if($check>0)
                echo "1";
            else
                echo sf($_POST['serial']);
              
            exit();
        }
        break;
        
        case 'reassign_batch_lot':
	        /*$ssq = mysqli_query($link, 'SELECT project_sub_steps.*, project_parts.name as part_name, project_parts.batch_lot, project_parts.variables as part_variables FROM project_sub_steps, project_parts WHERE project_sub_steps.part = project_parts.part AND project_parts.project = \''.sf($_POSTproject['id']).'\'');
            $substep = mysqli_fetch_assoc($ssq);
        	
        	$part_vars = json_decode($substep['part_variables'], true);
        	print_r($part_vars);
        	foreach($part_vars as $key=>$val) 
            {
                  if($val['name'] == 'Material'){ $material = $val['value']; }
                  if (strpos($val['name'], 'Color') !== false) { $color = strtoupper($val['value']); }
                  
                    if(!empty($color) && !empty($material)) 
                    {
                        $sql = 'SELECT lot_number FROM batch_lots WHERE archived=\'0\' AND material=\''.sf($material).'\' AND color=UPPER(\''.sf($color).'\') AND lot_number != \'\'';
                		$q = mysqli_query($link, $sql);
                		
                		if(mysqli_num_rows($q)>0) 
                		{	
                			$result = mysqli_fetch_assoc($q);	
                			$batch_lot = $result['lot_number'];
                			$q = 'UPDATE project_parts SET project_parts.batch_lot=\''.sf($batch_lot).'\' WHERE project_parts.id=\''.sf($sub_step['part_id']).'\'';
                			echo $q.'<br>';
                			$upd = mysqli_query($link, $q);
                		}
                	}
                	
            }*/
            print_r($_POST);

	    break;
        
	}
}

switch ($_GET['act'])
{
    case 'update_risers':
            $id = $_GET['id'];
            $des_id = $_GET['des'];
            
            $s  = sf(md5($des_id.'peregrin3!'));
            //$request = "https://projects.ndevix.com/pgdesign_dev/do/api_pull_order/?id=$id&s=$s";    
            //$request = "https://".$des."/do/api_pull_order/?id=$des_id&s=$s";
            $request = "https://design.peregrinemfginc.com/do/api_pull_order/?id=$des_id&s=$s";    
            
            echo $request.'<br/><br/>';
            
            // Generate curl request
            $session = curl_init($request);
            curl_setopt ($session, CURLOPT_POST, true);
            curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
            curl_setopt($session, CURLOPT_HEADER, 0);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            
            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            
            $data = json_decode($response, true);
            
            //echo "<pre>";
            //print_r($data);
            //echo "</pre>";
            
            if($data['data']['product_id'] == '96')  
            {
                echo 'Riser_Covers_Left_Outside_Color => '.$data['data']['Riser_Covers_Left_Outside_Color'].'<br/>';
                echo 'Riser_Covers_Right_Outside_Color => '.$data['data']['Riser_Covers_Right_Outside_Color'].'<br/>';
                echo 'Riser_Covers_Left_Inside_Color => '.$data['data']['Riser_Covers_Left_Inside_Color'].'<br/>';
                echo 'Riser_Covers_Right_Inside_Color => '.$data['data']['Riser_Covers_Right_Inside_Color'].'<br/>';
                echo 'Riser_Covers_Left_Color => '.$data['data']['Riser_Covers_Left_Color'].'<br/>';
                echo 'Riser_Covers_Right_Color => '.$data['data']['Riser_Covers_Right_Color'].'<br/><br/>';
                
                $l2 = $data['data']['Riser_Covers_Left_Outside_Color'];
                $r2 = $data['data']['Riser_Covers_Right_Outside_Color'];
                //$l1[Riser_Covers_Left_Color] => Raid
                //$r1[Riser_Covers_Right_Color] => Raid
                $l1 = (isset($data['data']['Riser_Covers_Left_Inside_Color']) && !empty($data['data']['Riser_Covers_Left_Inside_Color'])) ? $data['data']['Riser_Covers_Left_Inside_Color'] : $data['data']['Riser_Covers_Left_Color'];
                $r1 = (isset($data['data']['Riser_Covers_Right_Inside_Color']) && !empty($data['data']['Riser_Covers_Right_Inside_Color'])) ? $data['data']['Riser_Covers_Right_Inside_Color'] : $data['data']['Riser_Covers_Right_Color'];
                
                echo " L1 : ".$l1."<br>";
                echo " R1 : ".$r1."<br>";
                echo " L2 : ".$l2."<br>";
                echo " R2 : ".$r2."<br><br/>";
                
                $sql_l1 = 'UPDATE `project_parts` SET `name` = \'4L RISER COVER L1 (EM7)\' WHERE `project` = '.sf($id).' AND `name` = \'4L RISER COVER L (EM7)\'';
                $sql_r1 = 'UPDATE `project_parts` SET `name` = \'4R RISER COVER R1 (EM8)\' WHERE `project` = '.sf($id).' AND `name` = \'4R RISER COVER R (EM8)\'';
                mysqli_query($link, $sql_l1);
                mysqli_query($link, $sql_r1);
                
                echo $sql_l1.'<br>'.$sql_r1.'<br><br>';
                
                $sql_l2 = 'INSERT INTO `project_parts` (
                                                        `id`
                                                        , `project`
                                                        , `part`
                                                        , `category_id`
                                                        , `category`
                                                        , `name`
                                                        , `batch_lot`
                                                        , `variables`
                                                        ) 
                                                        VALUES
                                                        (NULL
                                                        , '.sf($id).'
                                                        , 1109
                                                        , 110
                                                        , NULL
                                                        , \'4L RISER COVER L2\'
                                                        , NULL
                                                        , \'[{\"name\":\"Material\",\"api_name\":\"Fabric_Type_CB\",\"value\":\"'.$data['data']['Fabric_Type_CB'].'\"},{\"name\":\"Color\",\"api_name\":\"Riser_Covers_Left_Inside_Color\",\"value\":\"'.$l2.'\"}]\'
                                                        );
                                                        ';
                $sql_r2 = 'INSERT INTO `project_parts` (
                                                        `id`
                                                        , `project`
                                                        , `part`
                                                        , `category_id`
                                                        , `category`
                                                        , `name`
                                                        , `batch_lot`
                                                        , `variables`
                                                        ) 
                                                        VALUES
                                                        (NULL
                                                        , '.sf($id).'
                                                        , 1110
                                                        , 110
                                                        , NULL
                                                        , \'4R RISER COVER R2\'
                                                        , NULL
                                                        , \'[{\"name\":\"Material\",\"api_name\":\"Fabric_Type_CB\",\"value\":\"'.$data['data']['Fabric_Type_CB'].'\"},{\"name\":\"Color\",\"api_name\":\"Riser_Covers_Right_Inside_Color\",\"value\":\"'.$r2.'\"}]\'
                                                        );
                                                        ';
                
                $check_l2 = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($id).'\' AND part=1109');
                if(mysqli_num_rows($check_l2) == 0){ mysqli_query($link,$sql_l2); echo $sql_l2.'<br/><br/>';}
                
                $check_r2 = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($id).'\' AND part=1110');
                if(mysqli_num_rows($check_r2) == 0){ mysqli_query($link,$sql_r2); echo $sql_r2.'<br/>';}
                
                //########################### RISE COVERS RIGHT 1
                $parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($id).'\' AND part=1047');
                while($part = mysqli_fetch_assoc($parts)) 
                {
                    $update = false;
                    $vars = json_decode($part['variables'], true);
                 
                    if (is_array($vars) || is_object($vars))
                    {
                        foreach($vars as $key=>$var) 
                        {
                            if($var['name'] == 'Color' && $var['api_name'] == 'Riser_Covers_Right_Color')
                            {
                                //echo"<pre>";
                                //print_r($vars);
                                //echo"</pre>";
                                
                                $vars[1]['value'] = $r1;
                                $update = true;
                            }
                        }
                    }
                  
                    echo '<br/>UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'<br/><br/>';
                    if($update==true) {
                        mysqli_query($link, 'UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'');
                    }
                }
                
                //########################### RISE COVERS LEFT 1
                $parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($id).'\' AND part=1046');
                while($part = mysqli_fetch_assoc($parts)) 
                {
                    $update = false;
                    $vars = json_decode($part['variables'], true);
                 
                    if (is_array($vars) || is_object($vars))
                    {
                        foreach($vars as $key=>$var) 
                        {
                            if($var['name'] == 'Color' && $var['api_name'] == 'Riser_Covers_Right_Color')
                            {
                                $vars[1]['api_name'] = 'Riser_Covers_Left_Color';
                                $vars[1]['value'] = $l1;
                                $update = true;
                            }
                            
                            if($var['name'] == 'Color' && $var['api_name'] == 'Riser_Covers_Left_Color')
                            {
                                //echo"<pre>";
                                //print_r($vars);
                                //echo"</pre>";
                                
                                $vars[1]['value'] = $l1;
                                $update = true;
                            }
                        }
                    }
                  
                    echo 'UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'<br/>';
                    if($update==true) {
                        mysqli_query($link, 'UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'');
                    }
                }
            }
            else if($data['data']['product_id'] == '89')
            {
                echo "Glide Product<br/><br/>";
                echo 'Riser_Covers_Left_Color => '.$data['data']['Riser_Covers_Left_Color'].'<br/>';
                echo 'Riser_Covers_Right_Color => '.$data['data']['Riser_Covers_Right_Color'].'<br/><br/>';
                
                $l1 = $data['data']['Riser_Covers_Left_Color'];
                $r1 = $data['data']['Riser_Covers_Right_Color'];
                
                
                //########################### RISE COVERS RIGHT
                $parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($id).'\' AND part=892');
                while($part = mysqli_fetch_assoc($parts)) 
                {
                    $update = false;
                    $vars = json_decode($part['variables'], true);
                 
                    if (is_array($vars) || is_object($vars))
                    {
                        foreach($vars as $key=>$var) 
                        {
                            if($var['name'] == 'Color' && $var['api_name'] == 'Riser_Covers_Right_Color')
                            {
                                //echo"<pre>";
                                //print_r($vars);
                                //echo"</pre>";
                                
                                $vars[1]['value'] = $r1;
                                $update = true;
                            }
                        }
                    }
                  
                    echo '<br/>UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'<br/><br/>';
                    if($update==true) {
                        mysqli_query($link, 'UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'');
                    }
                }
                
                //########################### RISE COVERS LEFT
                $parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($id).'\' AND part=891');
                while($part = mysqli_fetch_assoc($parts)) 
                {
                    $update = false;
                    $vars = json_decode($part['variables'], true);
                 
                    if (is_array($vars) || is_object($vars))
                    {
                        foreach($vars as $key=>$var) 
                        {
                            if($var['name'] == 'Color' && $var['api_name'] == 'Riser_Covers_Right_Color')
                            {
                                $vars[1]['api_name'] = 'Riser_Covers_Left_Color';
                                $vars[1]['value'] = $l1;
                                $update = true;
                            }
                            
                            if($var['name'] == 'Color' && $var['api_name'] == 'Riser_Covers_Left_Color')
                            {
                                //echo"<pre>";
                                //print_r($vars);
                                //echo"</pre>";
                                
                                $vars[1]['value'] = $l1;
                                $update = true;
                            }
                        }
                    }
                  
                    echo 'UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'<br/>';
                    if($update==true) {
                        mysqli_query($link, 'UPDATE project_parts SET variables=\''.json_encode($vars).'\' WHERE id=\''.sf($part['id']).'\'');
                    }
                }
                
            }
            break;
            
}
?>