<?


?>

<div class="col-md-6">
	<h1>Mask Production</h1>
  
</div>




<hr><br />
<br />

<strong>Counts</strong>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Type</th>
			<th>Mask</th>
			<th width="20%">QTY</th>
		</tr>
	</thead>
	<tbody>
		
		<?
		$types = array();
		$types['with_embroidery']='Black with Embroidery';
		$types['without_embroidery']='Black without Embroidery';
		$types['cb_with_embroidery']='Coyote Brown with Embroidery';
		$types['cb_without_embroidery']='Coyote Brown without Embroidery';
		$types['ulw_with_embroidery']='ULW with Embroidery';
		$types['ulw_without_embroidery']='ULW without Embroidery';
		$types['fr']='FR';
		
		$total = 0;
		
		$masks_q = mysqli_query($link, 'SELECT DISTINCT mask_order_items.size, mask_order_items.type, SUM(qty) as qty  FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\'PAID\' GROUP BY mask_order_items.type, mask_order_items.size ORDER BY mask_order_items.size ASC');
		
		while($mask = mysqli_fetch_assoc($masks_q)) {
		
		
			
				echo '<tr><td>'.$types[$mask['type']].'</td><td>'.$mask['size'].'</td><td class="text-center">'.$mask['qty'].'</td></tr>';
				$total += $mask['qty'];
			
		
		}
		?>
		
	</tbody>
</table>
<strong>Total needed: <?=$total?> </strong>

<br />
<br />
<br />



<strong>With Embroidery</strong>
<br />

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Mask</th>
			<th width="20%">QTY</th>
		</tr>
	</thead>
	<tbody>
		
		<?
		
		$total = 0;
		
		$masks_q = mysqli_query($link, 'SELECT DISTINCT mask_order_items.description, SUM(qty) as qty, mask_order_items.size  FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\'PAID\' AND mask_order_items.type LIKE \'%with_embroidery\' GROUP BY mask_order_items.description ORDER BY mask_order_items.size ASC');
		
		while($mask = mysqli_fetch_assoc($masks_q)) {
		
		
			
				echo '<tr><td>'.$mask['description'].'</td><td class="text-center">'.$mask['qty'].'</td></tr>';
				$total += $mask['qty'];
			
		
		}
		?>
		
	</tbody>
</table>


<strong>Total with embroidery: <?=$total?> </strong>
<br />
<br />
<br />


<strong>Without Embroidery</strong>
<br />

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Mask</th>
			<th width="20%">QTY</th>
		</tr>
	</thead>
	<tbody>
		
		<?
		
		$total = 0;
		
		$masks_q = mysqli_query($link, 'SELECT DISTINCT mask_order_items.description, SUM(qty) as qty  FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\'PAID\' AND mask_order_items.type LIKE \'%without_embroidery\' GROUP BY mask_order_items.description ORDER BY mask_order_items.description ASC');
		
		while($mask = mysqli_fetch_assoc($masks_q)) {
		
		
			
				echo '<tr><td>'.$mask['description'].'</td><td class="text-center">'.$mask['qty'].'</td></tr>';
				$total += $mask['qty'];
			
		
		}
		?>
		
	</tbody>
</table>
<strong>Total without embroidery: <?=$total?> </strong>
<br />
<br />

<br />
<br />
<br />


<strong>FR</strong>
<br />

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Mask</th>
			<th width="20%">QTY</th>
		</tr>
	</thead>
	<tbody>
		
		<?
		
		$total = 0;
		
		$masks_q = mysqli_query($link, 'SELECT DISTINCT mask_order_items.description, SUM(qty) as qty  FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\'PAID\' AND mask_order_items.type = \'fr\' GROUP BY mask_order_items.description ORDER BY mask_order_items.description ASC');
		
		while($mask = mysqli_fetch_assoc($masks_q)) {
		
		
			
				echo '<tr><td>'.$mask['description'].'</td><td class="text-center">'.$mask['qty'].'</td></tr>';
				$total += $mask['qty'];
			
		
		}
		?>
		
	</tbody>
</table>
<strong>Total without embroidery: <?=$total?> </strong>
<br />
<br />
