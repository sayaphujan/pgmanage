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
					echo '<tr><td>'.$item['desc'].'</td><td class="text-center">'.$item['qty'].'</td><td class="text-center">'.number_format(($item['price']*$item['qty']), 2, '.', '').'</td><td class="text-center"><button class="btn-danger" onclick="document.location=\'inc/exec.php?act=remove_from_cart&id='.$item_id.'\';">Remove</button></td></tr>';
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
	
    <form id="mask_form" action="" method="post" enctype="multipart/form-data">
		<strong>Step 1: Pick a style</strong>
		<hr>
        <div class="row">
            <div class="col-sm-2 pt-5 text-center">
				<img src="images/with_embroidery.jpeg" mask_type="with_embroidery" class="mask_style_image"><br />
				Shredder Standard with embroidery <br> $27.00/ea
            </div>
            <div class="col-sm-2 pt-5 text-center ">
				<img src="images/no_embroidery.jpeg" mask_type="without_embroidery" class="mask_style_image"><br />
				Shredder Standard Black without embroidery <br> $22.00/ea
            </div>
            <div class="col-sm-2 pt-5 text-center">
				<img src="images/shredder_cb_emb.jpg" mask_type="cb_with_embroidery" class="mask_style_image"><br />
				Shredder Standard Coyote Brown with embroidery <br> $27.00/ea
            </div>
            <div class="col-sm-2 pt-5 text-center ">
				<img src="images/shredder_cb.jpg" mask_type="cb_without_embroidery" class="mask_style_image"><br />
				Shredder Standard Coyote Brown without embroidery <br> $22.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="images/ulw_with_embroidery.jpeg" mask_type="ulw_with_embroidery" class="mask_style_image"><br />
				Shredder Ultra Light Weight with embroidery <br> $27.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="images/shredder_ulw.jpg" mask_type="ulw_without_embroidery" class="mask_style_image"><br />
				Shredder Ultra Light Weight Black <br> $22.00/ea
            </div>
			<div class="col-sm-2 pt-5 text-center ">
				<img src="images/fr.jpg" mask_type="fr" class="mask_style_image"><br />
				Shredder FR <br>(Fire Retardant) <br> $44.00/ea
            </div>
        </div>
		<div class="text-left" id="embroidery_color_section" style="display: none;">
		
				<div class="row pt-5">
					<strong>Embroidery Color</strong>
				</div>
				<hr>
				
				<select name="mask_embroidery_color" id="mask_embroidery_color" class="ol mask_option" autocomplete="off">
					<option value="White">White</option><option value="Black">Black</option><option value="Charcoal">Charcoal</option><option value="Silver">Silver</option><option value="Forest Green">Forest Green</option><option value="Kelly Green">Kelly Green</option><option value="Neon Green">Neon Green</option><option value="Tan">Tan</option><option value="Khaki">Khaki</option><option value="Browns">Browns</option><option value="Royal Blue">Royal Blue</option><option value="Navy Blue">Navy Blue</option><option value="Electric Blue">Electric Blue</option><option value="Purple">Purple</option><option value="Pink">Pink</option><option value="Neon Pink">Neon Pink</option><option value="Orange">Orange</option><option value="Neon Orange">Neon Orange</option><option value="Red">Red</option><option value="Burgandy">Burgandy</option><option value="Yellow">Yellow</option><option value="Neon Yellow">Neon Yellow</option><option value="Gold">Gold</option>
				</select>
				<br /><br />
				
				
				<hr>
		</div>
						<br />

		<input id="selected_mask_type" name="selected_mask_type" type="hidden" value="">
		<br />
		<br />
		<strong>N95 Filtration</strong><br />
		<ol>
			<li>As of 4/27/20, we will no longer be supplying N95 filter paper with our masks.  Unfortunately the donation supply has run out and we are no longer able to supply filters at no charge</li>
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
			
				<a href="/assets/MASK_SIZING_GUIDE_Rev_2.pdf" target="_blank"><img src="images/sizing_chart.jpg" style="height: 100px; cursor: pointer;"></a>
				
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
					
					<img src="images/sizing_chart.jpg" style="height: 600px; object-fit: cover; max-width: 90vw;">
					
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
		
		
		if(selected_mask_style=='with_embroidery') {
			display_txt = 'Mask with '+emb_color+' embroidery - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='without_embroidery') {
			display_txt = 'Shredder mask without embroidery - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='cb_with_embroidery') {
			display_txt = 'Coyote brown Shredder mask with '+emb_color+' embroidery - Size '+mask_size+' - Qty: '+mask_qty;

		}
		
		if(selected_mask_style=='cb_without_embroidery') {
			display_txt = 'Coyote brown Shredder mask without embroidery - Size '+mask_size+' - Qty: '+mask_qty;

		}
		
		if(selected_mask_style=='ulw_without_embroidery') {
			display_txt = 'Ultra light weight Shredder mask without embroidery - Size '+mask_size+' - Qty: '+mask_qty;

		}
		
		if(selected_mask_style=='ulw_with_embroidery') {
			display_txt = 'Ultra light weight Shredder mask with '+emb_color+' embroidery - Size '+mask_size+' - Qty: '+mask_qty;
		}
		
		if(selected_mask_style=='fr') {
			display_txt = 'Fire retardant Shredder mask - Size '+mask_size+' - Qty: '+mask_qty;
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
		
		$.post( "inc/exec.php?act=add-to-cart", $('#mask_form').serialize(), '', 'script');
		
	}
	

</script>
