<?

if($_POST['name']) {

	mysqli_query($link, 'INSERT INTO colors (`colors_name`,`colors_hex`,`deleted`) VALUES (\''.sf($_POST['name']).'\',\''.sf($_POST['hex']).'\',\'0\')');
	
	$id = mysqli_insert_id($link);
	
	echo 'document.location=\''.root().'?page=colors\'';
	
	exit();

}

?>
<form id="new_color_form">
		  <br />
		  <strong>Name</strong>
		  <input id="name" name="name" placeholder="Color Name" class="form-control input-md" type="text" value=""><br />
		  <br />
		  <strong>Hexa Code</strong>
		  <input id="hex" name="hex" placeholder="#000000" class="form-control input-md" type="text" value=""><br />
		  <br />
		  <button type="button" class="btn btn-info" onclick="add_color();">Add Color</button>
		  
</form>
		  <script>
		  function add_color() {
			$.post('<?=root()?>exec/add_color/?add_color=true', $('#new_color_form').serialize(), null, 'script');
		  }
		  </script>