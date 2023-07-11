<?php

/* ################################### SERIAL NUMBER ############################################################### */
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
                                                status != 'deleted'
                                            ORDER BY serial DESC LIMIT 10";
                            $result = mysqli_query($link, $query);
                            
                            $get_sn = array();
                            
                            while($output = mysqli_fetch_assoc($result))
                            {
                                $get_sn[] = $output['serial'];
                            }
                            
                            if(!empty($get_sn))
                            {
                                //$order_data['Serial_Number'] = min($get_sn);
                                $serial_number = chrono_trigger($get_sn);
                            } 
                            
/* ################################### PROJECTS ############################################################### */

$sql = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');
$project = mysqli_fetch_assoc($sql);
$project['peregrine_id'] = ($project['peregrine_id'] > 0) ? $project['peregrine_id'] : '0';
  
 if(isset($_GET['reload']))
 {
    $project_id = sf($_GET['cp']);
    
    $query = 'UPDATE projects SET `customer` = \''.sf($project['customer']).'\'
                                            ,`product` = \''.sf($project['product']).'\'
                                            ,`name` = \''.sf($project['name']).'\'
                                            , `location` = \''.sf($project['location']).'\'
                                            , `serial` = \''.sf($serial_number).'\'
                                            , `status` = \'started\'
                                            , `payment` = \''.sf($project['payment']).'\'
                                            , `notes` = \''.sf($project['notes']).'\'
                                            , `estimated_completion` = \''.sf($project['estimated_completion']).'\'
                                            , `priority` = \''.sf($project['priority']).'\'
                                            , `started` = \''.sf($project['started']).'\'
                                            , `pod` = \''.sf($project['pod']).'\'
                                            , `global_vars` = \''.sf($project['global_vars']).'\'
                                            , `peregrine_id` = \''.sf($project['peregrine_id']).'\'
                                            , `final_design` = \''.sf($project['final_design']).'\'
                                            , `peregrine_date_modified` = \''.sf(date("Y-m-d H:i:s")).'\'
                                            , `last_step` = \'ORDER ENTRY\'
                                            WHERE `project_id` = \''.sf($project_id).'\'';
        
        mysqli_query($link, $query);
        //echo 'UPDATE PROJECTS';
 }else{
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
                                            \''.sf($project['customer']).'\'
                                            , \''.sf($project['product']).'\'
                                            , \''.sf($project['name']).'\'
                                            , \''.sf($project['location']).'\'
                                            , \''.sf($serial_number).'\'
                                            , \'started\'
                                            , \''.sf($project['payment']).'\'
                                            , \''.sf($project['notes']).'\'
                                            , \''.sf(date("Y-m-d", strtotime($project['estimated_completion']))).'\'
                                            , \''.sf($project['priority']).'\'
                                            , \''.sf($project['started']).'\'
                                            , \''.sf($project['pod']).'\'
                                            , \''.sf($project['global_vars']).'\'
                                            , \''.sf($project['peregrine_id']).'\'
                                            , \''.sf($project['final_design']).'\'
                                            , \''.sf(date("Y-m-d H:i:s")).'\'
                                            , \'ORDER ENTRY\')';
        
        mysqli_query($link, $query);
        $project_id = mysqli_insert_id($link);
 }
 //echo $query;
        //echo 'INSERT PROJECTS';
        $cp_project = mysqli_fetch_assoc(mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($project_id).'\' AND status!=\'deleted\''));
         $upd = 'UPDATE projects SET global_vars=\''.sf($project['global_vars']).'\' WHERE id=\''.sf($project_id).'\'';
              //echo $upd.'<br/><br/>';
              mysqli_query($link, $upd);
        
/* ################################### PROJECT STEPS ############################################################### */

    $check_step = mysqli_query($link, 'SELECT * FROM project_steps WHERE project=\''.sf($project_id).'\'');
        
    if(mysqli_num_rows($check_step) > 0){    
        mysqli_query($link, 'DELETE FROM project_steps WHERE project=\''.sf($project_id).'\'');
        mysqli_query($link, 'DELETE FROM project_sub_steps WHERE project=\''.sf($project_id).'\'');
    }
        $steps = mysqli_query($link, 'SELECT * FROM product_steps WHERE product=\''.sf($cp_project['product']).'\' ORDER BY `order` ASC');
        
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
    
/* ################################### PROJECT SUB STEPS ############################################################### */
    
            $sql = 'SELECT * FROM product_sub_steps WHERE step=\''.sf($step['id']).'\' ORDER BY `s_order` ASC';
            $s_steps = mysqli_query($link, $sql);
              
            while($sub_step = mysqli_fetch_assoc($s_steps)) 
            {
              
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
        
/* ################################### PROJECT PARTS ############################################################### */

    $check_part = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($project_id).'\'');
        
        if(mysqli_num_rows($check_part) > 0){    
            mysqli_query($link, 'DELETE FROM project_parts WHERE project=\''.sf($project_id).'\'');
        }
            // add all parts
            $sql = 'SELECT * FROM product_parts WHERE product=\''.sf($cp_project['product']).'\' ORDER BY `p_order` ASC';
            $parts = mysqli_query($link, $sql);
            //echo $sql.'<br/>';
            
            while($part = mysqli_fetch_assoc($parts)) 
            {
                $sql = 'INSERT INTO project_parts (`project`, `part`, `category_id`, `name`, `variables`, `batch_lot`)
                                                VALUES (\''.$project_id.'\'
                                                        , \''.sf($part['id']).'\'
                                                        , \''.sf($part['category']).'\'
                                                        , \''.sf($part['name']).'\' 
                                                        , \''.sf($part['variables']).'\'
                                                        , \''.sf($part['batch_lot']).'\')';
                                                        
              mysqli_query($link, $sql);
              
              $sql = mysqli_fetch_assoc(mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($_GET['id']).'\' AND part=\''.sf($part['id']).'\''));
              
              $upd = 'UPDATE project_parts SET variables=\''.sf($sql['variables']).'\' WHERE project=\''.sf($project_id).'\' AND part=\''.sf($part['id']).'\'';
              //echo $upd.'<br/><br/>';
              mysqli_query($link, $upd);
            }
            
            
/* ################################### REFERRALS ###############################################################

    if(empty($_SESSION['referral_id']) || $_SESSION['referral_id'] == '')
    {
        $ref_user = mysqli_query($link, 'SELECT * FROM `referral_users` WHERE `project_id`=\''.sf($_GET['id']).'\'');
        
        if(mysqli_num_rows($ref_user) == 0){
            $query = 'INSERT INTO referral_users (`created`, `referrer_id`, `email`, `name`, `address`,`phone`, `dz`,`payment`, `uspa`, `credits`,`referal_code`,`deleted`) 
                                            VALUES (NOW()
                                                    , \''.sf($ref_user['referrer_id']).'\'
                                                    , \''.sf($ref_user['email']).'\'
                                                    , \''.sf(strtoupper($ref_user['name'])).'\'
                                                    ,\''.sf($ref_user['address']).'\'
                                                    ,\''.sf($ref_user['phone']).'\'
                                                    ,\''.sf($ref_user['dz']).'\'
                                                    ,\''.sf($ref_user['payment']).'\'
                                                    ,\''.sf($ref_user['uspa']).'\'
                                                    ,\''.sf($ref_user['credits']).'\'
                                                    , \''.sf($ref_user['referal_code']).'\'
                                                    , \'\')';
            //echo $query;
            mysqli_query($link, $query);
            $referral_id = mysqli_insert_id($link);
        
            echo 'INSERT REFERRAL';
            $_SESSION['referral_id'] = $referral_id;
        }
    }
       */
/* ################################### SVG - PDF FILES ############################################################### */

    if($cp_project['product'] == '96')
    {
        // Store the path of source file
        $source = $_SERVER['DOCUMENT_ROOT'].'/'.$mng.'/zip/Falkyn_order_'.$_GET['id'];
          
        // Store the path of destination file
        $destination = $_SERVER['DOCUMENT_ROOT'].'/'.$mng.'/zip/Falkyn_order_'.$project_id;
    }else{
        // Store the path of source file
        $source = $_SERVER['DOCUMENT_ROOT'].'/'.$mng.'/zip/Glide_order_'.$_GET['id'];
          
        // Store the path of destination file
        $destination = $_SERVER['DOCUMENT_ROOT'].'/'.$mng.'/zip/Glide_order_'.$project_id;   
    }
    
        $dir = opendir($source);
    
        if(!file_exists($destination))
        {
            mkdir($destination, 0777);    
        }
        
        // Loop through the files in source directory
        while( $file = readdir($dir) ) { 
      
            if (( $file != '.' ) && ( $file != '..' )) { 
                
                $new_file = str_replace("_".$_GET['id'], "_".$project_id , $file);
                
                if ( is_dir($source . '/' . $file) ) 
                { 
      
                    // Recursively calling custom copy function
                    // for sub directory 
                    copy($source . '/' . $file, $destination . '/' . $new_file); 
      
                } 
                else { 
                    copy($source . '/' . $file, $destination . '/' . $new_file); 
                } 
            } 
        } 
  
       echo "<script>
            window.location.href = '?page=project&id=".$project_id."';
        </script>";
?>