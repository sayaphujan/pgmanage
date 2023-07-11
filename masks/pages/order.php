<?
if($_SERVER['REMOTE_ADDR']!=='10.0.0.50') {
/*
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 pt-5 text-center">
            <h1>Down for maintenance</h1>
            <p class="lead">
                We're adding some exciting stuff. Check back soon!
            </p>
        </div>
    </div>

   
<?
exit();
*/

}
?>


<div class="container">
    <div class="row">
        <div class="col-sm-12 pt-5">
            <h1>Masks</h1>
            <p class="lead">
                Peregrine Manufacturing is now offering non-surgical safety masks. Please use the form below to place your order. For bulk orders of 50 or more, please email us at <a href="mailto:pmisales@peregrinemfginc.com">pmisales@peregrinemfginc.com</a> first.
            </p>
        </div>
    </div>

   
   <strong>Order Summary</strong><br />
   
    <table class="table table-bordered">
		<thead>
			<tr>
				<th>Mask</th>
				<th width="20%">QTY</th>
				<th width="10%">Price</th>
				<th width="10%"></th>
			</tr>
		</thead>
		<tbody>
			
			<?
			
			if(!$_SESSION['cart']) {
				$_SESSION['cart']=array();
				$_SESSION['cart_id']=1;
			}
			
			$cart = $_SESSION['cart'];
			
			
			if (count($cart) > 0) {
				foreach ($cart as $item_id=>$item) {
					echo '<tr><td>'.$item['desc'].'</td><td class="text-center">'.$item['qty'].'</td><td class="text-center">'.number_format(($item['price']*$item['qty']), 2, '.', '').'</td><td class="text-center"><button class="btn-danger" onclick="document.location=\'/inc/exec.php?act=remove_from_cart&id='.$item_id.'\';">Remove</button></td></tr>';
				}
			} else {
				echo '<tr><td colspan="4" align="center">Continue below to order your mask!</td></tr>';
			}
			?>
		</tbody>
	</table>
	<? if (count($cart) > 0) { ?>
	 <div class="col-sm-12 text-right">
		<button type="button" class="btn btn-primary" onclick="document.location='<?=root()?>checkout/';">Checkout</button>
	 </div>
	<? } ?>
	<br />
	<br />
	
    <form id="mask_form" action="" method="post" enctype="multipart/form-data" autocomplete="off">
		<strong>Step 1: Pick a style</strong>
		<hr>
        <div class="row">
            <div class="col-sm-2 pt-5 text-center">
				<img src="/images/with_embroidery.jpeg" mask_type="with_embroidery" class="mask_style_image"><br />
				Shredder Standard with embroidery <br> $27.00/ea
            </div>
            <div class="col-sm-2 pt-5 text-center ">
				<img src="/images/no_embroidery.jpeg" mask_type="without_embroidery" class="mask_style_image"><br />
				Shredder Standard Black without embroidery <br> $22.00/ea
            </div>
            <div class="col-sm-2 pt-5 text-center">
				<img src="/images/shredder_cb_emb.jpg" mask_type="cb_with_embroidery" class="mask_style_image"><br />
				Shredder Standard Coyote Brown with embroidery <br> $27.00/ea
            </div>
            <div class="col-sm-2 pt-5 text-center ">
				<img src="/images/shredder_cb.jpg" mask_type="cb_without_embroidery" class="mask_style_image"><br />
				Shredder Standard Coyote Brown without embroidery <br> $22.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="/images/with_embroidery.jpeg" mask_type="ulw_with_embroidery" class="mask_style_image"><br />
				Shredder Ultra Light Weight with embroidery <br> $27.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="/images/shredder_ulw.jpg" mask_type="ulw_without_embroidery" class="mask_style_image"><br />
				Shredder Ultra Light Weight Black <br> $22.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="/images/fl_black.jpeg" mask_type="fl_black" class="mask_style_image"><br />
				Shredder FL (Featherlite) - Black <br> <span style="color: red;">NEW!!!</span> <br> $14.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="/images/fl_cb.jpeg" mask_type="fl_cb" class="mask_style_image"><br />
				Shredder FL (Featherlite) - Coyote Brown <span style="color: red;">NEW!!!</span> <br> $14.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="/images/fr.jpg" mask_type="fr" class="mask_style_image"><br />
				Shredder FL (Featherlite) <br>(Fire Retardant) <br> $44.00/ea
            </div>
        </div>
		<div class="text-left" id="embroidery_color_section" style="display: none;">
		
				<div class="row pt-5">
					<strong>Embroidery Options</strong> - Please choose one
				</div>
				<hr>
				
				<?
				
				function show_embroidery_select($name, $allow_none=false) {
					echo '<select name="'.$name.'" id="'.$name.'" class="ol mask_option" autocomplete="off">';
					
					if($allow_none==true) echo '<option value="">None</option>';
					echo'		<option value="White">White</option><option value="Black">Black</option><option value="Charcoal">Charcoal</option><option value="Silver">Silver</option><option value="Forest Green">Forest Green</option><option value="Kelly Green">Kelly Green</option><option value="Neon Green">Neon Green</option><option value="Tan">Tan</option><option value="Khaki">Khaki</option><option value="Browns">Browns</option><option value="Royal Blue">Royal Blue</option><option value="Navy Blue">Navy Blue</option><option value="Electric Blue">Electric Blue</option><option value="Purple">Purple</option><option value="Pink">Pink</option><option value="Neon Pink">Neon Pink</option><option value="Orange">Orange</option><option value="Neon Orange">Neon Orange</option><option value="Red">Red</option><option value="Burgandy">Burgandy</option><option value="Yellow">Yellow</option><option value="Neon Yellow">Neon Yellow</option><option value="Gold">Gold</option>
						</select>';
				}
				
				?>
				
				<div class="row">
					<div class="col-md-5">
						<input type="radio" name="embroidery_type" id="embroidery_type_default" value="pmi_logo" checked="checked" class="mask_option embroidery_type_select" >
						
						<strong id="embroidery_logo_label_default">Peregrine Manufacturing Logo</strong> <span id="embroidery_logo_label_default_selected">(Selected)</span>
						
						<br />
						
						<br />
						Get your mask embroidered with a Peregrine Manufacturing Inc. logo pictured above! <br />
						<br />
						
						Please select a color: <?=show_embroidery_select('mask_embroidery_color')?>
						
						
					
					</div>
					
					<div class="col-md-1">
						<strong>Or</strong>
					</div>
					
					<div class="col-md-6">
						<input type="radio" name="embroidery_type" id="embroidery_type_custom"  value="custom" class="mask_option embroidery_type_select"> 
						
						<strong  id="embroidery_logo_label_custom" style="color: #888;">Custom Embroidery - $5.00</strong> <span id="embroidery_logo_label_custom_selected"></span>
						
						<br />
						<br />
						Upload your own digitized artwork and pick up to 3 colors below for custom embroidery on your mask.<br />
						<br />
						Please upload your digitized artwork (.dst or .emb file only):<br />
						<input type="file" name="embroidery_art" accept=".dst,.emb" class="mask_option">
						<br />
						<br />
						Color 1: <?=show_embroidery_select('mask_custom_embroidery_color_1')?>
						<br />
						<br />
						Color 2: <?=show_embroidery_select('mask_custom_embroidery_color_2', true)?>
						<br />
						<br />
						Color 3: <?=show_embroidery_select('mask_custom_embroidery_color_3', true)?>
						
					</div>
				</div>
				
				<script>
				$('.embroidery_type_select').click(function () {
					if($(this).val()=='pmi_logo') {
						$('#embroidery_logo_label_default').css('color', '#fff');
						$('#embroidery_logo_label_custom').css('color', '#888');
						$('#embroidery_logo_label_default_selected').html('(selected)');
						$('#embroidery_logo_label_custom_selected').html('');
					} else {
						$('#embroidery_logo_label_default').css('color', '#888');
						$('#embroidery_logo_label_custom').css('color', '#fff');
						$('#embroidery_logo_label_default_selected').html('');
						$('#embroidery_logo_label_custom_selected').html('(selected)');
					}
				});
				</script>
				
				
				
				<br /><br />
				
				
				<hr>
		</div>
						<br />

		<input id="selected_mask_type" name="selected_mask_type" type="hidden" value="">
		<br />
		<br />
		<strong>N95 Filtration</strong><br />
		<ol>
			<li>As of 4/27/20, we will no longer be supplying N95 filter paper with our masks. Â Unfortunately the donation supply has run out and we are no longer able to supply filters at no charge</li>
			<li>Please reference <a href="https://youtu.be/MHSCKt5I8So">this video</a> on the proper installation instructions for the N95 filter. </li>
			<li>N95 filters are cut to size by the customer and inserted between the layers to form the barrier.</li>
			<li>The customer should determine replacement interval of the filter based on individual usage and exposure risk level.</li>
			<li style="text-decoration: underline;">Standard N95 masks can also be used as the insert,doing this will significantly increase the life span.</li>
		</ol>
		<br />
		
		<strong>Fabric Care</strong><br />
		<ul>
			<li>Machine wash with like colors,  Cold water with regular detergent</li>
			<li>Air dry only, do not put in the dryer</li>
		</ul>


		
		<div class="row text-center">
			<div class="col-sm-6 text-center " style="display: none;">
				<div id="mask_selection_text"><strong>Please select a mask style above</strong></div>
			</div>
			
			
		</div>
		
		<div class="row pt-5">
			<strong>Step 2: Pick a size</strong>
		</div>
		<hr>
		
		<div class="row">
            <div class="col-sm-6">
			For mask sizing, please reference this guide: 
			
				<img src="/images/sizing_chart.jpg" style="height: 100px; cursor: pointer;" data-toggle="modal" data-target="#mask_sizing_modal">
				
           <br />
		   <br />
		   
				Please select a mask size:
				<select class="mask_option" name="mask_size" id="mask_size">
					<option value="XS">Extra Small</option>
					<option value="S">Small</option>
					<option value="M">Medium</option>
					<option value="L">Large</option>
					<option value="XL">Extra Large</option>
				</select>
            </div>
			
			
			<div class="modal fade" id="mask_sizing_modal" tabindex="-1" role="dialog" aria-hidden="true">
			  <div class="modal-dialog  modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
					<!-- Carousel markup goes in the modal body -->
					
					<img src="/images/sizing_chart.jpg" style="height: 600px; object-fit: cover; max-width: 90vw;">
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			</div>


							
        </div>
		
		<div class="row pt-5">
			<strong>Step 3: Pick a quantity</strong>
		</div>
		
		<hr>
		<div class="row">
            <div class="col-sm-12">
				Quantity: <input type="number" name="mask_qty" id="mask_qty" value="1" style="width: 40px;" class="mask_option"> 
            </div>
        </div>
		
		<hr>
        <div class="row">
			<div class="col-sm-10 text-left">

                <strong>Mask Summary</strong>: <span id="summary_desc"></span>
				
            </div>
            <div class="col-sm-2 text-right">

                <button type="button" class="btn btn-primary" onclick="add_to_cart()">Add to Order</button>
				
            </div>
        </div>
    </form>
</div>
<script>
	var selected_mask_style = null;
	
    $( document ).ready( function () {
		$('.mask_style_image').click(function() {
			$('.mask_style_image').css('border', '10px solid #343434');
			$(this).css('border', '10px solid #fff');
			//console.log($(this).attr('mask_type'));
			
			var type = $(this).attr('mask_type');
			
			if(type=='with_embroidery') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - with embroidery - $27.00/ea.');
				$('#embroidery_color_section').show();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='without_embroidery') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - without embroidery - $22.00/ea.');
				$('#embroidery_color_section').hide();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='fr') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - Fire Retardant - $44.00/ea.');
				$('#embroidery_color_section').hide();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='cb_with_embroidery') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - Coyote Brown with embroidery - $27.00/ea.');
				$('#embroidery_color_section').show();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='cb_without_embroidery') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - Coyote Brown with embroidery - $22.00/ea.');
				$('#embroidery_color_section').hide();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='ulw_without_embroidery') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - Ultra Light Weight - $22.00/ea.');
				$('#embroidery_color_section').hide();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='ulw_with_embroidery') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - Ultra Light Weight with embroidery - $27.00/ea.');
				$('#embroidery_color_section').show();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='fl_black') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - Fire Retardant - $44.00/ea.');
				$('#embroidery_color_section').hide();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			if(type=='fl_cb') {
				//$('#mask_selection_text').html('<strong>Selected Mask</strong> - Fire Retardant - $44.00/ea.');
				$('#embroidery_color_section').hide();
				selected_mask_style = type;
				$('#selected_mask_type').val(type);
			}
			
			create_build_summary();
			
			//console.log(type);
			
		});
		
		$('.mask_option').change(function() {
			create_build_summary();
		});
		
		
    } );
	
	
	function create_build_summary() {
		var mask_type = selected_mask_style;
		var emb_color = $('#mask_embroidery_color').val();
		var mask_size = $('#mask_size').val();
		var mask_qty = $('#mask_qty').val();
		
		
		var display_txt;
		
		var emb_desc;
		
		if($("input[name='embroidery_type']:checked").val()=='pmi_logo') {
			
			emb_desc = emb_color;
			
		} else {
			
			emb_desc = 'custom';
			
		}
		
		
		if(selected_mask_style=='with_embroidery') {
			display_txt = 'Mask with '+emb_desc+' embroidery - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='without_embroidery') {
			display_txt = 'Shredder mask without embroidery - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='cb_with_embroidery') {
			display_txt = 'Coyote brown Shredder mask with '+emb_desc+' embroidery - Size '+mask_size+' - Qty: '+mask_qty;

		}
		
		if(selected_mask_style=='cb_without_embroidery') {
			display_txt = 'Coyote brown Shredder mask without embroidery - Size '+mask_size+' - Qty: '+mask_qty;

		}
		
		if(selected_mask_style=='ulw_without_embroidery') {
			display_txt = 'Ultra light weight Shredder mask without embroidery - Size '+mask_size+' - Qty: '+mask_qty;

		}
		
		if(selected_mask_style=='ulw_with_embroidery') {
			display_txt = 'Ultra light weight Shredder mask with '+emb_desc+' embroidery - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='fr') {
			display_txt = 'Fire retardant Shredder mask - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='fl_black') {
			display_txt = 'Black Shredder FL (Featherlite) mask - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='fl_cb') {
			display_txt = 'Cotote Brown Shredder FL (Featherlite) mask - Size '+mask_size+' - Qty: '+mask_qty;
		}

		
		$('#summary_desc').html(display_txt);
	}
	
	function add_to_cart() {
		if($('#selected_mask_type').val() == '') {
			alert('Please select a mask type');
			return false;
		}
		
		if($('#selected_mask_type').val() > 0) {
			alert('Please select a quantity');
			return false;
		}
		
		//$.post( "/inc/exec.php?act=add-to-cart-beta", $('#mask_form').serialize(), '', 'script');
		
		
		
		var form = $('#mask_form')[0];
        var data = new FormData(form);
		
		$.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/inc/exec.php?act=add-to-cart-2",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {

                eval(data);

            },
            error: function (e) {
				
				//eval(e);
                //$("#result").text(e.responseText);
                console.log("ERROR : ", e);
                //$("#btnSubmit").prop("disabled", false);

            }
        });
		
		/*var formdata = $('#mask_form').serialize(); 
		
		$.ajax({
			type: "POST", 
			contentType:attr( "enctype", "multipart/form-data" ),
			url: "/inc/exec.php?act=add-to-cart-beta",  
			data: formdata,  
			success: function( data )  
			{
				 eval( data );
			}
          });*/
		
	}
	

</script>
