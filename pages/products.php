<?
if($_SESSION['type']!=='admin') exit();
?>
<div class="col-md-4">
	<h2 class="form-signin-heading">Products</h2>
</div>

<div class="col-md-5">
<br />

<input type="text" placeholder="search" id="livesearch" class="livesearch">
</div>


<script>

	$(function() {
			
			$('#livesearch').keypress(function() {
				
				$('#data_table').load('<?=root()?>exec/products/?search='+$('#livesearch').val()+' #data_table');
			
			});
			
	});
	
</script>

<div class="col-md-3 text-right">
<br />

	<button class="btn btn-success" onclick="document.location='<?=root()?>?page=product&add=1';">Add Product</button> <button class="btn btn-info" onclick="document.location='<?=root()?>?page=copy_product';">Copy Product</button>
</div>


<div id="data_table">
<table class="table table-striped table-bordered table-hover">
<tr><th width="60%">Product</th><th>Current Projects</th><th>Completed Projects</th></tr>

<?
if($_GET['search']) {
	$search_sql = " WHERE products.name LIKE '%".sf($_GET['search'])."%' OR products.workflow LIKE '%".sf($_GET['search'])."%' OR products.parts LIKE '%".sf($_GET['search'])."%'";
}


$q = mysqli_query($link, 'SELECT * FROM products '.$search_sql.' ORDER BY name ASC');

while($row = mysqli_fetch_assoc($q)) {
	echo '<tr onclick="document.location=\''.root().'?page=product&id='.$row['id'].'\'">
	<td>'.$row['name'].'</td>
	<td></td>
	<td></td>
	</tr>';
}

?>

</table>      
</div>