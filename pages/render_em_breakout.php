<?php
$order_id = isset($_GET["design_id"]) ? $_GET["design_id"] : '';
$product_id = isset($_GET["product"]) ? $_GET["product"] : '';
$project_id = isset($_GET["project"]) ? $_GET["project"] : '';
$generate = isset($_GET["generate"]) ? $_GET["generate"] : '';

$_SESSION['design_id']  = $order_id;
$_SESSION['product']    = $product_id;
$_SESSION['generate'] = $generate;

    $query    = "SELECT * FROM `designers` WHERE `id` = '".make_safe($design_id)."'";   

    $result = mysqli_query($link, $query);
    $order_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $order_data = $order_data[0];
    $order_options = unserialize($order_data['options']);
    $options = unserialize($order_data['options']);
    $colors = unserialize($order_data['colors']);
    
    
    for($i=1;$i<=20;$i++)
    {  
        $order_options['img-em'.$i.'_1'] = ($order_options['img-em'.$i.'_1'] == '') ? 'None' : $order_options['img-em'.$i.'_1'];
        $order_options['text_logo_field-em'.$i.'_1'] = ($order_options['text_logo_field-em'.$i.'_1'] == '') ? 'None' : $order_options['text_logo_field-em'.$i.'_1'];
        $order_options['em'.$i.'_2'] = ($order_options['em'.$i.'_1'] == 'None' && $order_options['img-em'.$i.'_1'] == 'None' && $order_options['text_logo_field-em'.$i.'_1'] == 'None') ? 'None' : $order_options['em'.$i.'_2'];
        $order_options['em'.$i.'_3'] = ($order_options['em'.$i.'_1'] == 'None' && $order_options['img-em'.$i.'_1'] == 'None' && $order_options['text_logo_field-em'.$i.'_1'] == 'None') ? 'None' : $order_options['em'.$i.'_3'];
        
        if(strpos($order_options['text_logo_field-em'.$i.'_1'], '"') !== false){
            //echo 'em'.$i;
            $order_options['text_logo_field-em'.$i.'_1'] = str_replace('"','\"', $order_options['text_logo_field-em'.$i.'_1']);
            //echo '<br>'.$order_options['text_logo_field-em'.$i.'_1'];
        }
    }
    
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
    
    //echo"<pre style='color:#000'>";
    //print_r($order_options);
    //echo"</pre>";
    //echo $order_data['final_design'].'<br/><hr/>';

    $svg_id = array();
                            
                            for($i=1;$i<=20;$i++)
                            {
                                if($order_options['em'.$i.'_1'] != 'None')
                                {
                                    //if(preg_match('/Custom Logo/', $order_options['em'.$i.'_1']))
                                    //{
                                    //     $logo = "<img src='".root()."images/upload/".$order_options['img-em'.$i.'_1']."' width='300px'>";
                                    //}
                                    //else 
                                    if(preg_match('/Custom Text/', $order_options['em'.$i.'_1']))
                                    {
                                        $logo = file_get_contents($_SERVER['DOCUMENT_ROOT']."/pages/templates/falkyn/em".$i."_text.svg");
                                        $svg_id['em_'.$i] = "Layer_em".$i;
                                    }
                                    else
                                    {
                                        if($order_options['em'.$i.'_1'] == 'FALKYN')
                                        {
                                            $svg = "em".$i."_fk.svg";
                                        }
                                        else if($order_options['em'.$i.'_1'] == 'PMI TATTOO')
                                        {
                                            $svg = "em".$i."_pmi.svg";
                                        }
                                        else if($order_options['em'.$i.'_1'] == 'F')
                                        {
                                            $svg = "em".$i."_f.svg";
                                        }
                                        else if($order_options['em'.$i.'_1'] == 'F TATTOO')
                                        {
                                            $svg = "em".$i."_ft.svg";
                                        }
                                        else if(strpos($order_options['em'.$i.'_1'], 'PMI Logo') !== false)
                                        {
                                            $svg = "em".$i."_pmig.svg";
                                        }
                                        else if(strpos($order_options['em'.$i.'_1'], 'Glide Logo') !== false)
                                        {
                                            $svg = "em".$i."_glideg.svg";
                                        }
                                        else if(strpos($order_options['em'.$i.'_1'], 'PGT Logo') !== false)
                                        {
                                            $svg = "em".$i."_pmigtg.svg";
                                        }
                                        else if(strpos($order_options['em'.$i.'_1'], 'GlideGT Logo') !== false)
                                        {
                                            $svg = "em".$i."_glidegtg.svg";
                                        }
                                        else if(strpos($order_options['em'.$i.'_1'], 'GTSW Logo') !== false)
                                        {
                                            $svg = "em".$i."_gtswg.svg";
                                        }
                                        
                                        $logo = file_get_contents($_SERVER['DOCUMENT_ROOT']."/pages/templates/falkyn/".$svg);
                                        $svg_id['em_'.$i] = str_replace(".svg", "",$svg);
                                        //$logo ='';
                                        //$svg_id['em_'.$i] = '';
                                    }

                                    echo '
                                    <img id="em_'.$i.'" src=""><br>
                                    <input type="hidden" id="render-success-em_'.$i.'" value=""><br>
                                    <div id="output" style="width:50px">'.$logo.'</div>';
                                }
                            }
                        ?>
        
<script>

$(document).ready(function()
{
    var generate = "<?=$_SESSION['generate'];?>";
    colors_apply();  
    text_apply();
    
    colors = {};
    
     <?php 
        foreach($svg_id as $key => $value)
        {
            $name = str_replace("_","",$key)
    ?>
    $.notify('We are rendering your embroidery...please wait', { autoHide: false, className: 'info' })
    setTimeout(function(){
         save_final_design("<?=$key;?>","<?=$value;?>","<?=$name;?>",);
    }, 50000);
    
    <?php
        }
    ?>
    
    setTimeout(function(){
            //window.location.href='<?=root();?>admin/build_certificate/?order_id=<?=$order_id;?>&product=<?=$product_id;?>&status=export'; 
            
            if(generate == 'true'){
                window.location.href='https://projects.ndevix.com/peregrinemanage/?page=generate&id=<?=$_GET['project'];?>&generate=true';
            }else{
                window.location.href='https://projects.ndevix.com/peregrinemanage/?page=em_breakout&design_id=<?=$order_id;?>&product=<?=$product_id;?>';
            }
    }, 100000);
    
});

String.prototype.stripSlashes = function(){
    return this.replace(/\\(.)/mg, "$1");
}

function save_final_design(em, svg_id, name)
{

    //$.notify('Render Embroidery '+svg_id+'...please wait', { autoHide: true, className: 'info' })
    
    var id = '<?php echo $_SESSION["design_id"]; ?>';
    var product = '<?php echo $_SESSION["product"]; ?>';
    
        var svg = document.getElementById(svg_id); // or whatever you call it
        var serializer = new XMLSerializer();
        var str = serializer.serializeToString(svg);
        var final = window.btoa(str);
        //document.getElementById("output").innerHTML = 'data:image/svg+xml;base64,'+final;
        
    parameters = { final_em : 'data:image/svg+xml;base64,'+final, em: name, ajax : 1, design_id : id, product : product, generate : generate };
    
    $.ajax({
        url: "<?=root();?>do/render_em/",
        type: "POST",
        data: parameters,
        success: function(result)
        {
            $('#render-success').val(1);
            //if($("#render-success-"+em).val() == 1)
            //{
            //   $.notify('Render Embroidery Success', { autoHide: true, className: 'success' })
            //}
        },
        error: function(){
            $.notify('Problem occured please try again')
        }
    });
    
}

function text_apply()
{
        $("#text_em1").html("<?=$order_options['text_logo_field-em1_1'];?>");    
        $("#text_em4").html("<?=$order_options['text_logo_field-em4_1'];?>");    
        $("#text_em6").html("<?=$order_options['text_logo_field-em6_1'];?>");    
        $("#text_em7").html("<?=$order_options['text_logo_field-em7_1'];?>");    
        $("#text_em8").html("<?=$order_options['text_logo_field-em8_1'];?>");    
        $("#text_em9").html("<?=$order_options['text_logo_field-em9_1'];?>");    
        $("#text_em10").html("<?=$order_options['text_logo_field-em10_1'];?>");    
        $("#text_em11").html("<?=$order_options['text_logo_field-em11_1'];?>");    
        $("#text_em12").html("<?=$order_options['text_logo_field-em12_1'];?>");    
        $("#text_em13").html("<?=$order_options['text_logo_field-em13_1'];?>");    
        $("#text_em14").html("<?=$order_options['text_logo_field-em14_1'];?>");    
        $("#text_em15").html("<?=$order_options['text_logo_field-em15_1'];?>");    
        $("#text_em16").html("<?=$order_options['text_logo_field-em16_1'];?>");    
        $("#text_em17").html("<?=$order_options['text_logo_field-em17_1'];?>");    
        $("#text_em18").html("<?=$order_options['text_logo_field-em18_1'];?>");    
        $("#text_em19").html("<?=$order_options['text_logo_field-em19_1'];?>");    
        $("#text_em20").html("<?=$order_options['text_logo_field-em20_1'];?>");    
        
        $("#text_em1").attr({"fill":"<?=$order_options['em1_2'];?>", "stroke":"<?=$order_options['em1_3'];?>", "font-family":"<?=$order_options['font_logo_field-em1_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em4").attr({"fill":"<?=$order_options['em4_2'];?>", "stroke":"<?=$order_options['em4_3'];?>", "font-family":"<?=$order_options['font_logo_field-em4_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em6").attr({"fill":"<?=$order_options['em6_2'];?>", "stroke":"<?=$order_options['em6_3'];?>", "font-family":"<?=$order_options['font_logo_field-em6_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em7").attr({"fill":"<?=$order_options['em7_2'];?>", "stroke":"<?=$order_options['em7_3'];?>", "font-family":"<?=$order_options['font_logo_field-em7_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em8").attr({"fill":"<?=$order_options['em8_2'];?>", "stroke":"<?=$order_options['em8_3'];?>", "font-family":"<?=$order_options['font_logo_field-em8_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em9").attr({"fill":"<?=$order_options['em9_2'];?>", "stroke":"<?=$order_options['em9_3'];?>", "font-family":"<?=$order_options['font_logo_field-em9_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em10").attr({"fill":"<?=$order_options['em10_2'];?>", "stroke":"<?=$order_options['em10_3'];?>", "font-family":"<?=$order_options['font_logo_field-em10_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em11").attr({"fill":"<?=$order_options['em11_2'];?>", "stroke":"<?=$order_options['em11_3'];?>", "font-family":"<?=$order_options['font_logo_field-em11_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em12").attr({"fill":"<?=$order_options['em12_2'];?>", "stroke":"<?=$order_options['em12_3'];?>", "font-family":"<?=$order_options['font_logo_field-em12_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em13").attr({"fill":"<?=$order_options['em13_2'];?>", "stroke":"<?=$order_options['em13_3'];?>", "font-family":"<?=$order_options['font_logo_field-em13_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em14").attr({"fill":"<?=$order_options['em14_2'];?>", "stroke":"<?=$order_options['em14_3'];?>", "font-family":"<?=$order_options['font_logo_field-em14_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em15").attr({"fill":"<?=$order_options['em15_2'];?>", "stroke":"<?=$order_options['em15_3'];?>", "font-family":"<?=$order_options['font_logo_field-em15_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em16").attr({"fill":"<?=$order_options['em16_2'];?>", "stroke":"<?=$order_options['em16_3'];?>", "font-family":"<?=$order_options['font_logo_field-em16_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em17").attr({"fill":"<?=$order_options['em17_2'];?>", "stroke":"<?=$order_options['em17_3'];?>", "font-family":"<?=$order_options['font_logo_field-em17_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em18").attr({"fill":"<?=$order_options['em18_2'];?>", "stroke":"<?=$order_options['em18_3'];?>", "font-family":"<?=$order_options['font_logo_field-em18_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em19").attr({"fill":"<?=$order_options['em19_2'];?>", "stroke":"<?=$order_options['em19_3'];?>", "font-family":"<?=$order_options['font_logo_field-em19_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        $("#text_em20").attr({"fill":"<?=$order_options['em20_2'];?>", "stroke":"<?=$order_options['em20_3'];?>", "font-family":"<?=$order_options['font_logo_field-em20_1'];?>", "style":"display:block; paint-order: stroke; font-style: normal; font-weight: normal; fill-opacity: 1; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-size: 15px;"});
        
    
}

function colors_apply()
{
    
    $.getJSON('<?=root("/do/colors_get/?design_id=".$_SESSION['design_id']); ?>', function(result)
    {
        $.each(result, function(key, val)
        {
            //console.log(key+" "+val);
            
            $('#'+key).attr('style', val);
            //weird somehow its get hidden
            //so we have to show this panel
            if(val.match(/fill/))
            {
                $('#'+key).show();        
            }
        })
    }).done(function()
    {
        
        //console.log(colors);
        
        <?php 
        foreach($order_options as $key => $value)
            {
                if(preg_match('/^em/', $key))
                {
                }
                
                if(preg_match('/^em([0-9][0-9]|[0-9])_3/', $key))
                {               
                    $temp_key = explode('_', $key);
                    $temp_key = preg_replace('/em/', '', $temp_key[0]);
                    echo "$('#svgimg$temp_key').css('filter', 'drop-shadow(1px 0px 0 $value) drop-shadow(-1px 0px 0 $value) drop-shadow(0px -1px 0 $value) drop-shadow(0px 1px 0 $value)');\n";
                }
            }
        ?>
    })
}
</script>