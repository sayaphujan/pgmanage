<style>
@media all {
  .watermark {
    display: none;
  }
  .sign{
      display: none;
  }
}

@media print {
  .pagebreak { 
    page-break-before: always; 
  }
  .no-print{
    display:none;
  }
  .no-padding{
    padding-top: 0px !important;
  }
  table td{
    padding: 0px;
  }
  .watermark {
        display: inline;
        position: fixed !important;
        opacity: 0.25;
        font-size: 3em;
        width: 100%;
        text-align: center;
        z-index: 1000;
        top:300px;
        right:5px;
    }
    .sign {
        display:inline;
    }
   * {
       -webkit-print-color-adjust: exact;
   }
   container.style.backgroundImage = 'url("<?=root();?>images/completed.png")';
}
</style>
<div class="project_heading" style="padding-top: 25px">
    <div class="col-md-1 text-right project-edit-btn no-print">
	
		<button class="btn" onclick="window.print()">Print this page</button>

	</div>
</div>
<br/>
<br/>
<br/>
<?

if(empty($_GET['cycle'])) {

	echo '<br />
	<h4>Please select a completed production cycle</h4><br />
	<br />
	 <div id="data_table">
    <table class="table table-striped table-bordered table-hover" id="datatable" width="100%" style="font-size: 12px">
        <thead>
            <tr>
	            <th align="center" style="width:5%">No</th>
	            <th align="center">Production Cycle</th>
	        </tr>
	   </thead>
	   <tbody>';
	//$q = mysqli_query($link, 'SELECT * FROM projects WHERE status=\'started\' AND (product=1 OR product>=89)');
	//$q = mysqli_query($link, 'SELECT * FROM projects WHERE status=\'started\' AND (product=1 OR product>= 82 AND product <=96)');
	$q = mysqli_query($link, 'SELECT * FROM projects WHERE status=\'completed\' AND (product=1 OR product>= 82)');
	
	$pods = array();
	$no=1;
	while($project = mysqli_fetch_assoc($q)) {
	    //print_r($project['pod'].'/'.$project['product']);
		preg_match("/PC([0-9]*)-([0-9]*)/", $project['pod'], $pod_output);
	    
		if(!in_array($pod_output[0], $pods) && !empty($pod_output[0])) {
			$pods[] = $pod_output[0];
			
			echo '<tr><td>'.$no++.'</td><td><a href="?page=print_report&cycle='.$pod_output[0].'">'.$pod_output[0].'</td></tr>';
			
			
		}
	}
	
	echo '</tbody></table>';
	
	
	
	
	
} else {

    if($_SESSION['type'] == 'inspector' || $_SESSION['type'] == 'admin' ){
        $readonly = '';
    }else{
        $readonly = 'disabled';
    }	

$pod = sf($_GET['cycle']);

//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

?>

<br />

<div class="tab-content">
    <h2 class="no-padding">HARNESS</h2>
	<div id="harness">
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>MLW Config</th>
				<th>Yoke Size</th>
				<th>MWL Size</th>
				<th>Offset</th>
				<th>Hardware Type</th>
				<th>Batch Lot #</th>
				<th>T7 Webbing Color</th>
				<th>Batch Lot #</th>
				<th>T8 Webbing Color</th>
				<th>Batch Lot #</th>
				<th>Tex 70 Thread Color</th>
				<th>Batch Lot #</th>
				<th>Tex 350 Thread Color</th>
				<th>Batch Lot #</th>
				<th>Chest Strap Size</th>
				<th>Legpad Size</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			  $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    if(mysqli_num_rows($que) > 0){
    			    while($res = mysqli_fetch_assoc($que)) 
        			{
        			    $inspector[] = $res;      
        			}
			    }
			while($c = mysqli_fetch_assoc($q)) {
				
				$vars = json_decode($c['global_vars'], true);
				
				if($vars[3]['vars']['cut_ready_1_harness'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_1_harness'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="harness" data-tab="1" name="inspector_1_harness-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_1_harness'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
    			    
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[1]['vars']['MLW Configuration'].'</td>
					<td>'.$vars[1]['vars']['Yoke Size'].'</td>
					<td>'.$vars[1]['vars']['MLW Size'].'</td>
					<td>'.$vars[1]['vars']['Offset'].'</td>
					<td>'.$vars[1]['vars']['Hardware Type'].'</td>
					<td align="center">'.$vars[3]['vars']['hw_batch_lot_1_harness'].'</td>
					<td>'.$vars[1]['vars']['Webbing Color'].'</td>
					<td align="center">'.$vars[3]['vars']['wb7_batch_lot_1_harness'].'</td>
					<td>'.$vars[1]['vars']['Webbing Color'].'</td>
					<td align="center">'.$vars[3]['vars']['wb8_batch_lot_1_harness'].'</td>
					<td>'.$vars[1]['vars']['Webbing Color'].'</td>
					<td align="center">'.$vars[3]['vars']['t7_batch_lot_1_harness'].'</td>
					<td>'.$vars[1]['vars']['Webbing Color'].'</td>
					<td align="center">'.$vars[3]['vars']['t8_batch_lot_1_harness'].'</td>
					<td>'.$vars[1]['vars']['Chest Strap Type'].'</td>
					<td>'.$vars[1]['vars']['Leg Pad Size'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_1_harness'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_1_harness'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_1_harness'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
		
	</div>
	<br/><br/>
	<div id="lateral">
		<h2 class="no-padding">LATERAL</h2>
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Lateral Type</th>
				<th>Lateral Size</th>
				<th>Lateral Webbing Color</th>
				<th>Tex 70 Thread Color</th>
				<th>Batch Lot #</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			  $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			
			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				
				if($vars[3]['vars']['cut_ready_2_lateral'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_2_lateral'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="lateral" data-tab="2" name="inspector_2_lateral-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_2_lateral'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
    				
    				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[1]['vars']['MLW Configuration'].'</td>
					<td>'.$vars[1]['vars']['Lateral Size'].'</td>
					<td>'.$vars[1]['vars']['Webbing Color'].'</td>
					<td>'.$vars[1]['vars']['Webbing Color'].'</td>
					<td align="center">'.$vars[3]['vars']['t70_batch_lot_2_lateral'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_2_lateral'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_2_lateral'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_2_lateral'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
	
	</div>
	<br/><br/>
	<div id="teamc">
	    <h2 class="no-padding">TEAM C</h2>
	    <?php
	    			    
    			//$q = mysqli_query($link, 'SELECT * 
    			  //                              FROM projects
    			    //                            WHERE pod LIKE \''.sf($pod).'%\' 
    			      //                              AND (product=1 OR product>=89) 
    			        //                            AND status!=\'deleted\'');
    			        
    			        //$q = mysqli_query($link, 'SELECT * 
    			         //                       FROM projects
    			           //                     WHERE pod LIKE \''.sf($pod).'%\' 
    			             //                       AND (product=1 OR product>= 82 AND product <=96) 
    			               //                     AND status!=\'deleted\'');
    			               
    			               $q = mysqli_query($link, 'SELECT * 
    			                                FROM projects
    			                                WHERE pod LIKE \''.sf($pod).'%\' 
    			                                    AND (product=1 OR product>= 82) 
    			                                    AND status=\'completed\'');

	            while($c = mysqli_fetch_assoc($q)) 
    			{
    				$vars = json_decode($c['global_vars'], true);
    				//echo "<pre>";
    				//print_r($vars);
    				//echo "</pre>";
    				/*
    				$vars[3]['vars']['cut_ready_3_main_risers'] 
                 = $vars[3]['vars']['production_complete_3_main_risers'] 
                 = $vars[3]['vars']['inspector_3_main_risers'] 
                 = $vars[3]['vars']['cut_ready_3_mpc'] 
                 = $vars[3]['vars']['production_complete_3_mpc'] 
                 = $vars[3]['vars']['inspector_3_mpc'] 
                 = $vars[3]['vars']['cut_ready_3_mpc_bridal'] 
                 = $vars[3]['vars']['production_complete_3_mpc_bridal'] 
                 = $vars[3]['vars']['inspector_3_mpc_bridal'] 
                 = $vars[3]['vars']['cut_ready_3_rpc'] 
                 = $vars[3]['vars']['production_complete_3_rpc'] 
                 = $vars[3]['vars']['inspector_3_rpc'] 
                 = $vars[3]['vars']['cut_ready_3_mdb'] 
                 = $vars[3]['vars']['production_complete_3_mdb'] 
                 = $vars[3]['vars']['inspector_3_mdb'] 
                 = $vars[3]['vars']['cut_ready_3_rf'] 
                 = $vars[3]['vars']['production_complete_3_rf'] 
                 = $vars[3]['vars']['inspector_3_rf'] 
                 = $vars[3]['vars']['cut_ready_3_rsl'] 
                 = $vars[3]['vars']['production_complete_3_rsl'] 
                 = $vars[3]['vars']['inspector_3_rsl'] 
                 = $vars[3]['vars']['cut_ready_3_air_snare'] 
                 = $vars[3]['vars']['production_complete_3_air_snare'] 
                 = $vars[3]['vars']['inspector_3_air_snare'] 
                 = $vars[3]['vars']['cut_ready_3_reserve_handle'] 
                 = $vars[3]['vars']['production_complete_3_reserve_handle'] 
                 = $vars[3]['vars']['inspector_3_reserve_handle'] 
                 = $vars[3]['vars']['cut_ready_3_mrh'] 
                 = $vars[3]['vars']['production_complete_3_mrh'] 
                 = $vars[3]['vars']['inspector_3_mrh'] 
                 = 1;*/
    			}
    			
    			if($vars[2]['vars']['Reserve Static Line Type']=='ACE') {
					$air_snare = 1;
				} else {
					$air_snare = 0;
				}
        	        if(
        	            $vars[3]['vars']['cut_ready_3_main_risers'] == 1
        	            && $vars[3]['vars']['production_complete_3_main_risers'] == 1
        	            && $vars[3]['vars']['inspector_3_main_risers'] != null
        	            && $vars[3]['vars']['cut_ready_3_mpc'] == 1
        	            && $vars[3]['vars']['production_complete_3_mpc'] == 1
        	            && $vars[3]['vars']['inspector_3_mpc'] != null
        	            && $vars[3]['vars']['cut_ready_3_mpc_bridal'] == 1
        	            && $vars[3]['vars']['production_complete_3_mpc_bridal'] == 1
        	            && $vars[3]['vars']['inspector_3_mpc_bridal'] != null
        	            && $vars[3]['vars']['cut_ready_3_rpc'] == 1
        	            && $vars[3]['vars']['production_complete_3_rpc'] == 1
        	            && $vars[3]['vars']['inspector_3_rpc'] != null
        	            && $vars[3]['vars']['cut_ready_3_mdb'] == 1
        	            && $vars[3]['vars']['production_complete_3_mdb'] == 1
        	            && $vars[3]['vars']['inspector_3_mdb'] != null
        	            && $vars[3]['vars']['cut_ready_3_rf'] == 1
        	            && $vars[3]['vars']['production_complete_3_rf'] == 1
        	            && $vars[3]['vars']['inspector_3_rf'] != null
        	            && $vars[3]['vars']['cut_ready_3_rsl'] == 1
        	            && $vars[3]['vars']['production_complete_3_rsl'] == 1
        	            && $vars[3]['vars']['inspector_3_rsl'] != null
        	            && $vars[3]['vars']['cut_ready_3_air_snare'] == $air_snare
        	            && $vars[3]['vars']['production_complete_3_air_snare'] == $air_snare
        	            && $vars[3]['vars']['inspector_3_air_snare'] != null
        	            && $vars[3]['vars']['cut_ready_3_reserve_handle'] == 1
        	            && $vars[3]['vars']['production_complete_3_reserve_handle'] == 1
        	            && $vars[3]['vars']['inspector_3_reserve_handle'] != null
        	            && $vars[3]['vars']['cut_ready_3_mrh'] == 1
        	            && $vars[3]['vars']['production_complete_3_mrh'] == 1
        	            && $vars[3]['vars']['inspector_3_mrh'] != null
        	            ){
        	             echo '&nbsp;<button class="btn btn-success pull-right right_btn no-print" onclick="window.print();" id="mark_as_complete" style="margin-top:-2px;">TEAM C COMPLETE</button>';
		                 echo '<hr>';
        	            }
        	           ?>
		
		<img src="<?=root();?>images/completed.png" style="position:relative;" class="print watermark">
			<h3 class="no-padding">Main Risers</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Main Riser Type</th>
				<th>Hardware Type</th>
				<th>Main Riser Color</th>
				<th>Main Riser Length</th>
				<th>Comfy Div Loop</th>
				<th>Speed Risers</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			    
			    $inspector = array();
			    $comfy = array();
			    $speed = array();
			    $q = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($c = mysqli_fetch_assoc($q)) 
    			{
    			    $inspector[] = $c;      
    			}
			    
    			//$q = mysqli_query($link, 'SELECT * 
    			                                //FROM projects
    			                                //WHERE pod LIKE \''.sf($pod).'%\' 
    			                                    //AND (product=1 OR product>=89) 
    			                                    //AND status!=\'deleted\'');
    			                                    
    			                         //$q = mysqli_query($link, 'SELECT * 
    			                         //       FROM projects
    			                         //      WHERE pod LIKE \''.sf($pod).'%\' 
    			                         //           AND (product=1 OR product>= 82 AND product <=96) 
    			                         //          AND status!=\'deleted\'');
    			                         
    			                         $q = mysqli_query($link, 'SELECT * 
    			                                FROM projects
    			                               WHERE pod LIKE \''.sf($pod).'%\' 
    			                                    AND (product=1 OR product>= 82) 
    			                                   AND status=\'completed\'');
    			while($c = mysqli_fetch_assoc($q)) 
    			{
    				$vars = json_decode($c['global_vars'], true);
    				
    				//echo "<pre>";
    				//print_r($vars);
    				//echo "</pre>";
    				
    				if($vars[3]['vars']['cut_ready_3_main_risers'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_main_risers'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="main_risers" data-tab="3" name="inspector_3_main_risers-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_main_risers'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
    			    
    			    echo '<tr>
    					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
    					<td>'.$vars[2]['vars']['Main Riser Type'].'</td>
    					<td>'.$vars[1]['vars']['Hardware Type'].'</td>
    					<td>'.$vars[2]['vars']['Main Riser Color'].'</td>
    					<td>'.$vars[2]['vars']['Main Riser Length'].'</td>
    					<td>'.$vars[2]['vars']['Comfy Dive Loops'].'</td>
    					<td>'.$vars[2]['vars']['Low Drag Risers'].'</td>
    					<td align="center">'.$vars[3]['vars']['cut_ready_3_main_risers'].'</td>
    					<td align="center">'.$vars[3]['vars']['production_complete_3_main_risers'].'</td>
    					<td align="center">'.$vars[3]['vars']['inspector_3_main_risers'].'</td>
    				</tr>';
    			}
			?>
		</table>
		
		<br class="no-print"/>
		<br />
		
		<hr>
			<h3 class="no-padding">Main Pilot Chute</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Main PC Type</th>
				<th>Main PC Handle Type</th>
				<th>Mesh Color</th>
				<th>Main PC Size</th>
				<th>Main PC Handle Color 1</th>
				<th>Main PC Handle Color 2</th>
				<th>Main PC Handle Color 3</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
//			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\' ORDER BY serial ASC');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\' ORDER BY serial ASC');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\' ORDER BY serial ASC');

			$pc_size = array();
			$pc_size['36-42']='28';
			$pc_size['36-44']='28';
			$pc_size['36-45']='28';
			$pc_size['36-52']='28';
			$pc_size['39-55']='28';
			$pc_size['40-62']='28';
			$pc_size['40-67']='31';
			$pc_size['40-67']='31';
			$pc_size['48-64']='31';
			$pc_size['48-73']='31';
			$pc_size['49-73']='31';
			$pc_size['50-82']='33';
			$pc_size['50-90']='33';
			$pc_size['64-10.8']='33';
			$pc_size['65-11.5']='35';
			$pc_size['68.12.2']='35';
			
			while($c = mysqli_fetch_assoc($q)) {
			    $vars = json_decode($c['global_vars'], true);

			    	if($vars[3]['vars']['cut_ready_3_mpc'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_mpc'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="mpc" data-tab="3" name="inspector_3_mpc-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_mpc'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
					
				if($c['product']==70) {
				
					//$vars = json_decode($c['global_vars'], true);
					echo '<tr>
						<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
				  		<td>'.$vars[2]['vars']['Main Pilot Chute Type'].'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Type'].'</td>
						<td>'.($vars[1]['vars']['Hardware Type']=='SS' ? 'White' : 'Black').'</td>
						<td>'.(!empty($vars[2]['vars']['Main PC Size']) ? $vars[2]['vars']['Main PC Size']:$pc_size[$vars[1]['vars']['Container Size']]).'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Color'].'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Color 2'].'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Color 3'].'</td>
						<td align="center">'.$vars[3]['vars']['cut_ready_3_mpc'].'</td>
    					<td align="center">'.$vars[3]['vars']['production_complete_3_mpc'].'</td>
    					<td align="center">'.$vars[3]['vars']['inspector_3_mpc'].'</td>
					</tr>';

				
				} else {
				
					//$vars = json_decode($c['global_vars'], true);
					
					echo '<tr>
						<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
						<td>'.$vars[2]['vars']['Main Pilot Chute Type'].'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Type'].'</td>
						<td>'.($vars[1]['vars']['Hardware Type']=='SS' ? 'White' : 'Black').'</td>
						<td>'.$vars[2]['vars']['Main PC Size'].'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Color'].'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Color 2'].'</td>
						<td>'.$vars[2]['vars']['Main PC Handle Color 3'].'</td>
						<td align="center">'.$vars[3]['vars']['cut_ready_3_mpc'].'</td>
    					<td align="center">'.$vars[3]['vars']['production_complete_3_mpc'].'</td>
    					<td align="center">'.$vars[3]['vars']['inspector_3_mpc'].'</td>
					</tr>';
				
				}
			}
					
			
			?>
		</table>
		
		<br class="no-print"/>
		<br />
		
		
		
		<hr>
			<h3 class="no-padding">Main Pilot Chute Bridal</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Main PC Bridal Type</th>
				<th>Main PC Bridal Length</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			
			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				if($vars[3]['vars']['cut_ready_3_mpc_bridal'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_mpc_bridal'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="mpc_bridal" data-tab="3" name="inspector_3_mpc_bridal-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_mpc_bridal'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[2]['vars']['Main Pilot Chute Type'].'</td>
					<td>'.$vars[2]['vars']['Bridle Length'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_3_mpc_bridal'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_3_mpc_bridal'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_3_mpc_bridal'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
		
		<br class="no-print"/>
		<br />
		<hr>
			<h3 class="pagebreak no-padding">Reserve Pilot Chute</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Reserve PC Cap Fabric Type</th>
				<th>Reserve PC Cap Fabric Color</th>
				<th>Binding Tape Color</th>
				<th>Thread Color</th>
				<th>Pinstripe Color</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Batch Lot #</th>
				<th align="center">Serial #</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			
			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				
				    if($vars[3]['vars']['cut_ready_3_rpc'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_rpc'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="rpc" data-tab="3" name="inspector_3_rpc-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_rpc'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
				
				
				$parts = mysqli_query($link, 'SELECT * FROM project_parts WHERE project=\''.$c['id'].'\'');
				
				while($part = mysqli_fetch_assoc($parts)) {
					$part_vars = json_decode($part['variables'], true);
					if($part['name']=='BINDING TAPE COLOR 1') $binding_tape_color = $part_vars[1]['value'];
					if($part['name']=='BINDING TAPE COLOR 1') $binding_tape_color = $part_vars[1]['value'];
					if($part['name']=='10 RESERVE CONTAINER') $fabric_type = $part_vars[0]['value'];
				}
				
				$vars[1]['vars']['Fabric Type CB'] = (!empty($vars[1]['vars']['Fabric Type CB'])) ? $vars[1]['vars']['Fabric Type CB'] : $fabric_type;
				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[1]['vars']['Fabric Type CB'].'</td>
					<td>'.$vars[2]['vars']['Reserve Cap Color'].'</td>
					<td>'.$binding_tape_color.'</td>
					<td>'.$vars[1]['vars']['Thread Color'].'</td>
					<td>'.$vars[1]['vars']['Pinstripe Color'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_3_rpc'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_3_rpc'].'</td>
    				<td align="center">'.$vars[3]['vars']['rpc_batch_lot_3_rpc'].'</td>
    				<td align="center">'.$vars[3]['vars']['rpc_serial_3_rpc'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_3_rpc'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
		
		<br class="no-print" />
		<br />
		
		
		<hr>
			<h3 class="no-padding">Main Deployment Bag</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Main Deployment Bag Type</th>
				<th>Main Deployment Bag Size</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			
			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				if($vars[3]['vars']['cut_ready_3_mdb'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_mdb'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="mdb" data-tab="3" name="inspector_3_mdb-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_mdb'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[2]['vars']['Main Deployment Bag Type'].'</td>
					<td>'.$vars[1]['vars']['Container Size'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_3_mdb'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_3_mdb'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_3_mdb'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
		
		<br class="no-print" />
		<br />
		
		
		<hr>
			<h3 class="no-padding">Reserve Freebag</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Freebag Size</th>
				<th>Reserve Static line Type</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Batch Lot #</th>
				<th align="center">Serial Number #</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			
			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				if($vars[3]['vars']['cut_ready_3_rf'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_rf'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				    
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="rf" data-tab="3" name="inspector_3_rf-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_rf'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[1]['vars']['Container Size'].'</td>
					<td>'.$vars[2]['vars']['Reserve Static Line Type'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_3_rf'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_3_rf'].'</td>
    				<td align="center">'.$vars[3]['vars']['rf_batch_lot_3_rf'].'</td>
    				<td align="center">'.$vars[3]['vars']['rf_serial_3_rf'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_1_harness'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
		
		<br class="no-print" />
		<br />
		
		<hr>
			<h3 class="no-padding">Reserve Static Line Configuration</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Reserve Static Line Type</th>
				<th>Hardware Type</th>
				<th>Yoke Size</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			
			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				
				if($vars[3]['vars']['cut_ready_3_rsl'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_rsl'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="rsl" data-tab="3" name="inspector_3_rsl-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_rsl'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[2]['vars']['Reserve Static Line Type'].'</td>
					<td>'.$vars[1]['vars']['Hardware Type'].'</td>
					<td>'.$vars[1]['vars']['Yoke Size'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_3_rsl'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_3_rsl'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_1_harness'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
		
		<br class="no-print" />
		<br />
		<hr>
			<h3 class="pagebreak no-padding">Air Snare</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Air Snare</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			$i = 0;
			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				
				if($vars[2]['vars']['Reserve Static Line Type']=='ACE') {
					$air_snare = 'Yes';
					$i++;
				} else {
					$air_snare = 'No';
				}
				
				if($air_snare == 'No'){
				    $read_only = "readonly";
				    $vars[3]['vars']['cut_ready_3_air_snare'] = 0;
				    $vars[3]['vars']['production_complete_3_air_snare'] = 0;
				}
					if($vars[3]['vars']['cut_ready_3_air_snare'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_air_snare'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="air_snare" data-tab="3" name="inspector_3_air_snare-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_air_snare'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$air_snare.'</td>
					';
				echo '<td align="center">';
					if($air_snare != 'No'){
    					echo $vars[3]['vars']['cut_ready_3_air_snare'];
					}
				echo '</td>
        			  <td align="center">';
        			if($air_snare != 'No'){
        			  echo $vars[3]['vars']['production_complete_3_air_snare'];
    				}
    			echo '</td>';
    			echo '
    				<td align="center">'.$vars[3]['vars']['inspector_3_air_snare'].'</td>
					
				</tr>';
			}
					
			
			?>
		</table>
		<br />
		<strong>Total ACE RSLs: <?=$i?></strong>
		
		<br  class="no-print"/>
		<br />
		
		
		<hr>
			<h3 class="no-padding">Reserve Handle</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Reserve Deployment Handle Type</th>
				<th>Fit Right Reserve Handle Color</th>
				<th>Yoke Size</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Batch Lot #</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status=\'completed\'');

			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				
				if($vars[3]['vars']['cut_ready_3_reserve_handle'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_reserve_handle'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				    
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="reserve_handle" data-tab="3" name="inspector_3_reserve_handle-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_reserve_handle'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
    			    
    			 
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[2]['vars']['Reserve Deployment handle type'].'</td>
					<td>'.$vars[2]['vars']['Fit Right Reserve Handle Color'].'</td>
					<td>'.$vars[1]['vars']['Yoke Size'].'</td>
					<td align="center">'.$vars[3]['vars']['cut_ready_3_reserve_handle'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_3_reserve_handle'].'</td>
    				<td align="center">'.$vars[3]['vars']['rh_batch_lot_3_reserve_handle'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_3_reserve_handle'].'</td>
					
				</tr>';
			}
					
			
			?>
		</table>
		
		<br  class="no-print"/>
		<br />
		
		
		<hr>
			<h3 class="no-padding">Main Release Handle</h3>
		</hr>
		
		<table class="table">
			<tr>
				<th>Serial</th>
				<th>Release Handle Color</th>
				<th>Embroidery</th>
				<th align="center">Cut/Ready</th>
				<th align="center">Production Complete</th>
				<th align="center">Inspected</th>
			</tr>
			<?
			
			    $inspector = array();
			    $que = mysqli_query($link, "SELECT * FROM inspectors WHERE active = '1' AND id!='2'");
			    while($res = mysqli_fetch_assoc($que)) 
    			{
    			    $inspector[] = $res;      
    			}
    			
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>=89) AND status!=\'deleted\'');
			//$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82 AND product <=96) AND status!=\'deleted\'');
			$q = mysqli_query($link, 'SELECT * FROM projects WHERE pod LIKE \''.sf($pod).'%\' AND (product=1 OR product>= 82) AND status!=\'completed\'');

			while($c = mysqli_fetch_assoc($q)) {
			
				$vars = json_decode($c['global_vars'], true);
				
				if($vars[3]['vars']['cut_ready_3_mrh'] == 1)
    				    $cut_ready = 'checked';
    				else
    				    $cut_ready = '';
    				    
    				if($vars[3]['vars']['production_complete_3_mrh'] == 1)
    				    $production_complete = 'checked';
    				else
    				    $production_complete = '';
    				
    				$inspector_option = '<select class="field_update" data-id="'.$c['id'].'" data-var="mrh" data-tab="3" name="inspector_3_mrh-'.$c['id'].'" '.$readonly.'>
    				                        <option value="0">Select Inspector</option>';
    				
    				foreach($inspector as $row)
    			    {
    			        $selected = '';
    			        if($row['id'] == $vars[3]['vars']['inspector_3_mrh'])
    			            $selected = 'selected';
    			            
    			        $inspector_option .= '<option '.$selected.' value="'.$row['id'].'">'.$row['initial'].' - '.$row['stamp_number'].'</option>';
    			    }
    			    $inspector_option .= '</select>';
				
				echo '<tr>
					<td><a href="'.root().'?page=project&id='.$c['id'].'">'.$c['serial'].'</td>
					<td>'.$vars[2]['vars']['Release Handle Color'].'</td>
					<td></td>
					<td align="center">'.$vars[3]['vars']['cut_ready_3_mrh'].'</td>
    				<td align="center">'.$vars[3]['vars']['production_complete_3_mrh'].'</td>
    				<td align="center">'.$vars[3]['vars']['inspector_1_harness'].'</td>
				</tr>';
			}
					
			
			?>
		</table>
		
		<br  class="no-print"/>
		<br />
		
		<div class="sign print">
		    <table width="25%">
		        <tr>
		          <td><b>Peregrine</b></td>
		        </tr>
		        <tr>
		          <td>
		              <br/>
		              <br/>
		              <br/>
		              <br/>
		              <hr/>
		          </td>
		        </tr>
		        <tr>
		            <td>Signature<hr/></td>
		        </tr>
		        <tr>
		            <td>Date</td>
		        </tr>
		    </table>
		</div>
		
		
	
	</div>
	
</div>

<?
}
?>
<script>
    $(document).ready(function()
    {
           //$('#datatable').DataTable({
            //    "pageLength": 25,
            //    "ordering": true,
           //});
        $('.field_update').change(function()
        {
            var project_id = $(this).data('id');
            var field = $(this).attr('name');
            var value = $(this).val();
            
            /*if($(this).attr('type') == 'checkbox')
            {
                $('input[type="checkbox"][name="'+field+'"]').change(function() {
                    if($(this).prop('checked')){
                      var value="0";
                    } else{
                      var value="1";
                    }
                });
                alert("field ="+field+"  value ="+value);
                //var value = $("input[type=checkbox][name="+field+"]:checked").val();
                //if (typeof value === 'undefined') {
                //   var value = 0;
                //}
            }*/
            //alert("field ="+field+"  value ="+value);
            update_value = {'id' : project_id}
            update_value[field] = value
            
            $.post('<?php echo root("do/update_project_ajax/"); ?>', update_value, function(result){
                if(result){
                    $.notify('Project have been succesfully updated!', 'success')
                }
                else{
                    $.notify('Failed to update project!', 'error')
                }
            })
        })
        
    var timer = null;
    $('.field-update').keyup(function() {
        clearTimeout(timer);
        var $this = $(this);
    
        timer = setTimeout(function() {
            var project_id = $this.data('id');
            //var vars = $this.data('var');
            //var tab = $this.data('tab');
            var field = $this.attr('name');
            var value = $this.val();
            
            update_value = {'id' : project_id}
            update_value[field] = value
            
            if ($(this).val() != '') {
                $.post('<?php echo root("do/update_project_ajax/"); ?>', update_value, function(result){
                    if(result){
                        $.notify('Project have been succesfully updated!', 'success')
                    }
                    else{
                        $.notify('Failed to update project!', 'error')
                    }
                })
            }
        }, 1000);
    });

    });
    
</script>