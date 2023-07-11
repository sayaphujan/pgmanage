<?
if(!check_access('production')) exit();
?>
<div class="col-md-8">&nbsp;</div>
<div class="col-md-4" style="float:right;margin:0px">
    <div class="col-md-6" style="width:auto;padding:3px">
		<br />
		<strong>Serial</strong>
	</div>
	<div class="col-md-6" style="width:auto">
        <br />
       <input type="text" placeholder="search" id="livesearch" class="livesearch" <?=($_GET['sn']!='' ? 'value="'.$_GET['sn'].'"' : '')?>>
    </div>
</div>
<div class="clear"></div>
<br/>

<?
        $pq = mysqli_query($link, 'SELECT * FROM projects WHERE serial=\''.sf($_GET['sn']).'\'');

        $project = mysqli_fetch_assoc($pq);
        $project_meta = json_decode($project['metadata'], true);
        
        $pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($project['product']).'\'');
        $product = mysqli_fetch_assoc($pq);
        
        $cq = mysqli_query($link, 'SELECT * FROM customers WHERE id=\''.sf($project['customer']).'\'');
        $customer = mysqli_fetch_assoc($cq);
        
        //this part is kindof a hack, but it works
         $query = 'SELECT project_parts.name as product_part_name
                                , project_parts.batch_lot
                                , project_parts.variables as part_variables
                                , product_parts.batch_lot as capture_batch_lot 
                                FROM project_parts
                                LEFT JOIN product_parts ON project_parts.part = product_parts.id 
                                WHERE project_parts.project=\''.sf($project['id']).'\' 
                                ORDER BY project_parts.id ASC';
            $ssq = mysqli_query($link, $query);

    echo '
    <strong>'.$project['name'].'</strong>
    <div class="project_step_input">
                <div class="row parts-table parts-table-heading row-eq-height"  style="border:none">
					<div class="col-md-4 part-heading">Part Name</div>
					<div class="col-md-2 part-heading">Batch Lot</div>
					<div class="col-md-3 part-heading">Material</div>
					<div class="col-md-3 part-heading">Color</div>
				</div>';        
        while($part = mysqli_fetch_assoc($ssq))
        {
            $part_vars = json_decode($part['part_variables'], true);
            if(!empty($part['product_part_name']))
            {
	            ?>
				<div class="row parts-table parts-table-data" style="border:none;padding:0px;margin:0px">
					<div class="col-md-4"><?=$part['product_part_name']?></div>
					<div class="col-md-2 text-center">
						<?php 
                            if(is_array($part_vars))
                            {
                                $batch_lot = $part['batch_lot'];
                                $capture_batch_lot = $part['capture_batch_lot'];
                                $lot_number = get_next_batch_lot($part_vars);
                                
                                if(empty($batch_lot)) 
                                {
                                    //attempt to look up a valid batch lot #
                                    if(!empty($lot_number)) {
                                        $batch_lot_subtxt = '<div class="text-danger bold">Auto-populated</div>';
                                        echo $lot_number.'<br/>'.$batch_lot_subtxt.'<br/>';
                                    } else {
                                        $batch_lot_subtxt = '';
                                        echo 'NONE';
                                    }
                                }
                                else
                                {
                                    echo $lot_number;
                                }
                            /*
                                if(!empty($lot_number) && $part['completed'] == 1)
                                {
                                    echo $lot_number.'<br/>'.$batch_lot_subtxt.'<br/>';
                                } 
                                elseif(!empty($lot_number) && $part['completed'] == 0)
                                {
                                    $lot_number = get_next_batch_lot($part_vars);
                                    echo 'Waiting to be completed';
                                }
                                else 
                                {
                                    echo 'NONE';
                                }
                            */
                            }
                        ?>
					</div>
					<div class="col-md-3 col-xs-12 project_substep_parts">
    					<?
        					$i = 1;
        					
        					if(is_array($part_vars))
                            {
        						foreach($part_vars as $key=>$var) {
        							if(!empty($var['value'])) {
        								if($var['name'] == 'Material')
        								{
        								    echo '<div class="col-md-8 col-sm-6">'.$var['value'].'&nbsp;</div>';
        								}
        								$i++;
        							}
        						}
                            }
    					?>
					
					</div>
					<div class="col-md-3 col-xs-12 project_substep_parts">
    					<?
        					$i = 1;
        					
        					if(is_array($part_vars))
                            {
        						foreach($part_vars as $key=>$var) {
        							if(!empty($var['value'])) {
        								if($var['name'] == 'Color')
        								{
        								    echo '<div class="col-md-8 col-sm-6">'.$var['value'].'&nbsp;</div>';
        								}
        								$i++;
        							}
        						}
                            }
    					?>
					
					</div>
				</div>
		    	<?
            }
        }
echo '</div>';
?>
<script>
    var timer = null;
			$('#livesearch').keypress(function() {
			    clearTimeout(timer); 
                timer = setTimeout(doStuff, 200)
			});
			
			function doStuff()
			{
			    document.location='<?=root()?>?page=check_batch_lot&sn='+$('#livesearch').val();
			}
</script>