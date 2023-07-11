<?php
    $query = 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'';
    $qr = mysqli_query($link, $query);
    $r = mysqli_fetch_assoc($qr);
    $project_id = $r['id'];
    $id         = $r['peregrine_id'];
    $product    = $r['product'];
    
    if(!isset($_GET['generate'])){
        echo "<body onload='render();'></body>";
    }   
    
    if(isset($_GET['generate'])){
    echo "<script>$.notify('Document is being generated, please wait!', 'default')</script>";
            $final_design = $r['final_design'];
            $data = explode(',', $final_design);
            $svg = base64_decode($data[1]);
            
        $s  = sf(md5($id.'peregrin3!'));
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
        
        unset($decoded_file['options']);
        unset($decoded_file['colors']);
        unset($decoded_file['measurement']);
        
        $decoded_file['data']['Customer_Notes'] = preg_replace( "/\r|\n/", " ", $decoded_file['data']['Customer_Notes']);
        
        $fp = fopen('file.csv', 'w');
    
        
        if($r['product'] == '96')
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
    }
?>
<script>
    function render(){
        window.location.href = 'https://<?=$des;?>render-generate/?design_id=<?=$id;?>&product=<?=$product;?>&project=<?=$project_id;?>&generate=true';
    }
</script>