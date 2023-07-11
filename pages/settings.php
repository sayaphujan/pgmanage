<?
if(!check_access('production')) exit();

function archive_batch_lot($id) {
	global $link;
	
	$q = mysqli_query($link, 'SELECT * FROM batch_lots WHERE `id`=\''.sf($id).'\'');

	$lot = mysqli_fetch_assoc($q);
	
	mysqli_query($link, 'INSERT INTO batch_lot_history (`name`, `inventory_id`, `type`, `material`, `color`, `lot_number`, `added`) VALUES ( \''.sf($lot['name']).'\', \''.sf($lot['inventory_id']).'\', \''.sf($lot['type']).'\', \''.sf($lot['material']).'\', \''.sf($lot['color']).'\', \''.sf($lot['lot_number']).'\', NOW() )');
		
}

if(check_access('admin')) {

	if($_GET['add_batch_lot']) {
		mysqli_query($link, 'INSERT INTO batch_lots (`added`) VALUES ( NOW() )');
		
		$id = mysqli_insert_id($link);
		
		echo '
					<tr id="batch_lot_'.$id.'">
						<form id="batch_lot_form_'.$id.'">
							<td>
								<input lot-id="'.$id.'" type="text" name="inventory_id" id="inventory_id_'.$id.'" placeholder="QB ID #" value="" class="form-control input-md batch-lot-input" required="" data-id="'.$id.'" onblur="save_batch_lot(\''.$id.'\')">
							</td>
							<td>
								<input lot-id="'.$id.'" type="text" name="name" id="name_'.$id.'" placeholder="name" value="" class="form-control input-md batch-lot-input" type="text" required="" data-id="'.$id.'" onblur="save_batch_lot(\''.$id.'\')">
							</td>
							<td>
								<input lot-id="'.$id.'" type="text" name="type" id="type_'.$id.'" placeholder="type" value="" class="form-control input-md batch-lot-input" type="text" required="" data-id="'.$id.'" onblur="save_batch_lot(\''.$id.'\')">
							</td>
							<td>
								<input lot-id="'.$id.'" type="text" name="material" id="material_'.$id.'" placeholder="material" value="" class="form-control input-md batch-lot-input" type="text" required="" data-id="'.$id.'" onblur="save_batch_lot(\''.$id.'\')">
							</td>
							<td>
								<input lot-id="'.$id.'" type="text" name="color" id="color_'.$id.'" placeholder="color" value="" class="color_field form-control input-md batch-lot-input" type="text" required="" data-id="'.$id.'" onblur="save_batch_lot(\''.$id.'\')">
								<div class="suggesstion-box" id="suggesstion-box_'.$id.'"></div>
							</td>
							<td>
								<input lot-id="'.$id.'" type="text" name="lot_number" id="lot_number_'.$id.'" placeholder="Lot Number" value="" class="form-control input-md batch-lot-input"  type="text" required="" data-id="'.$id.'" onblur="save_batch_lot(\''.$id.'\')">
							</td>
							<td>
								<button class="btn btn-danger" onclick="remove_batch_lot('.$id.')">Remove</button>
							</td>
						</form>
					</tr>'; 
		
		exit();
	}

	if($_GET['remove_batch_lot']) {
		
		archive_batch_lot($_GET['id']);
		
		mysqli_query($link, 'DELETE FROM batch_lots WHERE id=\''.sf($_GET['id']).'\'');
		
		echo '$("#batch_lot_'.$_GET['id'].'").remove();';
		
		
		exit();
	}

}

?>
<style>
ul li  a {
    display:block;
}

ul li a:hover, ul li a:focus {
    background-color: yellow;
}
.suggesstion-box{
    border: 1px solid black;    
}
.suggesstion-box ul{
    padding: 3px;
}
.suggesstion-box ul li{
    cursor: pointer;
}
</style>
<h2 class="form-signin-heading">Materials</h2>

<ul class="nav nav-tabs">

	<li class="active"><a data-toggle="tab" href="#main_tab">Batch Lot Tracking</a></li>

	
</ul>
	
<div class="tab-content">
	<div id="main_tab" class="tab-pane fade in active">
	<br />
	<br />
	<div class="row ledgend">
		<div class="col-md-4">
			Batch Lot Tracking
		</div>
		
		<div class="col-md-8">
			<input type="text" placeholder="search" id="livesearch" class="livesearch">
			
			<script>

				$(function() {
						
						var xhr = 'null';
						
						$('#livesearch').keypress(function() {
						
							xhr = $.ajax({
								url: '<?=root()?>exec/settings/?search='+$('#livesearch').val(),
								type: 'GET',
								beforeSend : function() {
										if(xhr != 'null' && xhr.readyState < 4) {
											console.log('aborted');
											xhr.abort();
										}
								},
								success: function(result) {
										$('#data_table').html($(result).find('#data_table').html());
										
										$('.batch-lot-input').blur(function(){
											save_batch_lot(this);
										});
								},
								error: function(xhr, ajaxOptions, thrownError) {
										if(thrownError == 'abort' || thrownError == 'undefined') return;
										console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							  });
						
						
							
							//$('#data_table').load('<?=root()?>exec/settings/?search='+$('#livesearch').val()+' #data_table');
						
						});
						
				});
				
			</script>
		</div>
		
	</div>
	<a href="<?=root();?>?page=add_batch_lot"><button class="btn btn-success" >Add</button></a>
	<div id="data_table">
		
		<table class="table" id="batch_lot_table">
		
			<thead>
				<tr>
					<th>QB Part</th><th>Name</th><th>Type</th><th>Material</th><th>Color</th><th>Lot Number</th><th></th>
				</tr>
			</div>
			<tbody>
				<?
				
				if($_GET['search']) {
					$search_sql = " AND (batch_lots.name LIKE '%".sf($_GET['search'])."%' OR batch_lots.inventory_id LIKE '%".sf($_GET['search'])."%' OR batch_lots.lot_number LIKE '%".sf($_GET['search'])."%' OR batch_lots.type LIKE '%".sf($_GET['search'])."%' OR batch_lots.material LIKE '%".sf($_GET['search'])."%' OR batch_lots.color LIKE '%".sf($_GET['search'])."%')";
				}
				
				
				$q = mysqli_query($link, 'SELECT * FROM batch_lots WHERE archived=0 '.$search_sql.' ORDER BY name ASC'.(!isset($_GET['search']) ? ' LIMIT 0,50' : ''));
				
				$color_options = array();
				
				while($lot = mysqli_fetch_assoc($q)) {
				
					if(check_access('admin')) {
					    
					    
						echo '
						<form id="batch_lot_form_'.$lot['id'].'">
						<tr id="batch_lot_'.$lot['id'].'">
							
								<td>
									<input type="text" name="inventory_id" id="inventory_id_'.$lot['id'].'" placeholder="QB ID #" value="'.$lot['inventory_id'].'" class="form-control input-md batch-lot-input" required="" onblur="save_batch_lot(\''.$lot['id'].'\')">
								</td>
								<td>
									<input type="text" name="name" id="name_'.$lot['id'].'" placeholder="name" value="'.$lot['name'].'" class="form-control input-md batch-lot-input" required="" onblur="save_batch_lot(\''.$lot['id'].'\')">
								</td>
								<td>
									<input type="text" name="type" id="type_'.$lot['id'].'" placeholder="type" value="'.$lot['type'].'" class="form-control input-md batch-lot-input" required="" onblur="save_batch_lot(\''.$lot['id'].'\')">
								</td>
								<td>
									<input type="text" name="material" id="material_'.$lot['id'].'" placeholder="Material" value="'.$lot['material'].'" class="form-control input-md batch-lot-input" required="" onblur="save_batch_lot(\''.$lot['id'].'\')">
								</td>
								<td>
							        <input type="text" name="color" id="color_'.$lot['id'].'" placeholder="color" value="'.$lot['color'].'" class="color_field form-control input-md batch-lot-input" required="" >
							        <div class="suggesstion-box" id="suggesstion-box_'.$lot['id'].'"></div>
								</td>
								<td>
									<input type="text" name="lot_number" id="lot_number_'.$lot['id'].'" placeholder="Lot Number" value="'.$lot['lot_number'].'" class="form-control input-md batch-lot-input" required="" onblur="save_batch_lot(\''.$lot['id'].'\')">
								</td>
								<td>
									<button class="btn btn-danger" onclick="remove_batch_lot('.$lot['id'].')">Remove</button>
								</td>
							
						</tr>
						</form>';
						
					} else {
					
						echo '
						<form id="batch_lot_form_'.$lot['id'].'">
						<tr id="batch_lot_'.$lot['id'].'">
							
								<td>
									'.$lot['inventory_id'].'
								</td>
								<td>
									'.$lot['name'].'
								</td>
								<td>
									'.$lot['type'].'
								</td>
								<td>
									'.$lot['material'].'
								</td>
								<td>
									'.$lot['color'].'
								</td>
								<td>
									'.$lot['lot_number'].'
								</td>
								<td>
									
								</td>
							
						</tr>
						</form>';
					
					}
				}
				
				
				if(!isset($_GET['search'])) {
				
					echo '<tr><td colspan="7" class="text-center"><button class="btn btn-success" onclick="show_all()">Show All</button></td></tr>';
				
				}
				?>
			
			</tbody>
		</table>
	</div>
	
	<script>
	
	    $(".suggesstion-box").hide();
	    
		
		function add_batch_lot() 
		{	
			$.get( "exec/settings/?add_batch_lot=true", function( data ) 
			{
				$('#batch_lot_table tbody').append(data);
				$('.batch-lot-input').blur(function(){
					save_batch_lot(this);
				});
				$('.color_field').keyup(function(){
					check_color(this);
				});
			});
		
		}
		
		function remove_batch_lot(id) {
			$.get( "exec/settings/?remove_batch_lot=true&id="+id , null, null, 'script');
		}
		
        var timer = null;
        $(document).on('keyup', '.color_field', function() {
            clearTimeout(timer); 
            var id = $(this).attr('id');
            timer = setTimeout(check_color(id), 1000)
        });
        
        $(document).on('blur', '.color_field', function() {
            var id = $(this).attr('id');
            exists_color(id);
        });
        
        function check_color(id){
            var lot_id = id.replace('color_','');
            $.ajax({
        		type: "POST",
        		url: "<?=root()?>exec/check_color/",  
        		data:{'keyword' : $("#"+id).val(), 'id' : id},
        		success: function(data)
        		{
        		    if(data == '')
        		    {
                        $("#suggesstion-box_"+lot_id).hide();
        			}
        			else
        			{
        			    $("#suggesstion-box_"+lot_id).show();
        			    $("#suggesstion-box_"+lot_id).html(data);
        			}
        		}
    		});
        }
        
        function exists_color(id){
            var lot_id = id.replace('color_','');
            $.ajax({
        		type: "POST",
        		url: "<?=root()?>exec/check_color/",  
        		data:{'keyword' : $("#"+id).val(), 'id' : id},
        		success: function(data)
        		{
        		    if(data == ''){
                        $.notify("Your color name IS NOT available in the designer.\nTo see available list color in designer please visit Color Settings Page", 'error')
        			}
        		}
    		});
        }
        
        function selectColor(id, val) 
        {
            var lot_id = id.replace('color_','');
            
            $("#"+id).val(val);
            $("#suggesstion-box_"+lot_id).hide();    
            
            save_batch_lot(lot_id);
        }
        
		function save_batch_lot(id) 
		{
		    console.log(id);
		    var lot_id = id;
			var inventory_id = $('#inventory_id_'+lot_id).val();
			var name = $('#name_'+lot_id).val();
			var type = $('#type_'+lot_id).val();
			var material = $('#material_'+lot_id).val();
			var color = $('#color_'+lot_id).val();
			var lot_number = $('#lot_number_'+lot_id).val();
			
            $.ajax({
        		type: "POST",
        		url: "<?=root()?>exec/save_batch_lot/",  
        		data: { id: lot_id, inventory_id: inventory_id, name: name, type: type, material: material, color: color, lot_number: lot_number },
        		success: function(result)
        		{
        			//console.log(result);
        			if(result == 0){
                        //$.notify("Sorry your color name not available in designer.\nTo see available list color in designer please visit Color Settings Page", 'error')
        			}
        		}
    		});

		}
		
		function show_all() {
			$('#data_table').load('<?=root()?>exec/settings/?search= #data_table');
		}
	
		$(function() {
			/*$('.batch-lot-input').blur(function(){
                save_batch_lot($(this).data("id"));
            });*/
		});
		
		
		
		
	
	</script>
	
	
</div>

