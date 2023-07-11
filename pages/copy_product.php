<?
if($_SESSION['type']!=='admin') exit();

if($_POST['product'] && $_POST['new_product_name']) {
	
	$q = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($_POST['product']).'\'');
	
	if(mysqli_num_rows($q)==0) {
		echo 'Error. Please try again';
		exit();
	}
	
	$product = mysqli_fetch_assoc($q);
	//product
	//product
	//product
	mysqli_query($link, 'INSERT INTO products (`name`, `workflow`, `parts`, `global_vars`) VALUES (\''.sf($_POST['new_product_name']).'\', \''.sf($product['workflow']).'\', \''.sf($product['parts']).'\', \''.sf($product['global_vars']).'\')');
	
	$product_id = mysqli_insert_id($link);
	
	$partcatq = mysqli_query($link, 'SELECT * FROM product_part_categories WHERE product=\''.sf($product['id']).'\'');
	
	while($partcat = mysqli_fetch_assoc($partcatq)) {
        //product_part_categories   
        //product_part_categories
        //product_part_categories
		mysqli_query($link, 'INSERT INTO product_part_categories (`product`, `c_order`, `name`) VALUES (\''.sf($product_id).'\', \''.sf($partcat['c_order']).'\', \''.sf($partcat['name']).'\')');
		
		$part_cat_id = mysqli_insert_id($link);
		
		$parts = mysqli_query($link, 'SELECT * FROM product_parts WHERE category=\''.sf($partcat['id']).'\' AND product=\''.sf($product['id']).'\'');
		
		while($part = mysqli_fetch_assoc($parts)) {
			//product_parts
			//product_parts
			//product_parts
			mysqli_query($link, 'INSERT INTO product_parts (`product`, `p_order`, `category`, `name`, `batch_lot`, `variables`) VALUES (\''.sf($product_id).'\', \''.sf($part['p_order']).'\', \''.sf($part_cat_id).'\', \''.sf($part['name']).'\', \''.sf($part['batch_lot']).'\', \''.sf($part['variables']).'\')');
			
		}
		
	}
	
	$steps = mysqli_query($link, 'SELECT * FROM product_steps WHERE product=\''.sf($product['id']).'\'');
	
	while($step = mysqli_fetch_assoc($steps)) {
	    //product_steps
	    //product_steps
	    //product_steps
		mysqli_query($link, 'INSERT INTO product_steps (`product`, `order`, `name`, `parts`, `metadata`, `email_template`) VALUES (\''.sf($product_id).'\', \''.sf($step['order']).'\', \''.sf($step['name']).'\', \''.sf($step['parts']).'\', \''.sf($step['metadata']).'\', \''.sf($step['email_template']).'\')');
		
		$step_id = mysqli_insert_id($link);
		
		$substepq = mysqli_query($link, 'SELECT * FROM product_sub_steps WHERE product=\''.sf($product['id']).'\' AND step = \''.sf($step['id']).'\'');
		
		
		
		while($substep = mysqli_fetch_assoc($substepq)) {
			//product_sub_steps
			//product_sub_steps
			//product_sub_steps
			mysqli_query($link, 'INSERT INTO product_sub_steps (`product`, `s_order`, `step`, `type`, `name`, `part`, `variables`) VALUES (\''.sf($product_id).'\', \''.sf($substep['s_order']).'\', \''.sf($step_id).'\', \''.sf($substep['type']).'\', \''.sf($substep['name']).'\', \''.sf($substep['part']).'\', \''.sf($substep['variables']).'\')');
			
		}
	}
	//echo $_POST['product'];
		$parts = mysqli_query($link, 'SELECT * FROM product_parts WHERE product=\''.sf($_POST['product']).'\'');
		
		while($source = mysqli_fetch_assoc($parts)) {
		    //echo '<br/>'.$source['id'].'<br/>';
			$part_dest = mysqli_query($link, 'SELECT * FROM product_parts WHERE product=\''.sf($product_id).'\' AND name=\''.sf($source['name']).'\'');
		    while($dest = mysqli_fetch_assoc($part_dest)) {
		        //echo '<br/>'.$dest['id'].'<br/>';    
		        $substepq = mysqli_query($link, 'SELECT * FROM product_sub_steps WHERE product=\''.sf($product_id).'\' AND part = \''.sf($source['id']).'\'');
		        if(mysqli_num_rows($substepq) > 0){
		            //echo '<br/>UPDATE product_sub_steps SET part=\''.$dest['id'].'\' WHERE product=\''.$product_id.'\' AND part = \''.$source['id'].'\'<br/>';    
		            mysqli_query($link, 'UPDATE product_sub_steps SET part=\''.sf($dest['id']).'\' WHERE product=\''.sf($product_id).'\' AND part = \''.sf($source['id']).'\'');
		        }
		    }
		}
	
	
	//header('location: '.root().'?page=products');
	echo'<META HTTP-EQUIV="Refresh" Content="0; URL=?page=products">';
}
?>

<form class="form-horizontal" action="" method="post">
<fieldset>

<legend>Copy a Product</legend>


<div class="form-group">
  <label class="col-md-4 control-label" for="product">Product</label>  
  <div class="col-md-4">
  <select id="product" name="product" class="form-control input-md" required="">
  <?
  $products = mysqli_query($link, 'SELECT * FROM products ORDER BY name ASC');
  while($product = mysqli_fetch_assoc($products)) {
		echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
  }
  ?>
  </select>
  <script>
	$(function() {
		$('#product').change(function() {
			
			$('#project_name').val($("#product option:selected").text());
			
		});
	});
	
	</script>
  </div>
</div>



<div class="form-group">
  <label class="col-md-4 control-label" for="new_product_name">New Name</label>  
  <div class="col-md-4">
  <input id="new_product_name" name="new_product_name" placeholder="New Project Name" class="form-control input-md" required="" type="text">
  </div>
</div> 


<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary">Copy Product</button>
  </div>
</div>


</fieldset>
</form>