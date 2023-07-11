<?

$order_q = mysqli_query($link, 'SELECT * FROM mask_orders WHERE id=\''.sf($_GET['id']).'\'');

$order = mysqli_fetch_assoc($order_q);


if($_GET['shipped']==1) {

	mysqli_query($link, 'UPDATE mask_orders SET order_status=\'SHIPPED\', `shipped_date`=NOW() WHERE id=\''.sf($_GET['id']).'\'');
	
	$cart = json_decode($order['order_data'],true);
	
	foreach ($cart as $item_id=>$item) {
		$order_confirmation_items .= $item['qty'].' x '.$item['desc'].' - $'.number_format(($item['price']*$item['qty']), 2, '.', '')."\n";
	}
	
	
	$message = sf($order['name'])."\n\n";
	$message .= 'Your mask order has shipped!'."\n\n";
	$message .= 'Below is a summary of your order.'."\n\n";
	$message .= $order_confirmation_items;
	$message .= "\nTotal: $".number_format($order['order_total'],2,'.','')."\n\n";
	$message .= "Shipping Address:\n";
	$message .= sf($order['name'])."\n";
	$message .= sf($order['address_1'])."\n";
	if($order['address_2']) $message .= sf($order['address_2'])."\n";
	$message .= sf($order['city']).", ".sf($order['state'])." ".sf($order['zip'])."\n\n";
	$message .= "If you have any questions, please email us at pmisales@peregrinemfginc.com.\n\n";
	$message .= "Thank You\n\n";
	$message .= "Peregrine Manufacturing, Inc.";
	
	smtpemail(sf($order['email']),'Your order has shipped!',$message);
	
	header('location: /?page=masks');

}

if($_GET['paid']==1) {

	mysqli_query($link, 'UPDATE mask_orders SET order_status=\'PAID\' WHERE id=\''.sf($_GET['id']).'\'');
	
	header('location: /?page=masks');

}

?>
<div class="row">
	<div class="col-md-6">
		<h1>Order #<?=$order['id']?></h1>
	  
	</div>
	<div class="col-md-6 text-right">
	<br />
		<? if($order['order_status']=='' || $order['order_status']=='UNPAID') { ?>
			<button class="btn btn-warning" onclick="document.location='/?page=mask_order&id=<?=$_GET['id']?>&paid=1';">Mark as Paid</button>

		<? } ?>
		<? if($order['order_status']=='PAID') { ?>
				<button class="btn btn-info" onclick="document.location='/?page=mask_order&id=<?=$_GET['id']?>&shipped=1';">Mark as Shipped</button>

		<? } ?>
		
	  
	</div>
</div>

<div class="row pt-5">
	<div class="col-sm-6">

		
		<strong>Customer Information</strong>
		<br />
		<br />
		
		<?=$order['name']?><br />
		<?=$order['address_1']?><br />
		<?=($order['address_2']!=='' ? $order['address_2'].'<br>' : '')?>
		<?=$order['city']?>, <?=$order['state']?> <?=$order['zip']?>
		
		<br />
		<br />
		<strong>Phone</strong>: <?=$order['phone']?><br />
		<strong>Email</strong>: <?=$order['email']?>
		
		
	</div>
	
	<div class="col-sm-6">

		
		<strong>Payment Info</strong>
		<br />
		<br />
		
		<strong>Processor</strong>: <?=$order['payment_method']?><br />
		<strong>Transaction ID</strong>: <?=$order['transaction_id']?>
		
	</div>
</div>

<div class="row">
<br />
<br />

<strong>Order Summary</strong> <br />
</div>
<hr>
<? if($_SESSION['type']=='admin') { ?>
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
<? } else { ?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Mask</th>
			<th width="20%">QTY</th>
		</tr>
	</thead>
	<tbody>
		
		<?
		
		$cart = json_decode($order['order_data'],true);
		
		
		
		foreach ($cart as $item_id=>$item) {
			echo '<tr><td>'.$item['desc'].'</td><td class="text-center">'.$item['qty'].'</td></tr>';
			$total += $item['price']*$item['qty'];
		}
		
		?>
		
	</tbody>
</table>

<?  } ?>
<br />
<br />