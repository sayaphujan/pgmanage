<?

$order_q = mysqli_query($link, 'SELECT * FROM mask_orders WHERE id=\''.sf($_SESSION['order_id']).'\'');

if(mysqli_num_rows($order_q)==0) header('location: '.root());

unset($_SESSION['cart']);

$order = mysqli_fetch_assoc($order_q);

?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 pt-5">
            <h1>Order Confirmation - Order #<?=$order['id']?></h1>
          
        </div>
    </div>
	
	
	<div class="row">
		<div class="col-sm-12 pt-5">
			Thank you for your order. Standard shipping times are 1-4 days. 
			<br />
			<br />
			
			<strong>Customer Information</strong>
			<br />
			<br />
			
			<?=$order['name']?><br />
			<?=$order['address_1']?><br />
			<?=($order['address_2']!=='' ? $order['address_2'].'<br>' : '')?>
			<?=$order['city']?>, <?=$order['state']?> <?=$order['zip']?>
			
		</div>
	</div>
	<br />
	<br />
	<strong>Order Summary</strong> <br />
	
   <hr>
   
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
			
			$cart = json_decode($order['order_data'],true);
			
			
			
			foreach ($cart as $item_id=>$item) {
				echo '<tr><td>'.$item['desc'].'</td><td class="text-center">'.$item['qty'].'</td><td class="text-center">$'.number_format(($item['price']*$item['qty']), 2, '.', '').'</td></tr>';
				$total += $item['price']*$item['qty'];
			}
			echo '<tr><td colspan="2" class="text-right">Total</td><td class="text-center">$'.number_format(($total), 2, '.', '').'</td></tr>';
			?>
			
		</tbody>
	</table>
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
			<li>Machine wash with like colors, Cold water with regular detergent</li>
			<li>Air dry only, do not put in the dryer</li>
		</ul>
	
 
</div>
