<?php
        $check_sn = mysqli_query($link, 'SELECT * FROM projects WHERE serial=2794');
        
            if(mysqli_num_rows($check_sn) < 1)
            {
                    $order_data['Serial_Number'] = '2794';
            }
            else
            {
               
                $get_sn=array();
                $check_sn = mysqli_query($link, 'SELECT * FROM projects WHERE serial=2799');
                        if(mysqli_num_rows($check_sn) < 1)
                        {
                                $sn = '2799';
                        }
                        else
                        {
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
                        }
                        print_r($get_sn);
                        sort($get_sn, SORT_NUMERIC);
                        $prev = False;
                        foreach($get_sn as $num) {
                            echo "<br/>num :".$num;
                            if($prev === False) {
                                $prev = $num;
                                continue;
                            }
                            if($prev != ($num-1)) {
                                return $prev+1;
                            }
                            $prev = $num;
                        }
                        echo "<br/>prev :".$prev;
                        
            }
        
?>