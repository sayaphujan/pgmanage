<?php
ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$_SESSION['generate'] = (isset($_GET["generate"])) ? $_GET["generate"] : '';

$id = sf($_GET['id']);
$s  = sf(md5($id.'peregrin3!'));
//$request = "https://projects.ndevix.com/pgdesign_dev/do/api_pull_order/?id=$id&s=$s";    
$request = "https://design.peregrinemfginc.com/do/api_pull_order/?id=$id&s=$s";    


// Generate curl request
$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
curl_setopt($session, CURLOPT_HEADER, 0);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
curl_close($session);

//echo "<pre>";
//print_r($response);
//echo "</pre>";
//Create CSV file
$decoded_file = json_decode($response, true);

$results = array();
$results['options']     = $decoded_file['options'];
$results['colors']      = $decoded_file['colors'];
$results['measurement'] = $decoded_file['measurement'];

//debugging
//debugging
//debugging
//$decoded_file['data']['Final_Design'] = '';
//echo "<pre>";
//print_r($decoded_file['data']);
//echo "</pre>";
//die();

unset($decoded_file['options']);
unset($decoded_file['colors']);
unset($decoded_file['measurement']);

$decoded_file['data']['Customer_Notes'] = preg_replace( "/\r|\n/", " ", $decoded_file['data']['Customer_Notes']);

$fp = fopen('file.csv', 'w');

$header = false;
foreach ($decoded_file as $row)
{
    if(is_array($row))
    {
        if (empty($header))
        {
            $header = array_keys($row);
            fputcsv($fp, $header);
            $header = array_flip($header);
        }
        fputcsv($fp, array_merge($header, $row));   
    }
}
fclose($fp);

if (($fp = fopen('file.csv', 'r')) !== FALSE)
{
    $csv = array_map('str_getcsv', file('file.csv'));
    
    array_walk($csv, function(&$a) use ($csv) 
    {
        $a = array_combine($csv[0], $a);
    });
  
    array_shift($csv);  
    $order_data = $csv[0];

    if(@$order_data['credit'] == 1)
    {
        $order_data['credit'] = 100;
    }
    else if(@$order_data['credit'] == 2)
    {
        $order_data['credit'] = 120;
    }
    else
    {
        $order_data['credit'] = 0;
    }
    
    //echo "<pre>";
    //print_r($order_data);
    //echo "</pre>";
    //die();

    // CHECK CUSTOMER EXIST
    
    $name = strtoupper($order_data['Dealer_Name']);
    
    //echo 'SELECT * FROM customers WHERE name=\''.sf($order_data['Dealer_Name']).'\'';
    
    $cust = mysqli_query($link, 'SELECT * FROM customers WHERE name=\''.sf($order_data['Dealer_Name']).'\'');
    
    if(mysqli_num_rows($cust) >0)
    {
        $data_cust = mysqli_fetch_assoc($cust);
        $cust_id = $data_cust['id'];
        
    } 
    else
    {
        $query = 'INSERT INTO customers (`name`, `address`, `city`, `state`, `zip`, `country`, `email`, `phone`, `notes`) VALUES (\''.sf(strtoupper($order_data['Dealer_Name'])).'\',\''.sf($order_data['Address']).'\',\''.sf($order_data['City']).'\', \''.sf($order_data['State']).'\', \''.sf($order_data['Zipcode']).'\', \''.sf($order_data['Country']).'\', \''.sf($order_data['Email']).'\', \''.sf($order_data['Tel_Number']).'\', \''.sf($order_data['Customer_Notes']).'\')';
     //echo $query;
        mysqli_query($link, $query);
        $cust_id = mysqli_insert_id($link);
    }
    
    //echo $cust_id;
      
    // CHECK PRODUCT
    //change to attach into IA-AC100-2, GLIDE GT H/C ASSEMBLY COMPLETE ID 89
    //$_POST['product'] = '70';
    /*if($order_data['default_product'] != '')
        $_POST['product'] = $order_data['default_product'];
    else
        $_POST['product'] = '89';*/
    if($order_data['product_id'] > 0 )
        $_POST['product'] = $order_data['product_id'];
    else
        $_POST['product'] = $order_data['default_product'];
    
    $pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($_POST['product']).'\'');
    $product = mysqli_fetch_assoc($pq);
    
    //echo 'SELECT * FROM products WHERE id=\''.sf($_POST['product']).'\'';
    
    //prep global variables
    $g_vars = array();
    $p_g_vars = json_decode($product['global_vars'], true);
    
    //echo"<pre>";
    //print_r($product);
    //print_r($p_g_vars);
    //echo"</pre>";
    
          
    foreach($p_g_vars as $key=>$group) 
    {  
        $v_vars = array();
        
        foreach($group['vars'] as $v_key=>$var) {
          $v_vars[$var] = null;
        }
        
        $g_vars[] = array('name'=>$group['name'], 'vars'=>$v_vars);   
        $v_vars = null;
    }
          
    // CHECK PROJECTS
    $projects = mysqli_query($link, 'SELECT * FROM projects 
                                          WHERE peregrine_id=\''.sf($id).'\' AND status!=\'deleted\'');
      
    if(mysqli_num_rows($projects) < 1 )
    {    
        $check_sn = mysqli_query($link, 'SELECT * FROM projects WHERE serial=2794');
        
        if($order_data['Active'] == 1)
        {
            if(mysqli_num_rows($check_sn) < 1)
            {
                //if(empty($order_data['Serial_Number'])){
                    $order_data['Serial_Number'] = '2794';
                //}
            }
            else
            {
                //if(empty($order_data['Serial_Number'])){
                    //$data = mysqli_fetch_assoc(mysqli_query($link, 'SELECT * FROM projects 
                    //                                                    WHERE product=89 AND serial >= 2794 
                    //                                                    ORDER BY serial DESC LIMIT 1'));
                //}
                $get_sn=array();
                $check_sn = mysqli_query($link, 'SELECT * FROM projects WHERE serial=2799');
                        if(mysqli_num_rows($check_sn) < 1)
                        {
                                $sn = '2799';
                        }
                        else
                        {
                            //$data = mysqli_fetch_assoc(mysqli_query($link,"SELECT projects.*, (SELECT product_steps.name FROM project_steps, product_steps WHERE project_steps.project=projects.id AND project_steps.status='Completed' AND project_steps.step = product_steps.id ORDER BY product_steps.order DESC LIMIT 1) as last_status, customers.name as customer_name, products.name as product_name FROM projects LEFT JOIN customers ON projects.customer = customers.id, products WHERE projects.product = products.id AND projects.status='started' ORDER BY serial DESC LIMIT 1"));
                            //$last = $data['serial'];
                            //for($i=2799;$i<=$last;$i++){
                            //    $query = 'SELECT * FROM projects WHERE serial='.$i.' AND status ="deleted"';
                            //    $new_sn = mysqli_query($link,$query);
                            //    if(mysqli_num_rows($new_sn) >0) //if exist
                            //    {
                            //        $sn = $i;
                            //    }else{
                            //        $sn = $last+1;        
                            //    }
                            //    $get_sn[]=$sn;
                            //}
                            
                            /*$query = "SELECT projects.*
                                        	, (SELECT product_steps.name
                                             	FROM project_steps, product_steps 
                                             	WHERE 
                                             		project_steps.project=projects.id AND 
                                             		project_steps.status='Completed' AND 
                                             		project_steps.step = product_steps.id 
                                             		ORDER BY product_steps.order DESC LIMIT 1) as last_status
                                            , customers.name as customer_name
                                            , products.name as product_name 
                                            FROM projects 
                                            LEFT JOIN customers ON projects.customer = customers.id, products 
                                            WHERE 
                                            	projects.product = products.id AND 
                                                products.name LIKE 'IA-AC100%' AND 
                                                char_length(serial) = 4 AND 
                                                serial >= 2799 AND 
                                                status != 'deleted'
                                            ORDER BY serial DESC LIMIT 10";*/
                            $query = "SELECT projects.*
                                        	, (SELECT product_steps.name
                                             	FROM project_steps, product_steps 
                                             	WHERE 
                                             		project_steps.project=projects.id AND 
                                             		project_steps.status='Completed' AND 
                                             		project_steps.step = product_steps.id 
                                             		ORDER BY product_steps.order DESC LIMIT 1) as last_status
                                            , customers.name as customer_name
                                            , products.name as product_name 
                                            FROM projects 
                                            LEFT JOIN customers ON projects.customer = customers.id, products 
                                            WHERE 
                                            	projects.product = products.id AND 
                                                char_length(serial) = 4 AND 
                                                serial >= 2799 AND 
                                                status != 'deleted' AND
                                                not_used_sn !='1'
                                            ORDER BY serial DESC LIMIT 10";
                            $result = mysqli_query($link, $query);
                            
                            $get_sn = array();
                            
                            while($output = mysqli_fetch_assoc($result))
                            {
                                $get_sn[] = $output['serial'];
                            }
                        }
                        
                        if(!empty($get_sn))
                        {
                            //$order_data['Serial_Number'] = min($get_sn);
                            $order_data['Serial_Number'] = chrono_trigger($get_sn);
                        } 
                        else 
                        {
                            $order_data['Serial_Number'] = $sn;
                        }
            }
        }
        else
        {
            $order_data['Serial_Number'] = '';
        }
        
        $check_sn_again = 'SELECT * FROM projects WHERE serial=\''.$order_data['Serial_Number'].'\' AND status!=\'deleted\'';
        //echo $check_sn_again;
        
        $que = mysqli_query($link, $check_sn_again);
        if(mysqli_num_rows($que) > 0){
            $order_data['Serial_Number'] = ($order_data['Serial_Number'] + 1);
        }else{
            $order_data['Serial_Number'] = $order_data['Serial_Number'];
        }
        //echo $order_data['Serial_Number'];
        
        if(empty($_POST['payment'])){ $_POST['payment'] = ''; }

        $query = 'INSERT INTO projects (`customer`
                                            ,`product`
                                            ,`name`
                                            , `location`
                                            , `serial`
                                            , `status`
                                            , `payment`
                                            , `notes`
                                            , `estimated_completion`
                                            , `priority`
                                            , `started`
                                            , `pod`
                                            , `global_vars`
                                            , `peregrine_id`
                                            , `final_design`
                                            , `peregrine_date_modified`
                                            , `last_step`) 
                                            VALUES (
                                            \''.sf($cust_id).'\'
                                            , \''.sf($_POST['product']).'\'
                                            , \''.sf($product['name']).'\'
                                            , \''.sf('Peregrine').'\'
                                            , \''.sf($order_data['Serial_Number']).'\'
                                            , \'started\'
                                            , \''.sf($_POST['payment']).'\'
                                            , \''.sf($order_data['Customer_Notes']).'\'
                                            , \''.sf(date("Y-m-d", strtotime($order_data['Date']))).'\'
                                            , \''.sf('Standard').'\'
                                            , \''.sf($order_data['Received_Date']).'\'
                                            , \''.sf($order_data['Production_Cycle']).'\'
                                            , \''.sf(json_encode($g_vars)).'\'
                                            , \''.sf($id).'\'
                                            , \''.sf($order_data['Final_Design']).'\'
                                            , \''.sf($order_data['Last_Update_Data']).'\'
                                            , \'ORDER ENTRY\')';
        
        mysqli_query($link, $query);
        $project_id = mysqli_insert_id($link);
        $project = mysqli_fetch_assoc(mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($project_id).'\' AND status!=\'deleted\''));
        $steps = mysqli_query($link, 'SELECT * FROM product_steps WHERE product=\''.sf($_POST['product']).'\' ORDER BY `order` ASC');
    
        while($step = mysqli_fetch_assoc($steps)) 
        { 
            $sql = 'INSERT INTO project_steps (`project`, `step`, `order`, `name`, `metadata`, `parts`) 
                        VALUES (\''.sf($project_id).'\'
                                , \''.sf($step['id']).'\'
                                , \''.sf($step['order']).'\'
                                , \''.sf($step['name']).'\'
                                , \''.sf($step['metadata']).'\'
                                , \''.sf($step['parts']).'\')';
            mysqli_query($link, $sql);
            $sid = mysqli_insert_id($link);
            
            $sql = 'SELECT * FROM product_sub_steps WHERE step=\''.sf($step['id']).'\' ORDER BY `s_order` ASC';
            $s_steps = mysqli_query($link, $sql);
              
            while($sub_step = mysqli_fetch_assoc($s_steps)) 
            {
              //$sql = 'INSERT INTO project_sub_steps (`project`, `s_order`, `step`, `sub_step`, `type`, `name`, `part`, `variables`, `value`)
              //          VALUES (\''.$project_id.'\'
              //                  , \''.sf($sub_step['s_order']).'\'
              //                  , \''.sf($sid).'\'
              //                  , \''.sf($sub_step['id']).'\'
              //                  , \''.sf($sub_step['type']).'\'
              //                  , \''.sf($sub_step['name']).'\'
              //                  , \''.sf($sub_step['part']).'\'
              //                  , \''.sf($sub_step['variables']).'\'
              //                  , \'\')';
              //mysqli_query($link, $sql);
              //echo $sql;
              
              $sql = "INSERT INTO `project_sub_steps` 
                        (`id`, `project`, `s_order`, `step`, `sub_step`, `type`, `name`, `value`, `notes`, `part`, `variables`, `completed`, `completed_time`, `completed_by`) 
                        VALUES (NULL
                            , '".sf($project_id)."'
                            , '".sf($sub_step['s_order'])."'
                            , '".sf($sid)."'
                            , '".sf($sub_step['id'])."'
                            , '".sf($sub_step['type'])."'
                            , '".sf($sub_step['name'])."'
                            , ''
                            , ''
                            , '".sf($sub_step['part'])."'
                            , '".sf($sub_step['variables'])."'
                            , '0'
                            , '1970-01-01 00:00:00'
                            , '');";
                            
                mysqli_query($link, $sql);
            }
        }
        
        // add all parts
        $sql = 'SELECT product_parts.* FROM product_parts WHERE product=\''.sf($_POST['product']).'\' ORDER BY `p_order` ASC';
        $parts = mysqli_query($link, $sql);
        
        while($part = mysqli_fetch_assoc($parts)) 
        {
            //echo 'INSERT INTO project_parts (`project`, `part`, `category_id`, `name`, `variables`) VALUES (\''.$project_id.'\', \''.sf($part['id']).'\', \''.sf($part['category']).'\', \''.sf($part['name']).'\' , \''.sf($part['variables']).'\')';
            $sql = 'INSERT INTO project_parts (`project`, `part`, `category_id`, `name`, `variables`) VALUES (\''.$project_id.'\', \''.sf($part['id']).'\', \''.sf($part['category']).'\', \''.sf($part['name']).'\' , \''.sf($part['variables']).'\')';
          mysqli_query($link, $sql);
        }
    } 
    else
    {
        $project = mysqli_fetch_assoc($projects);    
        $project_id = $project['id'];
        
        $query = 'UPDATE projects SET 
                      `product` = \''.sf($_POST['product']).'\', 
                      `notes`   = \''.sf($order_data['Customer_Notes']).'\',
                      `pod`     = \''.sf($order_data['Production_Cycle']).'\', 
                      `estimated_completion` = \''.sf((date("Y-m-d", strtotime($order_data['Date'])))).'\', 
                      `global_vars` = \''.sf(json_encode($g_vars)).'\', 
                      `peregrine_date_modified` = \''.sf($order_data['Last_Update_Data']).'\', 
                      `final_design`=\''.sf($order_data['Final_Design']).'\'  
                    WHERE id=\''.sf($project_id).'\'';
         
         //echo $query;
         
         mysqli_query($link, $query);
    }
    
    if(!empty($order_data['member_id']) && $order_data['member_id'] > 0){
        $ref_user = mysqli_query($link, 'SELECT * FROM referral_users WHERE name=\''.sf($order_data['Dealer_Referred_By']).'\' AND email=\''.sf($order_data['Email']).'\'');
        if(mysqli_num_rows($ref_user) == 0){
            $query = 'INSERT INTO referral_users (`created`, `email`, `name`, `address`,`phone`, `dz`,`payment`,`credits`,`referal_code`,`deleted`) VALUES (NOW(), \''.sf($order_data['Email']).'\', \''.sf(strtoupper($order_data['Dealer_Referred_By'])).'\',\''.sf($order_data['Address']).'\',\''.sf($order_data['Tel_Number']).'\',\''.sf($order_data['dz']).'\',\''.sf($order_data['payment']).'\',\''.sf($order_data['credit']).'\', \''.sf($order_data['referal_code']).'\', \'\')';
            //echo $query;
            mysqli_query($link, $query);
            $ref_by = mysqli_insert_id($link);
        }else{
            //echo 'update ref user';
            $data = mysqli_fetch_assoc($ref_user);
            $ref_by = $data['id'];
            //echo 'UPDATE referral_users SET `address`=\''.sf($order_data['Address']).'\', `dz`=\''.sf($order_data['dz']).'\',`payment`=\''.sf($order_data['payment']).'\',`credits`=\''.sf($order_data['credit']).'\',`referal_code`=\''.sf($order_data['referal_code']).'\', `phone`=\''.sf($order_data['Tel_Number']).'\',`project_id`=\''.sf($project_id).'\'  WHERE id=\''.sf($ref_by).'\'';
            mysqli_query($link,'UPDATE referral_users SET `address`=\''.sf($order_data['Address']).'\', `dz`=\''.sf($order_data['dz']).'\',`payment`=\''.sf($order_data['payment']).'\',`credits`=\''.sf($order_data['credit']).'\',`referal_code`=\''.sf($order_data['referal_code']).'\', `phone`=\''.sf($order_data['Tel_Number']).'\',`project_id`=\''.sf($project_id).'\'  WHERE id=\''.sf($ref_by).'\'');
           
        }
        
        //echo 'ref_by '.$ref_by;
        
        $ref = mysqli_query($link, 'SELECT * FROM referrals WHERE name=\''.sf($order_data['member_name']).'\' AND email=\''.sf($order_data['member_email']).'\' AND referrer_id=\''.sf($ref_by).'\'');
        if(mysqli_num_rows($ref) > 0){
            //echo 'UPDATE referrals SET referrer_id=\''.sf($ref_by).'\', `address`=\''.sf(strtoupper($order_data['member_address'])).'\', `phone`=\''.sf($order_data['member_phone']).'\', `uspa`=\''.sf($order_data['uspa']).'\' WHERE id=\''.sf($ref).'\'';
            $data = mysqli_fetch_assoc($ref);
            $ref = $data['id'];
            mysqli_query($link,'UPDATE referrals SET referrer_id=\''.sf($ref_by).'\', `address`=\''.sf(strtoupper($order_data['member_address'])).'\', `phone`=\''.sf($order_data['member_phone']).'\', `uspa`=\''.sf($order_data['uspa']).'\' WHERE id=\''.sf($ref).'\'');
        }else{
           $query = 'INSERT INTO referrals (`created`, `referrer_id`, `name`, `address`, `email`, `phone`, `uspa`, `contact_method`, `contact_window`,`project_id`,`deleted`) VALUES (NOW(), \''.sf($ref_by).'\', \''.sf($order_data['member_name']).'\', \''.sf(strtoupper($order_data['member_address'])).'\',\''.sf($order_data['member_email']).'\',\''.sf($order_data['member_phone']).'\', \''.sf($order_data['uspa']).'\', \'Email\', \''.sf($order_data['member_created']).'\', \''.sf($project_id).'\', \'\')';
            //echo $query;
            mysqli_query($link, $query);
        }
    }

                
    $meta = json_decode($project['metadata'], true);
    $global_vars = json_decode($project['global_vars'], true);
    
    if($_POST['product'] == '96')
    {     
        //$global_vars[0]['vars']['Name'] = $order_data['Dealer_Customer_Name_Reference'];
        $global_vars[0]['vars']['Name'] = $order_data['Dealer_Name'];
        $global_vars[0]['vars']['Height'] = $order_data['Height'];
        $global_vars[0]['vars']['Weight'] = $order_data['Weight'];
        $global_vars[0]['vars']['Chest'] = $order_data['Chest'];
        $global_vars[0]['vars']['Torso'] = $order_data['Torso'];
        $global_vars[0]['vars']['Waist'] = $order_data['Waist'];
        $global_vars[0]['vars']['Thigh'] = $order_data['Thigh'];
        $global_vars[0]['vars']['Inseam'] = $order_data['Inseam'];
        $global_vars[0]['vars']['Hip Bone'] = $order_data['Hip Bone'];
        $global_vars[0]['vars']['Cup'] = $order_data['Cup Size (Female Only)'];
        $global_vars[0]['vars']['Gender'] = $order_data['Gender_CB'];
        
        //$global_vars[1]['vars']['Container Size'] = $order_data['Model Size'];
        $global_vars[1]['vars']['Container Size'] = $order_data['Container_Size'];
        $global_vars[1]['vars']['Yoke Size'] = $order_data['Yoke_Size'];
        //$global_vars[1]['vars']['MLW Type'] = $order_data['Main_Left_Web_Type'];
        $global_vars[1]['vars']['Hardware Type'] = $order_data['Harness_Hardware_Type'];
        $global_vars[1]['vars']['MLW Size'] = $order_data['Yoke_Size'].'-'.$order_data['Main_Lift_Web_size'];
        $global_vars[1]['vars']['Lateral Size'] = $order_data['Lateral_Size'];
        $global_vars[1]['vars']['Leg Pad Size'] = $order_data['Leg_Pad_Size'];
        $global_vars[1]['vars']['Offset'] = $order_data['Offset'];
        $global_vars[1]['vars']['MLW Configuration'] = $order_data['Main_Left_Web_Type'];
        $global_vars[1]['vars']['Main Canopy Type'] = $order_data['Canopy_Type'];
        $global_vars[1]['vars']['Main Canopy Size'] = $order_data['Canopy_Size'];
        $global_vars[1]['vars']['Main Canopy Fabric Type'] = $order_data['Canopy_Fabric'];
        $global_vars[1]['vars']['Main Canopy Line Type'] = $order_data['Canopy_Line_Type'];
        $global_vars[1]['vars']['Reserve Canopy Type'] = $order_data['Reserve_Type'];
        $global_vars[1]['vars']['Reserve Canopy Size'] = $order_data['Reserve_Size'];
        $global_vars[1]['vars']['Reserve Canopy Fabric Type'] = $order_data['Reserve_Fabric'];
        //$global_vars[1]['vars']['RSL'] = $order_data['Reserve_Static_Line'];
        $global_vars[1]['vars']['Hardware Type'] = $order_data['Harness_Hardware_Type'];
        $global_vars[1]['vars']['Chest Strap Size'] = $order_data['Chest_Strap_Width'];
        $global_vars[1]['vars']['Chest Strap Type'] = $order_data['Chest_Strap_Type'];
        $global_vars[1]['vars']['Webbing Color'] = $order_data['Webbing_Color'];
        $global_vars[1]['vars']['Thread Color'] = (!empty($order_data['Contrasting_Thread_Color']) ? $order_data['Contrasting_Thread_Color'] : $order_data['Binding_Tape_2_Color']);
        $global_vars[1]['vars']['Pinstripe Color'] = $order_data['Pinstripe_6_Color'];
        $global_vars[1]['vars']['Fabric Type CB'] = $order_data['Fabric_Type_CB'];
        $global_vars[1]['vars']['Base Ring Size'] = $order_data['Base_Ring_Size'];
        if (strpos($order_data['UV Protectant Treatment'], 'Yes') !== false) {
            $order_data['UV Protectant Treatment'] = 'Yes';
        }else{
            $order_data['UV Protectant Treatment'] = 'No';
        }
        $global_vars[1]['vars']['UV Treatment'] = $order_data['UV Protectant Treatment'];
        
        $global_vars[2]['vars']['Fit Right Reserve Handle Color'] = $order_data['Reserve_Handle_Color'];
        $global_vars[2]['vars']['Reserve Static Line Type'] = $order_data['Reserve_Static_Line'];
        $global_vars[2]['vars']['Main PC Handle Type'] = $order_data['Main_Pilot_Chute_Handle'];
        $global_vars[2]['vars']['Main PC Handle Color'] = $order_data['MPCH_Color_1'];
        $global_vars[2]['vars']['Main PC Handle Color 1'] = $order_data['MPCH_Color_1'];
        $global_vars[2]['vars']['Main PC Handle Color 2'] = $order_data['MPCH_Color_2'];
        $global_vars[2]['vars']['Main PC Handle Color 3'] = $order_data['MPCH_Color_3'];
        $global_vars[2]['vars']['Release Handle Color'] = $order_data['Release_Handle_Color'];
        //$global_vars[2]['vars']['Reserve Handle Options'] = $order_data['Reserve_Handle_Options'];
        $global_vars[2]['vars']['Reserve Deployment handle type'] = $order_data['Reserve_Handle_Options']; 
        $global_vars[2]['vars']['Main Riser Length'] = substr($order_data['Main_Riser_Length'], 0, 2);
        $global_vars[2]['vars']['Main Riser Type'] = $order_data['Main_Riser_Type'];
        $global_vars[2]['vars']['Main Riser Ring Size'] = $order_data['Base_Ring_Size'];
        $global_vars[2]['vars']['Main Riser HW Type'] = $order_data['Harness_Hardware_Type'];
        $global_vars[2]['vars']['Main Riser Color'] = $order_data['Main_Riser_Color'];
        $global_vars[2]['vars']['Main Pilot Chute Type'] = $order_data['Main_Pilot_Chute_Type'];
        //$global_vars[2]['vars']['Main_Deployment_CB'] = $order_data['Main_Deployment_CB'];
        //$global_vars[2]['vars']['Wing Suit Option'] = $order_data['Wing_Suit_Option'];
        //$global_vars[2]['vars']['Wing_Suit_Bridle_Length_CB'] = $order_data['Wing_Suit_Bridle_Length_CB'];
        $global_vars[2]['vars']['Reserve Cap Color'] = $order_data['Reserve_PC_Top_Cap_Color'];
        $global_vars[2]['vars']['Reserve Cap Binding Tape'] = $order_data['Binding_Tape_1_Color'];
        $global_vars[2]['vars']['Binding Tape Color 1'] = $order_data['Binding_Tape_1_Color'];
        $global_vars[2]['vars']['Binding Tape Color 2'] = $order_data['Binding_Tape_2_Color'];
        $global_vars[2]['vars']['Binding Tape Color 3'] = $order_data['Binding_Tape_3_Color'];
        $global_vars[2]['vars']['Main Closing System Type'] = $order_data['Main_Closing_System_Type'];
        $global_vars[2]['vars']['Thread Color 1'] = $order_data['Thread_Color_1'];
        $global_vars[2]['vars']['Thread Color 2'] = $order_data['Thread_Color_2'];
        $global_vars[2]['vars']['Thread Color 3'] = $order_data['Thread_Color_3'];
        $global_vars[2]['vars']['Reserve Cap Thread Color'] = (!empty($order_data['Contrasting_Thread_Color']) ? $order_data['Contrasting_Thread_Color'] : $order_data['Binding_Tape_2_Color']);
        $global_vars[2]['vars']['Reserve Cap Pinstripe'] = $order_data['Pinstripe_6_Color'];
        //$order_data['Bridle_Length'] = ($order_data['Bridle_Length'] == 'KL87') ? 'MRHIX' : $order_data['Bridle_Length'];
        //$order_data['Bridle_Length'] = str_replace('KL', 'MRHIX', $order_data['Bridle_Length']);
        $order_data['Bridle_Length'] = str_replace('KL', 'COVERT', $order_data['Bridle_Length']);
        $global_vars[2]['vars']['Bridle Length'] = $order_data['Bridle_Length'];
        $global_vars[2]['vars']['Main Deployment Bag Type'] = $order_data['Main_Deployment_CB'];
        $global_vars[2]['vars']['Comfy Dive Loops'] = $order_data['comfy_dive_loops'];
        $global_vars[2]['vars']['Low Drag Risers'] = $order_data['low_drag_risers'];
    }else{
        //$global_vars[0]['vars']['Name'] = $order_data['Dealer_Customer_Name_Reference'];
        $global_vars[0]['vars']['Name'] = $order_data['Dealer_Name'];
        $global_vars[0]['vars']['Height'] = $order_data['Height'];
        $global_vars[0]['vars']['Weight'] = $order_data['Weight'];
        $global_vars[0]['vars']['Chest'] = $order_data['Chest'];
        $global_vars[0]['vars']['Torso'] = $order_data['Torso'];
        $global_vars[0]['vars']['Waist'] = $order_data['Waist'];
        $global_vars[0]['vars']['Thigh'] = $order_data['Thigh'];
        $global_vars[0]['vars']['Inseam'] = $order_data['Inseam'];
        $global_vars[0]['vars']['Hip Bone'] = $order_data['Hip Bone'];
        $global_vars[0]['vars']['Cup'] = $order_data['Cup Size (Female Only)'];
        $global_vars[0]['vars']['Gender'] = $order_data['Gender_CB'];
        
        //$global_vars[1]['vars']['Container Size'] = $order_data['Model Size'];
        $global_vars[1]['vars']['Container Size'] = $order_data['Container_Size'];
        $global_vars[1]['vars']['Yoke Size'] = $order_data['Yoke_Size'];
        //$global_vars[1]['vars']['MLW Type'] = $order_data['Main_Left_Web_Type'];
        $global_vars[1]['vars']['Hardware Type'] = $order_data['Harness_Hardware_Type'];
        $global_vars[1]['vars']['MLW Size'] = $order_data['Yoke_Size'].'-'.$order_data['Main_Lift_Web_size'];
        $global_vars[1]['vars']['Lateral Size'] = $order_data['Lateral_Size'];
        $global_vars[1]['vars']['Leg Pad Size'] = $order_data['Leg_Pad_Size'];
        $global_vars[1]['vars']['Offset'] = $order_data['Offset'];
        $global_vars[1]['vars']['MLW Configuration'] = $order_data['Main_Left_Web_Type'];
        $global_vars[1]['vars']['Main Canopy Type'] = $order_data['Canopy_Type'];
        $global_vars[1]['vars']['Main Canopy Size'] = $order_data['Canopy_Size'];
        $global_vars[1]['vars']['Main Canopy Fabric Type'] = $order_data['Canopy_Fabric'];
        $global_vars[1]['vars']['Main Canopy Line Type'] = $order_data['Canopy_Line_Type'];
        $global_vars[1]['vars']['Reserve Canopy Type'] = $order_data['Reserve_Type'];
        $global_vars[1]['vars']['Reserve Canopy Size'] = $order_data['Reserve_Size'];
        $global_vars[1]['vars']['Reserve Canopy Fabric Type'] = $order_data['Reserve_Fabric'];
        $global_vars[1]['vars']['RSL'] = $order_data['Reserve_Static_Line'];
        $global_vars[1]['vars']['Hardware Type'] = $order_data['Harness_Hardware_Type'];
        $global_vars[1]['vars']['Chest Strap Size'] = $order_data['Chest_Strap_Width'];
        $global_vars[1]['vars']['Chest Strap Type'] = $order_data['Chest_Strap_Type'];
        $global_vars[1]['vars']['Webbing Color'] = $order_data['Webbing_Color'];
        $global_vars[1]['vars']['Thread Color'] = (!empty($order_data['Contrasting_Thread_Color']) ? $order_data['Contrasting_Thread_Color'] : $order_data['Binding_Tape_2_Color']);
        $global_vars[2]['vars']['Binding Tape Color 1'] = $order_data['Binding_Tape_1_Color'];
        $global_vars[2]['vars']['Binding Tape Color 2'] = $order_data['Binding_Tape_2_Color'];
        $global_vars[2]['vars']['Binding Tape Color 3'] = $order_data['Binding_Tape_3_Color'];
        $global_vars[2]['vars']['Thread Color 1'] = $order_data['Thread_Color_1'];
        $global_vars[2]['vars']['Thread Color 2'] = $order_data['Thread_Color_2'];
        $global_vars[2]['vars']['Thread Color 3'] = $order_data['Thread_Color_3'];
        $global_vars[1]['vars']['Pinstripe Color'] = $order_data['Pinstripe_6_Color'];
        $global_vars[1]['vars']['Fabric Type CB'] = $order_data['Fabric_Type_CB'];
        if (strpos($order_data['UV Protectant Treatment'], 'Yes') !== false) {
            $order_data['UV Protectant Treatment'] = 'Yes';
        }else{
            $order_data['UV Protectant Treatment'] = 'No';
        }
        $global_vars[1]['vars']['UV Treatment'] = $order_data['UV Protectant Treatment'];
        
        $global_vars[2]['vars']['Fit Right Reserve Handle Color'] = $order_data['Reserve_Handle_Color'];
        $global_vars[2]['vars']['Reserve Static Line Type'] = $order_data['Reserve_Static_Line'];
        $global_vars[2]['vars']['Main PC Handle Type'] = $order_data['Main_Pilot_Chute_Handle'];
        $global_vars[2]['vars']['Main PC Handle Color 1'] = $order_data['MPCH_Color_1'];
        $global_vars[2]['vars']['Main PC Handle Color 2'] = $order_data['MPCH_Color_2'];
        $global_vars[2]['vars']['Main PC Handle Color 3'] = $order_data['MPCH_Color_3'];
        $global_vars[2]['vars']['Release Handle Color'] = $order_data['Release_Handle_Color'];
        //$global_vars[2]['vars']['Reserve Handle Options'] = $order_data['Reserve_Handle_Options'];
        $global_vars[2]['vars']['Reserve Deployment handle type'] = $order_data['Reserve_Handle_Options']; 
        $global_vars[2]['vars']['Main Riser Length'] = substr($order_data['Main_Riser_Length'], 0, 2);
        $global_vars[2]['vars']['Main Riser Type'] = $order_data['Main_Riser_Type'];
        $global_vars[2]['vars']['Main Riser Ring Size'] = $order_data['Base_Ring_Size'];
        $global_vars[2]['vars']['Main Riser HW Type'] = $order_data['Harness_Hardware_Type'];
        $global_vars[2]['vars']['Main Riser Color'] = $order_data['Main_Riser_Color'];
        $global_vars[2]['vars']['Main Pilot Chute Type'] = $order_data['Main_Pilot_Chute_Type'];
        //$global_vars[2]['vars']['Main_Deployment_CB'] = $order_data['Main_Deployment_CB'];
        //$global_vars[2]['vars']['Wing Suit Option'] = $order_data['Wing_Suit_Option'];
        //$global_vars[2]['vars']['Wing_Suit_Bridle_Length_CB'] = $order_data['Wing_Suit_Bridle_Length_CB'];
        $global_vars[2]['vars']['Reserve Cap Color'] = $order_data['Reserve_PC_Top_Cap_Color'];
        $global_vars[2]['vars']['Reserve Cap Binding Tape'] = $order_data['Binding_Tape_1_Color'];
        $global_vars[2]['vars']['Reserve Cap Thread Color'] = $order_data['Contrasting_Thread_Color'];
        $global_vars[2]['vars']['Reserve Cap Pinstripe'] = $order_data['Pinstripe_6_Color'];
        //$order_data['Bridle_Length'] = ($order_data['Bridle_Length'] == 'KL87') ? 'MRHIX' : $order_data['Bridle_Length'];
        //$order_data['Bridle_Length'] = str_replace('KL', 'MRHIX', $order_data['Bridle_Length']);
        $order_data['Bridle_Length'] = str_replace('KL', 'COVERT', $order_data['Bridle_Length']);
        $global_vars[2]['vars']['Bridle Length'] = $order_data['Bridle_Length'];
        $global_vars[2]['vars']['Main Deployment Bag Type'] = $order_data['Main_Deployment_CB'];
        $global_vars[2]['vars']['Comfy Dive Loops'] = $order_data['comfy_dive_loops'];
        $global_vars[2]['vars']['Low Drag Risers'] = $order_data['low_drag_risers'];
    }    
    
    //echo "Project_id :". $project_id;
    //echo "<pre>";
    //print_r($global_vars);
    //echo "</pre>";
    
    mysqli_query($link, 'UPDATE projects SET `global_vars`=\''.sf(json_encode($global_vars)).'\', peregrine_date_modified=\''.sf($order_data['Last_Update_Data']).'\' WHERE id=\''.sf($project_id).'\'');
    
    if(!empty($order_data['Production_Cycle'])) 
    {
        mysqli_query($link, 'UPDATE projects 
                                SET `pod`=\''.sf($order_data['Production_Cycle']).'\' 
                                WHERE id=\''.sf($project_id).'\'');
    }
    
            mysqli_query($link, "DELETE FROM project_parts WHERE project='".sf($project_id)."'");
            $sql = 'SELECT * FROM product_parts WHERE product=\''.sf($_POST['product']).'\' ORDER BY `p_order` ASC';
            $s_parts = mysqli_query($link, $sql);
              
            while($part = mysqli_fetch_assoc($s_parts)) 
            {
              $sql = 'INSERT INTO project_parts (`project`, `part`, `category_id`, `name`, `variables`, `batch_lot`) VALUES (\''.sf($project_id).'\', \''.sf($part['id']).'\', \''.sf($part['category']).'\', \''.sf($part['name']).'\' , \''.sf($part['variables']).'\', \''.sf($part['batch_lot']).'\')';
              //echo $sql;                            
              mysqli_query($link, $sql);
            }
            
    $parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($project_id).'\'');
    
    while($part = mysqli_fetch_assoc($parts)) 
    {
    
        $update = false;
        $vars = json_decode($part['variables'], true);
        //echo 'part_variable<hr>'.$part['variables'];
      
        if (is_array($vars) || is_object($vars))
        {
            foreach($vars as $key=>$var) 
            {
                if(!empty($var['api_name'])) 
                {
                    if(!empty($order_data[$var['api_name']])) 
                    {
                        $vars[$key]['value'] = $order_data[$var['api_name']];
                        $update = true;
                    }
                }
            }
        }
      
        //echo 'UPDATE project_parts SET variables=\''.sf(json_encode($vars)).'\' WHERE id=\''.$part['id'].'\'<br/>';
        if($update==true) {
            mysqli_query($link, 'UPDATE project_parts SET variables=\''.sf(json_encode($vars)).'\' WHERE id=\''.sf($part['id']).'\'');
        }
        
        $vars = null;
    }
    
    $sql = 'SELECT * FROM `product_sub_steps` WHERE product=\''.sf($_POST['product']).'\' ORDER BY s_order ASC';
    $s_steps = mysqli_query($link, $sql);
              
    while($sub_step = mysqli_fetch_assoc($s_steps)) 
    {
        $sql = "UPDATE `project_sub_steps` SET `part`='".sf($sub_step['part'])."', `variables`='".sf($sub_step['variables'])."' WHERE `project`='".sf($project_id)."' AND `sub_step`='".sf($sub_step['id'])."'";                            
        mysqli_query($link, $sql);
    }
            
    
    if($_POST['product'] == '96')
    {
        $dir = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Falkyn_order_'.$project_id;
    
        if(!file_exists($dir))
        {
            mkdir($dir, 0777);    
        }
    
    
        //############################# Falkyn_finaldesign.svg ##############################
        $url = 'https://design.peregrinemfginc.com/zip/Falkyn_order_'.$id.'/final_design_'.$id.'.svg';
        $ch = curl_init($url);
        $file_name = 'final_design_'.$project_id.'.svg';
        $save_file_loc = $dir .'/'. $file_name;
        $fp = fopen($save_file_loc, 'wb');
        
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        
        //############################# Falkyn EM Image ##############################
        for($i=1; $i<=20; $i++)
        {
            if($order_data['EM'.$i.' Logo'] != 'PMI TATTOO Logo' && $order_data['EM'.$i.' Logo'] != 'FALKYN Logo' && $order_data['EM'.$i.' Logo'] != 'F TATTOO Logo' && $order_data['EM'.$i.' Logo'] != 'F Logo' && $order_data['EM'.$i.' Logo'] != 'None' && $order_data['EM'.$i.' Logo'] != '')
            //if(strpos($order_data['EM'.$i.' Logo'], 'Custom Logo') !== false)
            //if($order_data['EM'.$i.' Logo'] != '' || !empty($order_data['EM'.$i.' Logo']))
            {
                $img_url = 'https://'.$des.'/zip/Falkyn_order_'.$id.'/'.$order_data['EM'.$i.' Logo'];
                $img_ch = curl_init($img_url);
                $save_em_loc = $dir .'/'. $order_data['EM'.$i.' Logo'];
                $img_fp = fopen($save_em_loc, 'wb');
                
                curl_setopt($img_ch, CURLOPT_FILE, $img_fp);
                curl_setopt($img_ch, CURLOPT_HEADER, 0);
                curl_exec($img_ch);
                curl_close($img_ch);
                fclose($img_fp);
            }
            
            if($order_data['EM'.$i.' Logo'] == 'PMI TATTOO Logo' || $order_data['EM'.$i.' Logo'] == 'FALKYN Logo' || $order_data['EM'.$i.' Logo'] == 'F TATTOO Logo' || $order_data['EM'.$i.' Logo'] == 'F Logo')
            {
                $filename = 'em'.$i.'_'.$id.'.svg';
                $img_url = 'https://'.$des.'/zip/Falkyn_order_'.$id.'/'.$filename;
                $img_ch = curl_init($img_url);
                $save_em_loc = $dir .'/'. $filename;
                $img_fp = fopen($save_em_loc, 'wb');
                
                curl_setopt($img_ch, CURLOPT_FILE, $img_fp);
                curl_setopt($img_ch, CURLOPT_HEADER, 0);
                curl_exec($img_ch);
                curl_close($img_ch);
                fclose($img_fp);
            }
        }
        
        //############################# Falkyn PDF Detail Order ##############################
        $pdf_url = 'https://design.peregrinemfginc.com/zip/Falkyn_order_'.$id.'/Falkyn_order_'.$id.'.pdf';
        $pdf_ch = curl_init($pdf_url);
        $save_pdf_loc = $dir.'/Falkyn_order_'.$project_id.'.pdf';
        $pdf_fp = fopen($save_pdf_loc, 'wb');
        curl_setopt($pdf_ch, CURLOPT_FILE, $pdf_fp);
        curl_setopt($pdf_ch, CURLOPT_HEADER, 0);
        curl_exec($pdf_ch);
        curl_close($pdf_ch);
        fclose($pdf_fp);
        
        //############################# Falkyn PDF Product Traveler ##############################
        //$pdf_url = 'https://design.peregrinemfginc.com/zip/Falkyn_order_'.$id.'/Product_Traveler_'.$id.'.pdf';
        //$pdf_ch = curl_init($pdf_url);
        //$save_pdf_loc = $dir.'/Product_Traveler_'.$project_id.'.pdf';
        //$pdf_fp = fopen($save_pdf_loc, 'wb');
        //curl_setopt($pdf_ch, CURLOPT_FILE, $pdf_fp);
        //curl_setopt($pdf_ch, CURLOPT_HEADER, 0);
        //curl_exec($pdf_ch);
        //curl_close($pdf_ch);
        //fclose($pdf_fp);
        
        //############################# Falkyn Build Certificate ##############################
        $xls_url = 'https://design.peregrinemfginc.com/zip/Falkyn_order_'.$id.'/Build_Certificate_'.$id.'.xlsx';
        $xls_ch = curl_init($xls_url);
        $save_xls_loc = $dir.'/Build_Certificate_'.$project_id.'.xlsx';

        $xls_fp = fopen($save_xls_loc, 'wb');
        curl_setopt($xls_ch, CURLOPT_FILE, $xls_fp);
        curl_setopt($xls_ch, CURLOPT_HEADER, 0);
        curl_exec($xls_ch);
        curl_close($xls_ch);
        fclose($xls_fp);
        
        //############################# Falkyn CSV Detail Order ##############################
        $output_file = 'Falkyn_order_'.$project_id.".csv";
        $output_folder = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Falkyn_order_'.$project_id.'/'.$output_file;
        
        rename('file.csv', $output_file);
        rename($output_file, $output_folder);
    }
    else
    {
        
        $dir = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Glide_order_'.$project_id;
    
        if(!file_exists($dir))
        {
            mkdir($dir, 0777);    
        }
        
        
    
        //############################# Glide final_design.svg ##############################
        $url = 'https://design.peregrinemfginc.com/zip/Glide_order_'.$id.'/final_design_'.$id.'.svg';
        $ch = curl_init($url);
        $file_name = 'final_design_'.$project_id.'.svg';
        $save_file_loc = $dir .'/'. $file_name;
        $fp = fopen($save_file_loc, 'wb');
        
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        
        //############################# Glide EM Image ##############################
        //get EM custom image
        for($i=1; $i<=16; $i++)
        {
            if($order_data['EM'.$i.' Logo'] != 'PMI' && $order_data['EM'.$i.' Logo'] != 'Glide' && $order_data['EM'.$i.' Logo'] != '')
            {
                $img_url = 'https://'.$des.'/zip/Glide_order_'.$id.'/'.$order_data['EM'.$i.' Logo'];
                
                //echo $img_url;
                
                $img_ch = curl_init($img_url);
                $save_em_loc = $dir . $order_data['EM'.$i.' Logo'];
                
                $img_fp = fopen($save_em_loc, 'wb');
                curl_setopt($img_ch, CURLOPT_FILE, $img_fp);
                curl_setopt($img_ch, CURLOPT_HEADER, 0);
                curl_exec($img_ch);
                curl_close($img_ch);
                fclose($img_fp);
            }
            
            if($order_data['EM'.$i.' Logo'] == 'PMI' || $order_data['EM'.$i.' Logo'] == 'Glide')
            {
                $filename = 'em'.$i.'_'.$id.'.svg';
                $img_url = 'https://'.$des.'/zip/Glide_order_'.$id.'/'.$filename;
                $img_ch = curl_init($img_url);
                $save_em_loc = $dir .'/'. $filename;
                $img_fp = fopen($save_em_loc, 'wb');
                
                curl_setopt($img_ch, CURLOPT_FILE, $img_fp);
                curl_setopt($img_ch, CURLOPT_HEADER, 0);
                curl_exec($img_ch);
                curl_close($img_ch);
                fclose($img_fp);
            }
        }
         
         
        //############################# Glide PDF Detail Order ##############################
        $pdf_url = 'https://design.peregrinemfginc.com/zip/Glide_order_'.$id.'/Glide_order_'.$id.'.pdf';
        $pdf_ch = curl_init($pdf_url);
        $save_pdf_loc = $dir.'/Glide_order_'.$project_id.'.pdf';
        $pdf_fp = fopen($save_pdf_loc, 'wb');
        curl_setopt($pdf_ch, CURLOPT_FILE, $pdf_fp);
        curl_setopt($pdf_ch, CURLOPT_HEADER, 0);
        curl_exec($pdf_ch);
        curl_close($pdf_ch);
        fclose($pdf_fp);
        
        //############################# Glide PDF Product Traveler ##############################
        //$pdf_url = 'https://design.peregrinemfginc.com/zip/Glide_order_'.$id.'/Product_Traveler_'.$id.'.pdf';
        //$pdf_ch = curl_init($pdf_url);
        //$save_pdf_loc = $dir.'/Product_Traveler_'.$project_id.'.pdf';
        
        //$pdf_fp = fopen($save_pdf_loc, 'wb');
        //curl_setopt($pdf_ch, CURLOPT_FILE, $pdf_fp);
        //curl_setopt($pdf_ch, CURLOPT_HEADER, 0);
        //curl_exec($pdf_ch);
        //curl_close($pdf_ch);
        //fclose($pdf_fp);
        
        //############################# Glide Build Certificate ##############################
        $xls_url = 'https://design.peregrinemfginc.com/zip/Glide_order_'.$id.'/Build_Certificate_'.$id.'.xlsx';
        $xls_ch = curl_init($xls_url);
        $save_xls_loc = $dir.'/Build_Certificate_'.$project_id.'.xlsx';

        $xls_fp = fopen($save_xls_loc, 'wb');
        curl_setopt($xls_ch, CURLOPT_FILE, $xls_fp);
        curl_setopt($xls_ch, CURLOPT_HEADER, 0);
        curl_exec($xls_ch);
        curl_close($xls_ch);
        fclose($xls_fp);
        
        //############################# Glide CSV Detail Order ##############################
        $output_file = 'Glide_order_'.$project_id.".csv";
        $output_folder = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Glide_order_'.$project_id.'/'.$output_file;
        
        rename('file.csv', $output_file);
        rename($output_file, $output_folder);

    }
    
    $pdf_ps = "UPDATE `project_steps` SET `status` = 'In Progress', started='".date('Y-m-d H:i:s')."' WHERE `project` = '".sf($project_id)."' AND `order`=0 AND started IS NOT NULL AND status!='Completed'";
    //echo $pdf_ps;
    mysqli_query($link, $pdf_ps);
    
    $pdf_pss = "UPDATE project_sub_steps SET value=1, completed=1, completed_time='".sf(date('Y-m-d H:i:s'))."',completed_by='".sf($_SESSION['name'])."' WHERE `project` = '".sf($project_id)."' AND name='Detailed Confirmation Generated and attached' AND completed!=1";
    //echo $pdf_pss;
    mysqli_query($link, $pdf_pss);
              
              
    
    
//    if(isset($_GET['p']) && $_GET['p'] == 'project_step'){
//      echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=project_step&id='.$project_id.'&get_step='.$_GET['get_step'].'&mark_as='.$_GET['mark_as'].'">';
//    }else{
//    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=project&id='.$project_id.'">';  
//    }
    //echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://design.peregrinemfginc.com/admin/orders">';
    
//    echo "<br><br><h3>Please wait we are importing order #".$id."</h3>";
    
//    if(isset($_GET['p']) && $_GET['p'] == 'project_step'){
//    echo "<br><br><h5>Click <a href='?page=project_step&id=".$project_id."&get_step=".$_GET['get_step']."&mark_as=".$_GET['mark_as'].">here</a> if your browser does not automatically redirect you</h5>";
//    }else{
//    echo "<br><br><h5>Click <a href='?page=project&id=".$project_id."'>here</a> if your browser does not automatically redirect you</h5>";    
//   }
    
//    echo "<br><br><h5>Click <a href='?page=project&id=".$project_id."'>here</a> if your browser does not automatically redirect you</h5>";    
//    echo "<script>
//            window.location.href = '?page=project&id=".$project_id."';
//        </script>";
    
    
     echo    "<script>
            window.location.href = '?page=convert&id=".$project_id."&generate=".$_SESSION['generate']."';
         </script>";
}
   
?>