<?
if($_SESSION['type']!=='admin') exit();

$q = mysqli_query($link, 'SELECT * FROM colors WHERE colors_id=\''.sf($_GET['id']).'\'');
$c = mysqli_fetch_assoc($q);

if($_POST['name']) {
	
	mysqli_query($link, 'UPDATE colors SET `colors_name`=\''.sf($_POST['name']).'\',`colors_hex`=\''.sf($_POST['hex']).'\' WHERE colors_id=\''.sf($_GET['id']).'\'');
	
	echo 'document.location=\''.root().'?page=colors\';';
	exit();
}

if($_GET['delete_color']) {
	mysqli_query($link, 'UPDATE colors SET deleted=\'1\' WHERE colors_id=\''.sf($_GET['delete_color']).'\'');
	
	echo 'alert(\'Color Removed\'); document.location=\''.root().'?page=colors\';';
	exit();
}

?>
<form id="edit_color_form">
		  <br />
		  <strong>Name</strong>
		  <input id="name" name="name" placeholder="Color Name" class="form-control input-md" type="text" value="<?=$c['colors_name']?>"><br />
		  <br />
		  <strong>Hexa Code</strong>
		  <input id="hex" name="hex" placeholder="#000000" class="form-control input-md" type="text" value="<?=$c['colors_hex']?>"><br />
		  <br />

		  <button type="button" class="btn btn-info" onclick="save_color();">Save</button>
		  
		  <button type="button" class="btn btn-danger pull-right" onclick="delete_color();">Delete Color</button>
		  
		  <script>
		  function save_color() {
			$.post('<?=root()?>exec/edit_color/?id=<?=$_GET['id']?>', $('#edit_color_form').serialize(), null, 'script');
		  }
		  
		  function delete_color() {
			if(confirm('Are you absolutely sure you want to delete the color <?=$c['name']?>?')) {
				$.post('<?=root()?>exec/edit_color/?delete_color=<?=$_GET['id']?>', null, null, 'script');
			}
		  }
		  </script>
</form>