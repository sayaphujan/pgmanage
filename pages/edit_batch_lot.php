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

$id = $_GET['id'];

if(!check_access('production')) exit();

    if(check_access('admin')) {

		$q = mysqli_query($link, 'SELECT * FROM batch_lots WHERE `id`=\''.sf($id).'\'');

	    $data = mysqli_fetch_assoc($q);
	
    }
?>
<h1>Edit Batch Lot #<?=$id?></h1>
<br>
<form id="batch_lot_form">
        <input lot-id="<?=$id;?>" type="hidden" name="id" id="id_<?=$id;?>" class="form-control input-md batch-lot-input"  value="<?=$id;?>" data-id="<?=$id;?>">
        <strong>QB ID#</strong>
        <input lot-id="<?=$id;?>" type="text" name="inventory_id" id="inventory_id_<?=$id;?>" placeholder="QB ID #" value="<?=$data['inventory_id'];?>" class="form-control input-md batch-lot-input"  data-id="<?=$id;?>">
        <br/>
        
        <strong>Name</strong>
        <input lot-id="<?=$id;?>" type="text" name="name" id="name_<?=$id;?>" placeholder="name" value="<?=$data['name'];?>" class="form-control input-md batch-lot-input" type="text"  data-id="<?=$id;?>">
        <br/>
        
        <strong>Type</strong>
        <input lot-id="<?=$id;?>" type="text" name="type" id="type_<?=$id;?>" placeholder="type" value="<?=$data['type'];?>" class="form-control input-md batch-lot-input" type="text"  data-id="<?=$id;?>">
        <br/>
        
        <strong>Material</strong>
        <input lot-id="<?=$id;?>" type="text" name="material" id="material_<?=$id;?>" placeholder="material" value="<?=$data['material'];?>" class="form-control input-md batch-lot-input" type="text"  data-id="<?=$id;?>">
        <br/>
        
        <strong>Color</strong>
        <input lot-id="<?=$id;?>" type="text" name="color" id="color_<?=$id;?>" placeholder="color" value="<?=$data['color'];?>" class="color_field form-control input-md batch-lot-input" type="text"  data-id="<?=$id;?>">
        <div class="suggesstion-box" id="suggesstion-box_<?=$id;?>"></div>
        <br/>
        
        <strong>Lot Number</strong>
		<input lot-id="<?=$id;?>" type="text" name="lot_number" id="lot_number_<?=$id;?>" placeholder="Lot Number" value="<?=$data['lot_number'];?>" class="form-control input-md batch-lot-input"  type="text"  data-id="<?=$id;?>">
        <br/>
        <button type="button" class="btn btn-info" onclick="save_batch_lot('<?=$id;?>')">Update Batch Lot</button>
		<button type="button" class="btn btn-danger" onclick="remove_batch_lot('<?=$id;?>')">Remove</button>
</form>
	<script>
	    function remove_batch_lot(id) {
			console.log(id);
		    var lot_id = id;

            $.ajax({
        		type: "POST",
        		url: "<?=root()?>exec/remove_batch_lot/",  
        		data: { id: lot_id},
        		dataType: "json",
        		success: function(data)
        		{
        		    $.notify("Successfully deleted #"+lot_id, 'success')
        			window.location.href="<?=root();?>?page=settings";
        		}
    		});
		}
	
	    $(".suggesstion-box").hide();
	    
        var timer = null;
        $(document).on('keyup', '.color_field', function() {
            clearTimeout(timer); 
            var id = $(this).attr('id');
            timer = setTimeout(check_color(id), 1000)
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
        
        function selectColor(id, val) 
        {
            var lot_id = id.replace('color_','');
            
            $("#"+id).val(val);
            $("#suggesstion-box_"+lot_id).hide();    
            $("input[name=color]").val(val);
            
            //save_batch_lot(lot_id);
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
        		dataType: "json",
        		success: function(data)
        		{
        			//var json = $.parseJSON(data); // create an object with the key of the array
                    //alert(json.html);
        			if(data == 0){
                        $.notify("Sorry your color name not available in designer.\nTo see available list color in designer please visit Color Settings Page", 'error')
        			}else if(data == 1){
                        $.notify("Successfully updated #"+lot_id, 'success')
                        		window.location.href="<?=root();?>?page=settings";
        			}else{}
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
		
	</script>
	
	
</div>
