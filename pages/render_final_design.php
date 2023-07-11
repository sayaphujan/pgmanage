<?php
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    
    $id = sf($_GET['id']);
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
    //print_r(json_decode($response));
    //echo "</pre>";
    //die();
    
    $decoded_file = json_decode($response, true);
    //$designers = $decoded_file['designers'];
    $measurement = $decoded_file['measurement'];
    
    $values = $decoded_file['options'];
    $options = $values;
    $val_color = $decoded_file['colors'];
    //$final_design = $decoded_file['final_design'];
    
    for($i=1; $i<=16; $i++)
    {  
        $values['img-em'.$i.'_1'] = ($values['img-em'.$i.'_1'] == '') ? 'blank.png' : $values['img-em'.$i.'_1'];
    }
    
    foreach($measurement as $key=>$value)
    {
        $measurement[$key] = preg_replace('/"/', '&quot;', $value);
    }
?>

<link href="https://design.peregrinemfginc.com/css/designer-v5.css" rel="stylesheet">
<script type="text/javascript" src="https://design.peregrinemfginc.com/js/notify.js"></script>
<style>
/* Solve problem where border size changes on hover */
.lastview
{
  background-color: white;
  height: 410px;
  overflow: hidden;
}
.lastview div
{
  margin-top: -115px;
}
.lastview:hover .image{ 
  opacity: 1;
} 

.image {
  opacity: 1;
  display: block;
  transition: .5s ease;
  backface-visibility: hidden;
}

.buttonview{
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}

.lastview:hover .buttonview{ 
  opacity: 1;
} 

.measure{
    /*color :#007bff;*/
    color :;#e0e0e0;
    font-weight: bold;
}

tr, td {
  padding: 15px;
  text-align: left;
}

td {
  font-weight: bold;
  font-size: 1.25rem;
}

td.measure{
    /*color :#007bff;*/
    color :;#e0e0e0;
}

p.detail-info{
    font-size: 18px;
}

p.customer-notes{
  font-weight: bold;
  font-size: 1.25rem;
}

label {
    display: inline-block;
    margin-bottom: .5rem;
    min-width: 240px;
}

.rotateccw {
  -webkit-transform:rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  transform: rotate(270deg);
}

.rotatecw {
  -webkit-transform:rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  transform: rotate(90deg);
}

.rotate45ccw {
  -webkit-transform:rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  transform: rotate(23deg);
}

.rotate45cw {
  -webkit-transform:rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  transform: rotate(158deg);
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
  table td{
    padding: 0px;
  }
  .total-estimate{
    width: 100% !important;
    max-width: 100% !important;
    flex: none;
  }
}

.notifyjs-corner{
  position: fixed !important;
  z-index: 99999 !important;
}
.container{
  opacity: 0.2;
}
</style>
<script>
    $.notify('We are rendering your final design...please wait', { autoHide: false, className: 'info' })
</script>
<div class="container">

    <div class="row">

        <div class="col-sm-12 pt-5 introduction">

            <h1 class="no-print">Confirmation</h1>

            <h3 class="pt-3 no-print"></h3>

            <p class="lead no-print" align="justify">

                Please double check your design, options, and all information below. You may go back to any section again. You will be 
                sent this information again in an email confirmation. From that point you will have 3 days to correct any mistakes.

            </p>

            <h3 class="pt-3 print">Designs:</h3>

            <p class="lead no-print" align="justify">

                If you'd like to edit your design, click on the image below and you will be taken back to the designer. You will not lose
                any of your information.

            </p>

            <div class="row lastview" >
                
                <div class="col-md-12 pt-3" style="overflow: hidden">
                    <div class="row common-design-container" style="margin-top: 115px">
                        <div class="row" style="margin-top: 0px; ">
                         
                          <div class="rig-image glide-box" id="html" contenteditable="">
                            <?php
                                echo file_get_contents("https://design.peregrinemfginc.com/pages/templates/default.svg"); 
                            ?>
                          </div>
                            
                          <img class="modal-content custom-logo-svg" id="svgimg1" src="/images/upload/<?php echo $values['img-em1_1']; ?>" style="position: absolute;width: 30px;margin-top: 185px;margin-left: 575px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg2" src="/images/upload/<?php echo $values['img-em2_1']; ?>" style="position: absolute; width: 40px; margin-top: 310px;margin-left: 110px; background-color: transparent; border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg3" src="/images/upload/<?php echo $values['img-em3_1']; ?>" style="position: absolute; width: 40px; margin-top: 310px;margin-left: 225px; background-color: transparent; border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg4" src="/images/upload/<?php echo $values['img-em4_1']; ?>" style="position: absolute;width: 30px;margin-top: 150px;margin-left: 174px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg5" src="/images/upload/<?php echo $values['img-em5_1']; ?>" style="position: absolute;width: 60px;margin-top: 225px;margin-left: 508px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg6" src="/images/upload/<?php echo $values['img-em6_1']; ?>" style="position: absolute;width: 33px;margin-top: 188px;margin-left: 471px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg7" src="/images/upload/<?php echo $values['img-em7_1']; ?>" style="position: absolute;width: 30px;margin-top: 85px;margin-left: 110px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg8" src="/images/upload/<?php echo $values['img-em8_1']; ?>" style="position: absolute;width: 30px;margin-top: 83px;margin-left: 238px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg9" src="/images/upload/<?php echo $values['img-em9_1']; ?>" style="position: absolute;width: 15px;margin-top: 410px;margin-left: 390px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg10" src="/images/upload/<?php echo $values['img-em10_1']; ?>" style="position: absolute;width: 15px;margin-top: 410px;margin-left: 670px;background-color: transparent;border: none;">
                         
                          <img class="modal-content custom-logo-svg" id="svgimg11" src="/images/upload/<?php echo $values['img-em11_1']; ?>" style="position: absolute;width: 15px;margin-top: 286px;margin-left: 488px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg12" src="/images/upload/<?php echo $values['img-em12_1']; ?>" style="position: absolute;width: 13px;margin-top: 288px;margin-left: 574px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg13" src="/images/upload/<?php echo $values['img-em13_1']; ?>" style="position: absolute;width: 17px;margin-top: 286px;margin-left: 465px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg14" src="/images/upload/<?php echo $values['img-em14_1']; ?>" style="position: absolute;width: 17px;margin-top: 280px;margin-left: 594px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg15" src="/images/upload/<?php echo $values['img-em15_1']; ?>" style="position: absolute;width: 22px;margin-top: 366px;margin-left: 70px;background-color: transparent;border: none;">
                          
                          <img class="modal-content custom-logo-svg" id="svgimg16" src="/images/upload/<?php echo $values['img-em16_1']; ?>" style="position: absolute;width: 20px;margin-top: 368px;margin-left: 285px;background-color: transparent;border: none;">
                                    
                        </div>
                        
                    </div>
                    
                    <img id="final_design" src="" class="image print print-svg" style="margin-left: -15px">
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://design.peregrinemfginc.com/js/svg-pan-zoom.js"></script>
<script>
var colors = {};
var template_id = <?php echo $decoded_file['template_id']; ?>;

$(document).ready(function()
{
    <?php   
      foreach($values as $key => $value)
      {  
          if(preg_match('/^em([0-9][0-9]|[0-9])_3/', $key))
          {               
              $temp_key = explode('_', $key);
              $temp_key = preg_replace('/em/', '', $temp_key[0]);
              echo "$('#svgimg$temp_key').css('filter', 'drop-shadow(1px 0px 0 $value) drop-shadow(-1px 0px 0 $value) drop-shadow(0px -1px 0 $value) drop-shadow(0px 1px 0 $value)');\n";
          }
      }
    ?>
    
    panZoomInstance = svgPanZoom('#glide', {
        zoomEnabled: true,
        controlIconsEnabled: true,
        mouseWheelZoomEnabled: false,
        fit: true,
        center: true,
        minZoom: 0.8,
        maxZoom: 4.8,
        zoomScaleSensitivity: 0.07,
        onZoom: function(){},
        onPan: function(){},
    });
    
    //zoom out
    panZoomInstance.zoom(0.90);
    panZoomInstance.pan({x: 70, y: 73})
    
    //load base64 format for the pattern
    //we load raw format to replace src in image
    //it is needed because if we use link it wont get exported into canvas
    cordura_001_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-001_highlander.gif")); ?>'
    cordura_001_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-001_raid.gif")); ?>'
    cordura_001_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-001_typhon.gif")); ?>'
    cordura_001_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-001_multicam.gif")); ?>'
    cordura_001_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-001_black-multicam.gif")); ?>'
    cordura_001_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-001_woodland-camo.gif")); ?>'
    $('#cordura-001_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_001_highlander);
    $('#cordura-001_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_001_raid);
    $('#cordura-001_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_001_typhon);
    $('#cordura-001_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_001_multicam);
    $('#cordura-001_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_001_black_multicam);
    $('#cordura-001_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_001_woodland_camo);
    
    cordura_003_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-003_highlander.gif")); ?>'
    cordura_003_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-003_raid.gif")); ?>'
    cordura_003_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-003_typhon.gif")); ?>'
    cordura_003_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-003_multicam.gif")); ?>'
    cordura_003_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-003_black-multicam.gif")); ?>'
    cordura_003_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-003_woodland-camo.gif")); ?>'
    $('#cordura-003_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_003_highlander);
    $('#cordura-003_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_003_raid);
    $('#cordura-003_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_003_typhon );
    $('#cordura-003_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_003_multicam);
    $('#cordura-003_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_003_black_multicam);
    $('#cordura-003_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_003_woodland_camo);
    
    cordura_004_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-004_highlander.gif")); ?>'
    cordura_004_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-004_raid.gif")); ?>'
    cordura_004_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-004_typhon.gif")); ?>'
    cordura_004_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-004_multicam.gif")); ?>'
    cordura_004_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-004_black-multicam.gif")); ?>'
    cordura_004_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-004_woodland-camo.gif")); ?>'
    $('#cordura-004_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_004_highlander);
    $('#cordura-004_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_004_raid);
    $('#cordura-004_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_004_typhon );
    $('#cordura-004_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_004_multicam);
    $('#cordura-004_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_004_black_multicam);
    $('#cordura-004_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_004_woodland_camo);
    
    cordura_005_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-005_highlander.gif")); ?>'
    cordura_005_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-005_raid.gif")); ?>'
    cordura_005_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-005_typhon.gif")); ?>'
    cordura_005_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-005_multicam.gif")); ?>'
    cordura_005_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-005_black-multicam.gif")); ?>'
    cordura_005_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-005_woodland-camo.gif")); ?>'
    $('#cordura-005_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_005_highlander);
    $('#cordura-005_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_005_raid);
    $('#cordura-005_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_005_typhon );
    $('#cordura-005_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_005_multicam);
    $('#cordura-005_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_005_black_multicam);
    $('#cordura-005_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_005_woodland_camo);
    
    cordura_006_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-006_highlander.gif")); ?>'
    cordura_006_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-006_raid.gif")); ?>'
    cordura_006_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-006_typhon.gif")); ?>'
    cordura_006_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-006_multicam.gif")); ?>'
    cordura_006_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-006_black-multicam.gif")); ?>'
    cordura_006_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-006_woodland-camo.gif")); ?>'
    $('#cordura-006_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_006_highlander);
    $('#cordura-006_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_006_raid);
    $('#cordura-006_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_006_typhon );
    $('#cordura-006_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_006_multicam);
    $('#cordura-006_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_006_black_multicam);
    $('#cordura-006_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_006_woodland_camo);
    
    cordura_007_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-007_highlander.gif")); ?>'
    cordura_007_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-007_raid.gif")); ?>'
    cordura_007_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-007_typhon.gif")); ?>'
    cordura_007_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-007_multicam.gif")); ?>'
    cordura_007_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-007_black-multicam.gif")); ?>'
    cordura_007_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-007_woodland-camo.gif")); ?>'
    $('#cordura-007_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_007_highlander);
    $('#cordura-007_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_007_raid);
    $('#cordura-007_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_007_typhon );
    $('#cordura-007_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_007_multicam);
    $('#cordura-007_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_007_black_multicam);
    $('#cordura-007_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_007_woodland_camo);
    
    cordura_008_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-008_highlander.gif")); ?>'
    cordura_008_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-008_raid.gif")); ?>'
    cordura_008_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-008_typhon.gif")); ?>'
    cordura_008_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-008_multicam.gif")); ?>'
    cordura_008_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-008_black-multicam.gif")); ?>'
    cordura_008_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-008_woodland-camo.gif")); ?>'
    $('#cordura-008_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_008_highlander);
    $('#cordura-008_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_008_raid);
    $('#cordura-008_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_008_typhon );
    $('#cordura-008_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_008_multicam);
    $('#cordura-008_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_008_black_multicam);
    $('#cordura-008_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_008_woodland_camo);
    
    cordura_011_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-011_highlander.gif")); ?>'
    cordura_011_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-011_raid.gif")); ?>'
    cordura_011_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-011_typhon.gif")); ?>'
    cordura_011_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-011_multicam.gif")); ?>'
    cordura_011_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-011_black-multicam.gif")); ?>'
    cordura_011_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-011_woodland-camo.gif")); ?>'
    $('#cordura-011_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_011_highlander);
    $('#cordura-011_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_011_raid);
    $('#cordura-011_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_011_typhon );
    $('#cordura-011_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_011_multicam);
    $('#cordura-011_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_011_black_multicam);
    $('#cordura-011_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_011_woodland_camo);
    
    cordura_012_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-012_highlander.gif")); ?>'
    cordura_012_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-012_raid.gif")); ?>'
    cordura_012_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-012_typhon.gif")); ?>'
    cordura_012_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-012_multicam.gif")); ?>'
    cordura_012_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-012_black-multicam.gif")); ?>'
    cordura_012_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-012_woodland-camo.gif")); ?>'
    $('#cordura-012_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_012_highlander);
    $('#cordura-012_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_012_raid);
    $('#cordura-012_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_012_typhon );
    $('#cordura-012_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_012_multicam);
    $('#cordura-012_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_012_black_multicam);
    $('#cordura-012_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_012_woodland_camo);
    
    cordura_013_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-013_highlander.gif")); ?>'
    cordura_013_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-013_raid.gif")); ?>'
    cordura_013_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-013_typhon.gif")); ?>'
    cordura_013_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-013_multicam.gif")); ?>'
    cordura_013_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-013_black-multicam.gif")); ?>'
    cordura_013_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-013_woodland-camo.gif")); ?>'
    $('#cordura-013_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_013_highlander);
    $('#cordura-013_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_013_raid);
    $('#cordura-013_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_013_typhon );
    $('#cordura-013_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_013_multicam);
    $('#cordura-013_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_013_black_multicam);
    $('#cordura-013_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_013_woodland_camo);
    
    cordura_014_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-014_highlander.gif")); ?>'
    cordura_014_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-014_raid.gif")); ?>'
    cordura_014_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-014_typhon.gif")); ?>'
    cordura_014_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-014_multicam.gif")); ?>'
    cordura_014_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-014_black-multicam.gif")); ?>'
    cordura_014_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-014_woodland-camo.gif")); ?>'
    $('#cordura-014_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_014_highlander);
    $('#cordura-014_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_014_raid);
    $('#cordura-014_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_014_typhon );
    $('#cordura-014_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_014_multicam);
    $('#cordura-014_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_014_black_multicam);
    $('#cordura-014_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_014_woodland_camo);
    
    cordura_015_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-015_highlander.gif")); ?>'
    cordura_015_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-015_raid.gif")); ?>'
    cordura_015_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-015_typhon.gif")); ?>'
    cordura_015_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-015_multicam.gif")); ?>'
    cordura_015_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-015_black-multicam.gif")); ?>'
    cordura_015_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-015_woodland-camo.gif")); ?>'
    $('#cordura-015_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_015_highlander);
    $('#cordura-015_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_015_raid);
    $('#cordura-015_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_015_typhon );
    $('#cordura-015_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_015_multicam);
    $('#cordura-015_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_015_black_multicam);
    $('#cordura-015_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_015_woodland_camo);
    
    cordura_016_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-016_highlander.gif")); ?>'
    cordura_016_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-016_raid.gif")); ?>'
    cordura_016_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-016_typhon.gif")); ?>'
    cordura_016_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-016_multicam.gif")); ?>'
    cordura_016_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-016_black-multicam.gif")); ?>'
    cordura_016_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-016_woodland-camo.gif")); ?>'
    $('#cordura-016_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_016_highlander);
    $('#cordura-016_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_016_raid);
    $('#cordura-016_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_016_typhon );
    $('#cordura-016_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_016_multicam);
    $('#cordura-016_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_016_black_multicam);
    $('#cordura-016_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_016_woodland_camo);
    
    cordura_017_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-017_highlander.gif")); ?>'
    cordura_017_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-017_raid.gif")); ?>'
    cordura_017_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-017_typhon.gif")); ?>'
    cordura_017_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-017_multicam.gif")); ?>'
    cordura_017_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-017_black-multicam.gif")); ?>'
    cordura_017_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-017_woodland-camo.gif")); ?>'
    $('#cordura-017_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_017_highlander);
    $('#cordura-017_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_017_raid);
    $('#cordura-017_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_017_typhon );
    $('#cordura-017_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_017_multicam);
    $('#cordura-017_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_017_black_multicam);
    $('#cordura-017_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_017_woodland_camo);
    
    cordura_019_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-019_highlander.gif")); ?>'
    cordura_019_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-019_raid.gif")); ?>'
    cordura_019_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-019_typhon.gif")); ?>'
    cordura_019_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-019_multicam.gif")); ?>'
    cordura_019_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-019_black-multicam.gif")); ?>'
    cordura_019_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-019_woodland-camo.gif")); ?>'
    $('#cordura-019_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_019_highlander);
    $('#cordura-019_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_019_raid);
    $('#cordura-019_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_019_typhon );
    $('#cordura-019_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_019_multicam);
    $('#cordura-019_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_019_black_multicam);
    $('#cordura-019_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_019_woodland_camo);
    
    cordura_023_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-023_highlander.gif")); ?>'
    cordura_023_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-023_raid.gif")); ?>'
    cordura_023_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-023_typhon.gif")); ?>'
    cordura_023_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-023_multicam.gif")); ?>'
    cordura_023_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-023_black-multicam.gif")); ?>'
    cordura_023_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-023_woodland-camo.gif")); ?>'
    $('#cordura-023_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_023_highlander);
    $('#cordura-023_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_023_raid);
    $('#cordura-023_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_023_typhon );
    $('#cordura-023_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_023_multicam);
    $('#cordura-023_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_023_black_multicam);
    $('#cordura-023_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_023_woodland_camo);
    
    cordura_23_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-23_highlander.gif")); ?>'
    cordura_23_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-23_raid.gif")); ?>'
    cordura_23_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-23_typhon.gif")); ?>'
    cordura_23_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-23_multicam.gif")); ?>'
    cordura_23_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-23_black-multicam.gif")); ?>'
    cordura_23_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-23_woodland-camo.gif")); ?>'
    $('#cordura-23_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_23_highlander);
    $('#cordura-23_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_23_raid);
    $('#cordura-23_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_23_typhon );
    $('#cordura-23_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_23_multicam);
    $('#cordura-23_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_23_black_multicam);
    $('#cordura-23_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_23_woodland_camo);
    
    cordura_24_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-24_highlander.gif")); ?>'
    cordura_24_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-24_raid.gif")); ?>'
    cordura_24_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-24_typhon.gif")); ?>'
    cordura_24_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-24_multicam.gif")); ?>'
    cordura_24_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-24_black-multicam.gif")); ?>'
    cordura_24_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-24_woodland-camo.gif")); ?>'
    $('#cordura-24_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_24_highlander);
    $('#cordura-24_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_24_raid);
    $('#cordura-24_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_24_typhon );
    $('#cordura-24_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_24_multicam);
    $('#cordura-24_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_24_black_multicam);
    $('#cordura-24_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_24_woodland_camo);
    
    cordura_031_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-031_highlander.gif")); ?>'
    cordura_031_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-031_raid.gif")); ?>'
    cordura_031_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-031_typhon.gif")); ?>'
    cordura_031_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-031_multicam.gif")); ?>'
    cordura_031_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-031_black-multicam.gif")); ?>'
    cordura_031_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-031_woodland-camo.gif")); ?>'
    $('#cordura-031_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_031_highlander);
    $('#cordura-031_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_031_raid);
    $('#cordura-031_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_031_typhon );
    $('#cordura-031_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_031_multicam);
    $('#cordura-031_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_031_black_multicam);
    $('#cordura-031_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_031_woodland_camo);
    
    cordura_041_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-041_highlander.gif")); ?>'
    cordura_041_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-041_raid.gif")); ?>'
    cordura_041_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-041_typhon.gif")); ?>'
    cordura_041_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-041_multicam.gif")); ?>'
    cordura_041_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-041_black-multicam.gif")); ?>'
    cordura_041_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-041_woodland-camo.gif")); ?>'
    $('#cordura-041_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_041_highlander);
    $('#cordura-041_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_041_raid);
    $('#cordura-041_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_041_typhon );
    $('#cordura-041_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_041_multicam);
    $('#cordura-041_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_041_black_multicam);
    $('#cordura-041_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_041_woodland_camo);
    
    cordura_042_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-042_highlander.gif")); ?>'
    cordura_042_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-042_raid.gif")); ?>'
    cordura_042_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-042_typhon.gif")); ?>'
    cordura_042_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-042_multicam.gif")); ?>'
    cordura_042_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-042_black-multicam.gif")); ?>'
    cordura_042_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-042_woodland-camo.gif")); ?>'
    $('#cordura-042_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_042_highlander);
    $('#cordura-042_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_042_raid);
    $('#cordura-042_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_042_typhon );
    $('#cordura-042_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_042_multicam);
    $('#cordura-042_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_042_black_multicam);
    $('#cordura-042_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_042_woodland_camo);
    
    webbing_043_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-043_highlander.gif")); ?>'
    webbing_043_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-043_raid.gif")); ?>'
    webbing_043_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-043_typhon.gif")); ?>'
    webbing_043_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-043_multicam.gif")); ?>'
    webbing_043_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-043_black-multicam.gif")); ?>'
    webbing_043_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-043_woodland-camo.gif")); ?>'
    $('#webbing-043_highlander image').attr('xlink:href', 'data:image/png;base64,'+webbing_043_highlander);
    $('#webbing-043_raid image').attr('xlink:href', 'data:image/png;base64,'+webbing_043_raid);
    $('#webbing-043_typhon image').attr('xlink:href', 'data:image/png;base64,'+webbing_043_typhon );
    $('#webbing-043_multicam image').attr('xlink:href', 'data:image/png;base64,'+webbing_043_multicam);
    $('#webbing-043_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+webbing_043_black_multicam);
    $('#webbing-043_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+webbing_043_woodland_camo);
    
    cordura_044_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-044_highlander.gif")); ?>'
    cordura_044_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-044_raid.gif")); ?>'
    cordura_044_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-044_typhon.gif")); ?>'
    cordura_044_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-044_multicam.gif")); ?>'
    cordura_044_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-044_black-multicam.gif")); ?>'
    cordura_044_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-044_woodland-camo.gif")); ?>'
    $('#cordura-044_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_044_highlander);
    $('#cordura-044_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_044_raid);
    $('#cordura-044_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_044_typhon );
    $('#cordura-044_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_044_multicam);
    $('#cordura-044_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_044_black_multicam);
    $('#cordura-044_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_044_woodland_camo);
    
    cordura_050_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-050_highlander.gif")); ?>'
    cordura_050_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-050_raid.gif")); ?>'
    cordura_050_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-050_typhon.gif")); ?>'
    cordura_050_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-050_multicam.gif")); ?>'
    cordura_050_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-050_black-multicam.gif")); ?>'
    cordura_050_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-050_woodland-camo.gif")); ?>'
    $('#cordura-050_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_050_highlander);
    $('#cordura-050_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_050_raid);
    $('#cordura-050_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_050_typhon );
    $('#cordura-050_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_050_multicam);
    $('#cordura-050_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_050_black_multicam);
    $('#cordura-050_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_050_woodland_camo);
    
    cordura_051_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-051_highlander.gif")); ?>'
    cordura_051_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-051_raid.gif")); ?>'
    cordura_051_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-051_typhon.gif")); ?>'
    cordura_051_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-051_multicam.gif")); ?>'
    cordura_051_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-051_black-multicam.gif")); ?>'
    cordura_051_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-051_woodland-camo.gif")); ?>'
    $('#cordura-051_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_051_highlander);
    $('#cordura-051_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_051_raid);
    $('#cordura-051_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_051_typhon );
    $('#cordura-051_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_051_multicam);
    $('#cordura-051_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_051_black_multicam);
    $('#cordura-051_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_051_woodland_camo);
    
    webbing_045_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-045_highlander.gif")); ?>'
    webbing_045_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-045_raid.gif")); ?>'
    webbing_045_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-045_typhon.gif")); ?>'
    webbing_045_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-045_multicam.gif")); ?>'
    webbing_045_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-045_black-multicam.gif")); ?>'
    webbing_045_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/webbing-045_woodland-camo.gif")); ?>'
    $('#webbing-045_highlander image').attr('xlink:href', 'data:image/png;base64,'+webbing_045_highlander);
    $('#webbing-045_raid image').attr('xlink:href', 'data:image/png;base64,'+webbing_045_raid);
    $('#webbing-045_typhon image').attr('xlink:href', 'data:image/png;base64,'+webbing_045_typhon );
    $('#webbing-045_multicam image').attr('xlink:href', 'data:image/png;base64,'+webbing_045_multicam);
    $('#webbing-045_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+webbing_045_black_multicam);
    $('#webbing-045_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+webbing_045_woodland_camo);
    
    cordura_009_left_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-left_highlander.gif")); ?>'
    cordura_009_left_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-left_raid.gif")); ?>'
    cordura_009_left_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-left_typhon.gif")); ?>'
    cordura_009_left_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-left_multicam.gif")); ?>'
    cordura_009_left_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-left_black-multicam.gif")); ?>'
    cordura_009_left_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-left_woodland-camo.gif")); ?>'
    $('#cordura-009-left_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_left_highlander);
    $('#cordura-009-left_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_left_raid);
    $('#cordura-009-left_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_left_typhon );
    $('#cordura-009-left_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_left_multicam);
    $('#cordura-009-left_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_left_black_multicam);
    $('#cordura-009-left_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_left_woodland_camo);
    
    cordura_009_right_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-right_highlander.gif")); ?>'
    cordura_009_right_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-right_raid.gif")); ?>'
    cordura_009_right_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-right_typhon.gif")); ?>'
    cordura_009_right_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-right_multicam.gif")); ?>'
    cordura_009_right_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-right_black-multicam.gif")); ?>'
    cordura_009_right_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-009-right_woodland-camo.gif")); ?>'
    $('#cordura-009-right_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_right_highlander);
    $('#cordura-009-right_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_right_raid);
    $('#cordura-009-right_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_right_typhon );
    $('#cordura-009-right_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_right_multicam);
    $('#cordura-009-right_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_right_black_multicam);
    $('#cordura-009-right_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_009_right_woodland_camo);
    
    cordura_right_handle_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-right-handle_highlander.gif")); ?>'
    cordura_right_handle_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-right-handle_raid.gif")); ?>'
    cordura_right_handle_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-right-handle_typhon.gif")); ?>'
    cordura_right_handle_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-right-handle_multicam.gif")); ?>'
    cordura_right_handle_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-right-handle_black-multicam.gif")); ?>'
    cordura_right_handle_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-right-handle_woodland-camo.gif")); ?>'
    $('#cordura-right-handle_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_right_handle_highlander);
    $('#cordura-right-handle_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_right_handle_raid);
    $('#cordura-right-handle_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_right_handle_typhon );
    $('#cordura-right-handle_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_right_handle_multicam);
    $('#cordura-right-handle_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_right_handle_black_multicam);
    $('#cordura-right-handle_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_right_handle_woodland_camo);
    
    cordura_riser_cover_left_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-left_highlander.gif")); ?>'
    cordura_riser_cover_left_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-left_raid.gif")); ?>'
    cordura_riser_cover_left_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-left_typhon.gif")); ?>'
    cordura_riser_cover_left_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-left_multicam.gif")); ?>'
    cordura_riser_cover_left_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-left_black-multicam.gif")); ?>'
    cordura_riser_cover_left_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-left_woodland-camo.gif")); ?>'
    $('#cordura-riser-cover-left_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_left_highlander);
    $('#cordura-riser-cover-left_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_left_raid);
    $('#cordura-riser-cover-left_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_left_typhon );
    $('#cordura-riser-cover-left_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_left_multicam);
    $('#cordura-riser-cover-left_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_left_black_multicam);
    $('#cordura-riser-cover-left_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_left_woodland_camo);
    
    cordura_riser_cover_right_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-right_highlander.gif")); ?>'
    cordura_riser_cover_right_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-right_raid.gif")); ?>'
    cordura_riser_cover_right_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-right_typhon.gif")); ?>'
    cordura_riser_cover_right_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-right_multicam.gif")); ?>'
    cordura_riser_cover_right_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-right_black-multicam.gif")); ?>'
    cordura_riser_cover_right_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-riser-cover-right_woodland-camo.gif")); ?>'
    $('#cordura-riser-cover-right_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_right_highlander);
    $('#cordura-riser-cover-right_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_right_raid);
    $('#cordura-riser-cover-right_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_right_typhon );
    $('#cordura-riser-cover-right_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_right_multicam);
    $('#cordura-riser-cover-right_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_right_black_multicam);
    $('#cordura-riser-cover-right_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_riser_cover_right_woodland_camo);
    
    cordura_legpad_highlander = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-legpad_highlander.gif")); ?>'
    cordura_legpad_raid = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-legpad_raid.gif")); ?>'
    cordura_legpad_typhon = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-legpad_typhon.gif")); ?>'
    cordura_legpad_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-legpad_multicam.gif")); ?>'
    cordura_legpad_black_multicam = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-legpad_black-multicam.gif")); ?>'
    cordura_legpad_woodland_camo = '<?php echo base64_encode(file_get_contents("https://design.peregrinemfginc.com//pages/templates/cordura-legpad_woodland-camo.gif")); ?>'
    $('#cordura-legpad_highlander image').attr('xlink:href', 'data:image/png;base64,'+cordura_legpad_highlander);
    $('#cordura-legpad_raid image').attr('xlink:href', 'data:image/png;base64,'+cordura_legpad_raid);
    $('#cordura-legpad_typhon image').attr('xlink:href', 'data:image/png;base64,'+cordura_legpad_typhon );
    $('#cordura-legpad_multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_legpad_multicam);
    $('#cordura-legpad_black-multicam image').attr('xlink:href', 'data:image/png;base64,'+cordura_legpad_black_multicam);
    $('#cordura-legpad_woodland-camo image').attr('xlink:href', 'data:image/png;base64,'+cordura_legpad_woodland_camo);
    
    
    //initialization
    //initially hide all custom logo
    $(".custom-logo-svg").hide();
    colors_apply();
    colors_init();
    save_final_design();
    
    //1 Stealth - 5 Stripes - D-Handle
    //2 Stealth - 5 Stripes - Pillow Handle
    //5 Stainless - 5 Stripes - D-Handle
    //6 Stainless - 5 Stripes - Pillow Handle 
    //this will show handle based on template selected
    //this will show handle based on template selected
    setTimeout(function()
    {
        $("#handle_1").hide();
        $("#handle_2").hide();
        $("#handle_3").hide();
        $("#cordura-left-handle").hide();      
        
        <?php if($decoded_file['template_id'] == 1) { ?>
        $("#handle_1").show();
        $("#handle_2").show();
        $("#handle_3").show();
        $('path[fill="#F7F7F7"]').attr('fill', '#161616');
        <?php } ?>
        
        <?php if($decoded_file['template_id'] == 2) { ?>
        $("#cordura-left-handle").show();
        $('path[fill="#F7F7F7"]').attr('fill', '#161616');
        <?php } ?>
        
        <?php if($decoded_file['template_id'] == 5) { ?>
        $("#handle_1").show();
        $("#handle_2").show();
        $("#handle_3").show();
        $('path[fill="#161616"]').attr('fill', '#F7F7F7');
        <?php } ?>
        
        <?php if($decoded_file['template_id'] == 6) { ?>
        $("#cordura-left-handle").show();
        $('path[fill="#161616"]').attr('fill', '#F7F7F7');
        <?php } ?>
        
    }, 500);
})

function colors_init(template_id)
{
    $.post('/do/template_save/', { template_id : template_id});
}

function colors_apply()
{
    $.getJSON('/do/colors_get/?design_id=<?php echo $decoded_file['template_id']; ?>', function(result)
    {
        $.each(result, function(key, val)
        {
            if(key.match(/thread/))
            {
                $('#'+key).attr('stroke', val);
                colors[key] = val;
            }    
            else if(key.match(/pinstripes/))
            {
                //applying svg
                $('#'+key).attr('stroke', val);
                
                //applying dropdown
                key = key.replace(/-/, '_');
                $('#'+key).val(val);
                colors[key] = val;
            }
            else
            {
                $('#'+key).attr('style', val);
                colors[key] = val;
            }
        })
    }).done(function()
    {
        <?php 
        foreach($values as $key => $value)
        { 
            if(preg_match('/^em([0-9][0-9]|[0-9])_3/', $key))
            {               
                $temp_key = explode('_', $key);
                $temp_key = preg_replace('/em/', '', $temp_key[0]);
                echo "$('#svgimg$temp_key').css('filter', 'drop-shadow(1px 0px 0 $value) drop-shadow(-1px 0px 0 $value) drop-shadow(0px -1px 0 $value) drop-shadow(0px 1px 0 $value)');\n";
            }
        
            if(preg_match('/^em/', $key))
            {
                $temp_key = explode('_', $key);
                $temp_key = preg_replace('/em/', '', $temp_key[0]);
                
                if(preg_match('/Glide/', $value))
                {
                    echo "$('#svgimg$temp_key').hide();\n";
                    echo "$('#cordura-logo-em{$temp_key}_pmi').hide();\n";
                    echo "$('#cordura-outline-em{$temp_key}_pmi').hide();\n";
                    echo "$('#cordura-logo-em{$temp_key}_glide').show();\n";
                    echo "$('#cordura-outline-em{$temp_key}_glide').show();\n";
                }
                
                if(preg_match('/PMI/', $value))
                {
                    echo "$('#svgimg$temp_key').hide();\n";
                    echo "$('#cordura-logo-em{$temp_key}_pmi').show();\n";
                    echo "$('#cordura-outline-em{$temp_key}_pmi').show();\n";
                    echo "$('#cordura-logo-em{$temp_key}_glide').hide();\n";
                    echo "$('#cordura-outline-em{$temp_key}_glide').hide();\n";
                }
                
                if(preg_match('/Custom/', $value))
                {
                    echo "$('#svgimg$temp_key').show();\n";
                    echo "$('#cordura-logo-em{$temp_key}_pmi').hide();\n";
                    echo "$('#cordura-outline-em{$temp_key}_pmi').hide();\n";
                    echo "$('#cordura-logo-em{$temp_key}_glide').hide();\n";
                    echo "$('#cordura-outline-em{$temp_key}_glide').hide();\n";
                }
            }
        }
        ?>
    })
}


//function save_final_design(next_page)
function save_final_design()
{
    //panZoomInstance.zoom(0.9);
    //panZoomInstance.pan({x: 70, y: 73});
    
    setTimeout(function()
    {
        var svg_serialize = new XMLSerializer().serializeToString(document.getElementById("glide"))
        var final_data = window.btoa(svg_serialize);
    
        $('#final_design').attr('src', 'data:image/svg+xml;base64,'+final_data);
        
        parameters = { final_design : $('#final_design').attr('src'), ajax : 1, design_id : '<?php echo $decoded_file['design_id']; ?>' };
        
        $.ajax({
            url: "/do/design_save/",
            type: "POST",
            data: parameters,
            success: function(){
                //if(next_page != '')
                //    window.location = next_page;
                $('.common-design-container').hide();
                $('.notifyjs-wrapper').trigger('notify-hide');
                $('.container').css('opacity', '1')
            },
            error: function(){
                $.notify('Problem occured please try again')
            }
        });
    }, 1000)
}
</script>
