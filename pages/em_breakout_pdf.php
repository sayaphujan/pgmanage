<?php

    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
    $product_id = intval($_GET['product']);
    $order_id  = intval($_GET['design_id']);
    $project_id  = intval($_GET['id']);
    
    $request = "https://".$des."/do/api_em_breakout/?id=".sf($order_id).'&product='.sf($product_id);    

    $session = curl_init($request);
    curl_setopt ($session, CURLOPT_POST, true);
    curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
    curl_setopt($session, CURLOPT_HEADER, 0);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($session);
    curl_close($session);
    
    $order_options = json_decode($response, true);

    //echo "<pre>";
    //print_r($order_options);
    //echo "</pre>";
    
    
    $color_hex =  [
                    'transparent' => 'None',
                    '#000000' => 'Black',
                    '#F7F7F7' => 'White',
                    '#6A6A6A' => 'Charcoal',
                    '#898E8C' => 'Silver',
                    '#355749' => 'Forest Green',
                    '#2d8447' => 'Kelly Green',
                    '#28FD46' => 'Neon Green',
                    '#D3BF80' => 'Tan',
                    '#937F4F' => 'Khaki',
                    '#5E473D' => 'Brown',
                    '#314AA4' => 'Royal Blue',
                    '#4E5675' => 'Navy Blue',
                    '#4494f0' => 'Electric Blue',
                    '#009e9a' => 'Teal',
                    '#493A5C' => 'Purple',
                    '#E3CADD' => 'Pink',
                    '#FB0069' => 'Neon Pink',
                    '#D5362D' => 'Orange',
                    '#F44E2F' => 'Neon Orange',
                    '#C2103B' => 'Red',
                    '#501922' => 'Burgandy',
                    '#D1D800' => 'Yellow',
                    '#E3FF00' => 'Neon Yellow',
                    '#FEC12B' => 'Gold',
                    '#CC0000' => 'Red',
                  ];
    foreach($order_options as $key => $val){
        if(isset($color_hex[$val]))
            $order_options[$key] = $color_hex[$val];
    }
    
    
    if($product_id == '96'){$p = 'Falkyn'; $c = '20'; }else{$p='Glide'; $c = '16'; }
    
    if($product_id == '96')
    {
        $dir = $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/zip/Falkyn_order_".$project_id;
    }
    else
    {
        $dir = $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/zip/Glide_order_".$project_id;
    }
        
    for($i=1;$i<=$c;$i++)
    {  
        $order_options['img-em'.$i.'_1'] = ($order_options['img-em'.$i.'_1'] == '') ? 'None' : $order_options['img-em'.$i.'_1'];
        $order_options['text_logo_field-em'.$i.'_1'] = ($order_options['text_logo_field-em'.$i.'_1'] == '') ? 'None' : $order_options['text_logo_field-em'.$i.'_1'];
        $order_options['em'.$i.'_2'] = ($order_options['em'.$i.'_1'] == 'None' && $order_options['img-em'.$i.'_1'] == 'None' && $order_options['text_logo_field-em'.$i.'_1'] == 'None') ? 'Transparent' : $order_options['em'.$i.'_2'];
        $order_options['em'.$i.'_3'] = ($order_options['em'.$i.'_1'] == 'None' && $order_options['img-em'.$i.'_1'] == 'None' && $order_options['text_logo_field-em'.$i.'_1'] == 'None') ? 'Transparent' : $order_options['em'.$i.'_3'];
    }

$header = '';
$footer = '';
$content .='
<style>
    .box {
        border: 1px solid #ccc!important;
    }
    .svgtext{
        paint-order: stroke;
        font-style: normal;
        font-weight: normal;
        fill-opacity: 1;
        stroke-width: 1px;
        stroke-linecap: butt;
        stroke-linejoin: miter;
        stroke-opacity: 1;
        font-size: 15px;
    }
svg {
    width: 300px;
    height: 300px;
}
p {
    color: #000000;
}
body {
    background: #FFFFFF;
}
.lead.measure{
    border-bottom: 1px solid black;
    width: 300px;
}
h2{
    color: black;
}
svg
{
    width: 300px;
    height: 410px;
}
    
@media print {
    .pagebreak { 
        page-break-before: always; 
    }
    .introduction{
        padding-top: 0px;
    }
    .no-print{
        display:none;
    }
    #final_design{
        width: 1900px !important;
    }
    .no-padding{
        padding-top: 0px !important;
    }
    .total-estimate{
        width: 100% !important;
        max-width: 100% !important;
        flex: none;
    }
    nav{
        display: none;
    }
}
</style>
<page>
<div class="container">
    <h3 class="pt-5">Embroidery:</h3>';
                            $z = 0;
                            
                            for($i=1;$i<=$c;$i++)
                            {
                                if($order_options['em'.$i.'_1'] != 'None')
                                {
                                    $z++;
                                    if($z == 3)
                                    { 
                                        $break = 'pagebreak'; 
                                        $z = 0;
                                    }
                                    else
                                    {
                                        $break = ''; 
                                    }
                                    
                                    $content .='<div class="row print no-padding '.$break.'">
                                                    <div class="col-sm-12"><h2>EM'.$i.' - '.$order_options['em'.$i.'_1'].'</h2></div>
                                                        <div class="col-sm-12 mb-5">
                                                            <div class="row">
                                                                <div class="col-sm-5 box">';
                                                    if(preg_match('/Custom Logo/', $order_options['em'.$i.'_1']))
                                                    {
                                                        //if client use emb format
                                                        //for now lets replace using
                                                        //other dummy text
                                                        if(preg_match('/\.emb/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/emb-file.png';
                                                        }
                                                        else if(preg_match('/\.dst/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/dst-file.png';
                                                        }
                                                        else if(preg_match('/\.EMB/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/emb-file.png';
                                                        }
                                                        else if(preg_match('/\.DST/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/dst-file.png';
                                                        }
                                                        else
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/'.$order_options['img-em'.$i.'_1'];
                                                        }
                                                    }
                                                    //else if(preg_match('/Custom Text/', $order_options['em'.$i.'_1']))
                                                    else
                                                    {
                                                        $logo = 'https://'.$des.'/zip/'.$p.'_order_'.$order_id.'/em'.$i.'_'.$order_id.'.svg';
                                                    }
                                                    
                                                    $handler = curl_init($logo);
                                                    curl_setopt($handler,  CURLOPT_RETURNTRANSFER, TRUE);
                                                    $re = curl_exec($handler);
                                                    $httpcdd = curl_getinfo($handler, CURLINFO_HTTP_CODE);
                                                    
                                                    if ($httpcdd == '404')
                                                    { 
                                                        //$logo = '';
                                                        echo "<script>window.location.href = 'https://'.$des.'/em-breakout/?design_id=".$order_id."&product=".$product_id."&status=render';</script>";
                                                    } 
                                                    else 
                                                    { 
                                                        $logo = $logo; 
                                                    }
                                                    
                                                    $order_options['em1_2_pgt2-gt']     = ($order_options['em1_2_pgt2-gt'] == 'None' ) ? 'Transparent' : $order_options['em1_2_pgt2-gt'];
                                                    $order_options['em1_2_pgt2-pmi']    = ($order_options['em1_2_pgt2-pmi'] == 'None' ) ? 'Transparent' : $order_options['em1_2_pgt2-pmi'];
                                                    $order_options['em1_3_pgt2']        = ($order_options['em1_3_pgt2'] == 'None' ) ? 'Transparent' : $order_options['em1_3_pgt2'];
                                                    
                                                    $order_options['em2_2_pgt3-glide']  = ($order_options['em2_2_pgt3-glide'] == 'None' ) ? 'Transparent' : $order_options['em2_2_pgt3-glide'];
                                                    $order_options['em2_2_pgt3-dash']   = ($order_options['em2_2_pgt3-dash'] == 'None' ) ? 'Transparent' : $order_options['em2_2_pgt3-dash'];
                                                    $order_options['em2_3_pgt3-inner']  = ($order_options['em2_3_pgt3-inner'] == 'None' ) ? 'Transparent' : $order_options['em2_3_pgt3-inner'];
                                                    $order_options['em2_3_pgt3-outer']  = ($order_options['em2_3_pgt3-outer'] == 'None' ) ? 'Transparent' : $order_options['em2_3_pgt3-outer'];
                                                    
                                                    $order_options['em3_2_pgt1-gt']     = ($order_options['em3_2_pgt1-gt'] == 'None' ) ? 'Transparent' : $order_options['em3_2_pgt1-gt'];
                                                    $order_options['em3_2_pgt1-pmi']    = ($order_options['em3_2_pgt1-pmi'] == 'None' ) ? 'Transparent' : $order_options['em3_2_pgt1-pmi'];
                                                    $order_options['em3_2_pgt1-peregrine']    = ($order_options['em3_2_pgt1-peregrine'] == 'None' ) ? 'Transparent' : $order_options['em3_2_pgt1-peregrine'];
                                                    $order_options['em3_3_pgt1']        = ($order_options['em3_3_pgt1'] == 'None' ) ? 'Transparent' : $order_options['em3_3_pgt1'];
                                                    
                                                    
                                                    
                                    $content .='    <img src="'.$logo.'"  width="300px">
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    &nbsp;
                                                                </div>
                                                                <div class="col-sm-6">';
                                                    if(preg_match('/Custom/', $order_options['em'.$i.'_1']) || preg_match('/PMI Logo/', $order_options['em'.$i.'_1']) || preg_match('/Glide Logo/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_2'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_3'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else if(preg_match('/PGT/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">GT Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em1_2_pgt2-gt'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">PMI Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em1_2_pgt2-pmi'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em1_3_pgt2'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else if(preg_match('/GlideGT/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">Glide Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_2_pgt3-glide'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Dash Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_2_pgt3-dash'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Inner Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_3_pgt3-inner'].'</p>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_3_pgt3-outer'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else if(preg_match('/GTSW/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">GT Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_2_pgt1-gt'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">PMI Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_2_pgt1-pmi'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Peregrine Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_2_pgt1-peregrine'].'</p>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_3_pgt1'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else
                                                    {
                                                        
                                                                                                
                                                                                    if($order_options['new_falkyn'] == 'true')
                                                                                    {
                                                                                            if(preg_match('/F TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                 echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle7-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle6-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle5-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 5
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle8-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 6
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                        ';
                                                                                            }
                                                                                            else if(preg_match('/PMI TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                 echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle3-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle5-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                        ';
                                                                                            }
                                                                                            else if(preg_match('/FALKYN/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                  echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle3-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle7-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 5
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle13-1'].'</p>
                                                                                                            <br/>
                                                                                                        
                                                                                                        ';
                                                                                            }
                                                                                            else if(preg_match('/F/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle6-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle7-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle8-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 5
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle3-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 6
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 7
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle5-1'].'</p>
                                                                                                            <br/>
                                                                                                        ';
                                                                                            }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        for($j=1;$j<=16;$j++)
                                                                                        {
                                                                                            $order_options['em'.$i.'_needle'.$j] = ($order_options['em'.$i.'_needle'.$j] == '' || $order_options['em'.$i.'_needle'.$j] == 'none' ) ? 'None' : $order_options['em'.$i.'_needle'.$j];
                                                                                            $needle = '
                                                                                                       <p class="lead measure">Needle '.$j.'
                                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle'.$j].'</p>
                                                                                                ';
                                                                                                
                                                                                            if(preg_match('/F TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='2' || $j=='4' || $j=='5' || $j=='6' || $j=='8' || $j=='16')
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            }
                                                                                            else if(preg_match('/PMI TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='1' || $j=='2' || $j=='8' || $j=='12' || $j=='16' )
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            }
                                                                                            else if(preg_match('/FALKYN/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='1' || $j=='2' || $j=='3' || $j=='7' || $j=='8' || $j=='9' || $j=='12' || $j=='16' )
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            }
                                                                                            else if(preg_match('/F/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='1' || $j=='2' || $j=='8' || $j=='16' )
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            } 
                                                                                        }
                                                                                    }
                                                                                
                                                    }
                                    $content .='                </div>
                                                            </div>
                                                        </div>
                                                    </div>';

                                }
                            }
        $content .='</div>
</div>
</page>
        <htmlpagefooter name="myFooter1">
          <div class="container">
            <div class="row">
             <div class="col-md-12">   
              <center><img src="'.$_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/images/hr.JPG"></center>
             </div>
            </div>
            <div class="row">
             <div class="col-md-4">   
              <b>{PAGENO} of {nbpg}</b>
             </div>
             <di1v class="col-md-8">   
              <img src="'.$_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/images/footer_peregrine.jpg" width="60%" style="float:right;">
             </div>
            </div>
          </div>
        </htmlpagefooter>
        <sethtmlpagefooter name="myFooter1" value="1"></sethtmlpagefooter>';
                
                require $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/vendor/autoload.php";
                $mpdf = new \Mpdf\Mpdf([]);
                $mpdf->WriteHTML($content);
                $mpdf->Output($dir.'/EM_Breakout_'.$project_id.'.pdf', 'F');
                unset($mpdf);
?>
<script>

$(document).ready(function()
{
     $.notify('Emroidery Breakout have been succesfully export to PDF!', 'success');
     
     window.location.href = '?page=em_breakout&&id=<?=$project_id;?>&design_id=<?=$order_id;?>&product=<?=$product_id;?>';
});

</script>
