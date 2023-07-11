<?php
if(!check_access('production')) exit();

$pq = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');
$project = mysqli_fetch_assoc($pq);

if($project['product'] == 96)
{
    $output_forder = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Falkyn_order_';
}
else
{
    $output_forder = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Glide_order_';
}

?>
<script src="/peregrinemanage/js/svg-pan-zoom.js"></script>
<style>
    .container{
        width: 100%;
    }
</style>
<div style="width: 100%; overflow: hidden; margin-top: 20px">
    <div class="row common-design-container">
        <div class="glide-box" id="html" contenteditable="">
            <?php echo file_get_contents($output_forder.$project['id'].'/final_design_'.$project['id'].'.svg'); ?>
        </div>
    </div>
</div>

<script>
   $(function() 
   {
        $('#svg-pan-zoom-controls').remove()
        $('#glide').attr('width', '100%');
        $('#glide').attr('height', '575px');
        
        /*panZoomInstance = svgPanZoom('#glide', {
              zoomEnabled: true,
              controlIconsEnabled: true,
              mouseWheelZoomEnabled: false,
              minZoom: 2.3
              zoomScaleSensitivity: 0.07,
              onZoom: function(){},
              onPan: function(){},
        });
      
        //zoom out
        panZoomInstance.zoom(2.3);
        panZoomInstance.pan({x: 0, y:0})
        $('#svg-pan-zoom-controls').attr('transform', 'translate(1250 20)')*/
        
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
        $('#svg-pan-zoom-controls').attr('transform', 'translate(1100 20)')
   })
</script>