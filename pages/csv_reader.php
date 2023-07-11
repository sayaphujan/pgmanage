<?php 

    $loc = root().'zip/Glide_order_'.$_GET['id'].'/Glide_order_'.$_GET['id'].'.csv';
    
    //echo root().'zip/Glide_order_'.$_GET['id'].'/Glide_order_'.$_GET['id'].'.csv';
    
    if (($fp = fopen($loc, 'r')) !== FALSE)
    {
        $csv = array_map('str_getcsv', file($loc));
		
		    array_walk($csv, function(&$a) use ($csv) {
			  $a = array_combine($csv[0], $a);
			});
			
			array_shift($csv);
			
			
			$order_data = $csv[0];
			
			echo "<h2>DATA FROM CSV Project Id #".$_GET['id']."</h2>";
			
			echo nl2br(print_r($order_data, true)); 
			/*
			echo "<hr/>";
			echo "<h2>DATA FROM DB Project Id #".$_GET['id']."</h2>";
            			
            //I realized after designing the system with json vars, that this next part will be painful.. Oh well.
			$parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.sf($_GET['id']).'\'');
			
			while($part = mysqli_fetch_assoc($parts)) {
				
				$update = false;
				
				$vars = json_decode($part['variables'], true);
				
				//echo $part['variables'];
				echo "<pre>";
			    print_r($part['variables']);
			    echo "</pre>";
				
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
			}
			*/
    }  
?>