<?

$order_status = sf($_GET['order_status']);

if(empty($order_status)) $order_status = 'PAID';

if($order_status=='UNPAID') $order_status = '';



?>
<div class="col-md-3">
	<h2 class="form-signin-heading">Mask Orders</h2>
</div>

<div class="col-md-5">
	<br />

	<input type="text" placeholder="search" id="livesearch" class="livesearch" style="width: 250px;">  

	Status: 
	<select id="order_status" onchange="document.location='/?page=masks&order_status='+$('#order_status').val();">
		<option value="PAID" <?=($order_status=='PAID' ? 'selected' : '')?>>PAID</option>
		<option value="SHIPPED" <?=($order_status=='SHIPPED' ? 'selected' : '')?>>SHIPPED</option>
		<option value="UNPAID" <?=($order_status=='UNPAID' ? 'selected' : '')?>>UNPAID</option>
	</select>
</div>

<script>

	$(function() {
			
			$('#livesearch').keyup(function() {
				
				$('#data_table').load('<?=root()?>exec/masks/?order_status=<?=$_GET['order_status']?>&search='+encodeURI($('#livesearch').val())+' #data_table');
			
			});
			
	});
	
</script>


<div class="col-md-4 text-right"><br />

	<!--<button class="btn btn-info" onclick="document.location='<?=root()?>?page=mask_production';">View Production</button>-->
		<button class="btn btn-info" onclick="document.location='<?=root()?>?page=mask_production_2';">View Production - BETA</button>
</div>
<div class="clear"></div>

<div id="data_table">
<table class="table table-striped table-bordered table-hover">
<tr>
	<th width="5%"><a href="<?=root()?>?page=masks&order_status=<?=$_GET['order_status']?>&order_by=id">ID</a></th>
	<th width="30%"><a href="<?=root()?>?page=masks&order_status=<?=$_GET['order_status']?>&order_by=id">Date</th>
	<th><a href="<?=root()?>?page=masks&order_status=<?=$_GET['order_status']?>&order_by=name">Name</a></th>
	<th><a href="<?=root()?>?page=masks&order_status=<?=$_GET['order_status']?>&order_by=state">State</a></th>
	<th><a href="<?=root()?>?page=masks&order_status=<?=$_GET['order_status']?>&order_by=payment_method">Payment Method</a></th>
	<th><a href="<?=root()?>?page=masks&order_status=<?=$_GET['order_status']?>&order_by=shipped_date">Shipped Date</a></th>
	<? if($_SESSION['type']=='admin') { ?><th><a href="<?=root()?>?page=masks&order_status=<?=$_GET['order_status']?>&order_by=order_total">Order Total</a></th><? } ?>
</tr>

<?

if($_GET['search']) {
	$search_sql = "AND (mask_orders.name LIKE '%".sf($_GET['search'])."%' OR mask_orders.email LIKE '%".sf($_GET['search'])."%' OR mask_orders.phone LIKE '%".sf($_GET['search'])."%' OR mask_orders.transaction_id LIKE '%".sf($_GET['search'])."%')";
}

if(empty($_GET['order_by'])) $_GET['order_by']='date';

$q = mysqli_query($link, 'SELECT * FROM mask_orders WHERE `order_status`=\''.sf($order_status).'\' '.$search_sql.' ORDER BY '.sf($_GET['order_by']).' ASC');


while($row = mysqli_fetch_assoc($q)) {
	echo '<tr onclick="document.location=\''.root().'?page=mask_order&id='.$row['id'].'\'">
			<td>'.$row['id'].'</td>
			<td>'.$row['date'].'</td>
			<td>'.$row['name'].'</td>
			<td>'.$row['state'].'</td>
			<td>'.$row['payment_method'].'</td>
			<td>'.$row['shipped_date'].'</td>';
			if($_SESSION['type']=='admin') { 
				echo '<td>'.$row['order_total'].'</td>'; 
			}
		echo '</tr>';
}

?>

</table>      
</div>