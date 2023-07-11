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
<?
if(!check_access('production')) exit();

function archive_batch_lot($id) {
	global $link;
	
	$q = mysqli_query($link, 'SELECT * FROM batch_lots WHERE `id`=\''.sf($id).'\'');

	$lot = mysqli_fetch_assoc($q);
	
	mysqli_query($link, 'INSERT INTO batch_lot_history (`name`, `inventory_id`, `type`, `material`, `color`, `lot_number`, `added`) VALUES ( \''.sf($lot['name']).'\', \''.sf($lot['inventory_id']).'\', \''.sf($lot['type']).'\', \''.sf($lot['material']).'\', \''.sf($lot['color']).'\', \''.sf($lot['lot_number']).'\', NOW() )');
		
}

if(check_access('admin')) {

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
		
	if($_GET['remove_batch_lot']) {
		
		archive_batch_lot($_GET['id']);
		
		mysqli_query($link, 'DELETE FROM batch_lots WHERE id=\''.sf($_GET['id']).'\'');
		
		echo '$("#batch_lot_'.$_GET['id'].'").remove();';
		
		
		exit();
	}

}

?>


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
				//$('.color_field').keyup(function(){
				//	check_color(this);
				//});
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
        
        
        //$(document).on('keyup', '.color_field', function() {
        //    var id = $(this).attr('id');
            //exists_color(id);
        //    check_color(id);
        //});
        
        //$(document).on('blur', '.color_field', function() {
        //    var id = $(this).attr('id');
            //exists_color(id);
        //    check_color(id);
        //});
        
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
                        $.notify("Sorry your color name not available in designer.\nTo see available list color in designer please visit Color Settings Page", 'error')
        			}
        			else
        			{
            			    $("#suggesstion-box_"+lot_id).show();
            			    $("#suggesstion-box_"+lot_id).html(data);
        			}
        		}
    		});
        }
        
        /*function exists_color(id){
            var lot_id = id.replace('color_','');
            $.ajax({
        		type: "POST",
        		url: "<?=root()?>exec/check_color/",  
        		data:{'keyword' : $("#"+id).val(), 'id' : id},
        		success: function(data)
        		{
        		   // if(data == ''){
                     //   $.notify("Sorry your color name not available in designer.\nTo see available list color in designer please visit Color Settings Page", 'error')
        			//}
        			if(data == '')
        		    {
                        $("#suggesstion-box_"+lot_id).hide();
                        $.notify("Sorry your color name not available in designer.\nTo see available list color in designer please visit Color Settings Page", 'error')
        			}
        			else
        			{
        			    $("#suggesstion-box_"+lot_id).show();
        			    $("#suggesstion-box_"+lot_id).html(data);
        			}
        		}
    		});
        }*/
        
        function selectColor(id, val) 
        {
            var lot_id = id.replace('color_','');
            
            $("#"+id).val(val);
            $("#check_"+id).val('1');
            $("#suggesstion-box_"+lot_id).hide();   
            $("#suggesstion-box_"+lot_id).html();
            
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
        			if(result != 0){
        			    $('#suggestion-box_'+lot_id).hide();
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

