<?php
$_SESSION['generate'] = (isset($_GET["generate"])) ? $_GET["generate"] : '';

?>
<html>
  <head>
    <script type="text/javascript" src="<?php echo root();?>savesvgaspng.js"></script>
    <script src="/peregrinemanage/js/svg-pan-zoom.js"></script>
  </head>
  <body onload="convert()">
      <br><br>
      <p>Please wait we are updating your design data...</p>
    <?php
        $que = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');

        while($res = mysqli_fetch_assoc($que)) 
        {
            //debugging
            //debugging
            //debugging
            //$res['final_design'] = '';
            //echo "<pre>";
            //print_r($res);
            //echo "</pre>";
            
            $final_design = $res['final_design'];
            $design_id = $res['peregrine_id'];
            $product = $res['product'];
            
            $data = explode(',', $final_design);
            $svg = base64_decode($data[1]);
        }
        
        echo $svg;
     ?>
    </body>
<script>
  /* $(function() 
   {
        $('#svg-pan-zoom-controls').remove();
        $('#glide').attr('width', '100%');
        $('#glide').attr('height', '575px');
        
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
        $('#svg-pan-zoom-controls').attr('transform', 'translate(1000 20)')
   })*/

function convert()
{   
    var id = "<?php echo $_GET['id']; ?>";
    var design_id = "<?php echo $design_id; ?>";
    var product =  "<?php echo $product; ?>";
    var generate =  "<?php echo $_SESSION['generate']; ?>";
    
    <?php if($product == 96){ ?>
    var uri = "<?php echo root();?>zip/Falkyn_order_"+id+"/final_design_"+id+".png";
    var product_line = "Falkyn";
    <?php } else { ?>
    var uri = "<?php echo root();?>zip/Glide_order_"+id+"/final_design_"+id+".png";
    var product_line = "Glide";
    <?php } ?>
    //saveSvgAsPng(document.getElementById("glide"), "final_design_"+id+".png");

     svgAsPngUri(document.getElementById("glide"), {}, function(uri){
         $.post('save_final_design.php', { 'data_image' : uri, 'id' : id, 'product_line' : product_line }, function(){
              //window.location.href = '?page=project&id='+id;
              window.location.href = '?page=render-product-traveler&id='+id+'&design_id='+design_id+'&product='+product+'&generate='+generate;
         })
     })

}
</script>
</html>