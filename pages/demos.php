<?
if(!check_access('demos')) exit();
?>
<div class="col-md-4">
	<h2 class="form-signin-heading">Demos</h2>
</div>

<div class="col-md-4">
<br />

<input type="text" placeholder="search" id="livesearch" class="livesearch">
</div>


<script>

	$(function() {
			
			$('#livesearch').keypress(function() {
				
				$('#data_table').load('<?=root()?>exec/demos/?search='+$('#livesearch').val()+' #data_table');
			
			});
			
	});
	
</script>

<div class="col-md-4 text-right">
	<br>
	<button class="btn btn-info" type="button" onclick="document.location='<?=root()?>?page=settings&type=demo_stock';">Demo Stock</button>
	
	<button class="btn btn-success" type="button" onclick="document.location='<?=root()?>?page=add_demo';">New Demo Request</button>


</div>

<h4>Demo Container Requests</h4>
<div id="data_table">
<table class="table table-striped table-bordered table-hover">
<tr><th width="60%">Customer</th><th>Requested Container</th><th>Request Date</th><th>Request Status</th></tr>

<?
if($_GET['search']) {
	$search_sql = " AND ( demo_requests.notes LIKE '%".sf($_GET['search'])."%' OR demo_requests.metadata LIKE '%".sf($_GET['search'])."%' OR customers.name LIKE '%".sf($_GET['search'])."%' OR demo_pool.name LIKE '%".sf($_GET['search'])."%' )";
}


$q = mysqli_query($link, 'SELECT demo_requests.*, customers.name, demo_pool.name as pool_name FROM demo_requests, customers, demo_pool WHERE demo_requests.customer = customers.id AND demo_pool.id = demo_requests.requested_demo_pool AND demo_requests.status!=\'Completed\' '.$search_sql.' ORDER BY demo_requests.id ASC');


while($row = mysqli_fetch_assoc($q)) {
	echo '<tr onclick="document.location=\''.root().'?page=demo&id='.$row['id'].'\'">
	<td>'.$row['name'].'</td>
	<td>'.$row['pool_name'].'</td>
	<td>'.$row['date'].'</td>
	<td>'.$row['status'].'</td>
	</tr>';
}

?>

</table>      
</div>

<h4>Stock Demo Wings</h4>
<div id="data_table">
<table class="table table-striped table-bordered table-hover">
<tr><th width="60%">Serial Number</th><th>Colors</th><th>Date Added</th><th>Status</th><th></th></tr>

<?


$q = mysqli_query($link, 'SELECT demo_units.* FROM demo_units LEFT JOIN demo_requests ON (demo_units.assigned_demo_request = demo_requests.id) ORDER BY demo_units.id ASC');


while($row = mysqli_fetch_assoc($q)) {
	echo '<tr onclick="">
	<td>'.$row['serial_number'].'</td>
	<td>'.$row['colors'].'</td>
	<td>'.$row['date_added'].'</td>
	<td>'.$row['status'].'</td>
	<td><button class="btn btn-success" type="button" onclick="document.location=">Assign</button></td>
	</tr>';
}

?>

</table>      
</div>