<div class="container">
    <div class="row">
        <div class="col-sm-12 pt-5">
            <h1>Please confirm your order</h1>
          
        </div>
    </div>

   
   <strong>Order Summary</strong><br />
   
    <table class="table table-bordered">
		<thead>
			<tr>
				<th>Mask</th>
				<th width="20%">QTY</th>
				<th width="10%">Price</th>
			</tr>
		</thead>
		<tbody>
			
			<?
			
			
			$cart = $_SESSION['cart'];
			
			
			if (count($cart) <= 0) {
				header( 'Location: ' . root() );
				exit();
			}
			
			foreach ($cart as $item_id=>$item) {
				echo '<tr><td>'.$item['desc'].'</td><td class="text-center">'.$item['qty'].'</td><td class="text-center">$'.number_format(($item['price']*$item['qty']), 2, '.', '').'</td></tr>';
				$total += $item['price']*$item['qty'];
			}
			echo '<tr><td colspan="2" class="text-right">Total</td><td class="text-center">$'.number_format(($total), 2, '.', '').'</td></tr>';
			?>
			
		</tbody>
	</table>

	
    <form id="checkout_form" action="" method="post" enctype="multipart/form-data">
		
		<strong>Enter your shipping info:</strong>
		
		<div class="row">
			<div class="col-sm-12 pt-5">
		
				<h2>Your Info</h2>
				
				<div class="form-group">
					<label for="cname" class="control-label">
						<strong>Name:</strong>
					</label>
					<input type="text" name="name" id="name" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="email" class="control-label">
						<strong>Email:</strong>
					</label>
					<input type="text" name="email" id="email" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="address" class="control-label">
						<strong>Address Line 1:</strong>
					</label>
					<input type="text" name="address" id="address" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="address_2" class="control-label">
						<strong>Address Line 2:</strong>
					</label>
					<input type="text" name="address_2" id="address_2" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="city" class="control-label">
						<strong>City:</strong>
					</label>
					<input type="text" name="city" id="city" class="form-control"/>
				</div>
				
				<div class="form-group">
					<label for="state" class="control-label">
						<strong>State:</strong>
					</label>
					<input type="text" name="state" id="state" class="form-control"/>
				</div>
				
				<div class="form-group">
					<label for="zip" class="control-label">
						<strong>Postal Code:</strong>
					</label>
					<input type="text" name="zip" id="zip" class="form-control"/>
				</div>
				
				
				<div class="form-group">
					<label for="phone" class="control-label">
						<strong>Phone:</strong>
					</label>
					<input type="number"  name="phone" id="phone" class="form-control"/>
				</div>
				<br />
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12 pt-5">
				<strong>Payment Method:</strong> &nbsp; &nbsp;
				<input type="radio" name="payment_type" value="paypal" checked="checked" onclick="$('#cc').hide();"> PayPal
				&nbsp; &nbsp;
				<input type="radio" name="payment_type" value="cc" onclick="$('#cc').show();"> Credit Card
			</div>
		</div>
		
		<div class="row" id="cc" style="display:none;">
			<div class="col-sm-12 pt-5">
		
				<h2>Credit Card</h2>
				
				<div class="form-group">
					<label for="cname" class="control-label">
						<strong>Name On Card:</strong>
					</label>
					<input type="text" name="cc_name" id="cc_name" class="form-control"/>
				</div>
				
				<div class="form-group">
					<label for="cc_number" class="control-label">
						<strong>Credit Card Number:</strong>
					</label>
					<input type="text" name="cc_number" id="cc_number" class="form-control"/>
				</div>
				
				<div class="form-group">
					<label class="control-label">
						<strong>Expiration Date (mm/yyyy):</strong>
					</label> 
					<br />
					
					<input type="text" name="cc_exp_month" id="cc_exp_month" style="width: 30px"> / <input type="text" name="cc_exp_year" id="cc_exp_month" style="width: 50px">
				</div>
				
				<div class="form-group">
					<label for="cc_number" class="control-label">
						<strong>CVV Code:</strong>
					</label>
					<input type="text" name="cc_cvv" id="cc_cvv" class="form-control" style="width: 70px" />
				</div>
				
				<br />
			</div>
		</div>
		<br />
		<br />
		<Strong>Refund:</strong><br />
		

A full refund is available if the order has not shipped.   Please contact us as pmisales@peregrinemfginc.com to request a refund.  
<br />
<br />
<strong>Returns:</strong><br />
 

No returns are permitted.  All returned items will be refused or discarded prior to entering our facility.   We hope that you understand and appreciate the precautions we are making to keep you and our staff safe during this time.
		<hr>
		
		<div id="card_error" class="row" style="display: none;"><div class="col-sm-12 bg-danger text-center"></div></div>
		
		<div class="col-sm-12 text-right">
			<button type="button" class="btn btn-primary" id="complete_order_button" onclick="complete_order();">Complete Order</button>
		 </div>
    </form>
</div>
<script>

	
	function complete_order() {
		
		if($('#name').val() == '' || $('#email').val() == '' || $('#address').val() == '' || $('#city').val() == '' || $('#state').val() == '' || $('#zip').val() == '') {
			alert('Please fill in all required fields');
			return false;
		}
		
		$('#complete_order_button').text('Please Wait...');
		$('#complete_order_button').prop('disabled', true);
		
		
		$.post( "<?=root()?>inc/exec.php?act=checkout", $('#checkout_form').serialize(), '', 'script');
		
	}
	

</script>
