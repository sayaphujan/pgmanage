<?
if($_SESSION['type']!=='admin') exit();

?>
<div class="floatl">
	<h2 class="form-signin-heading">Inspectors</h2>
</div>

<div class="floatr">
	<button class="btn btn-primary" onclick="document.location='<?=root()?>?page=add_inspector';">Add Inspectors</button>
</div>
<div class="clear"></div>


<table class="table table-striped table-bordered table-hover">
<tr><th width="30%">Name</th><th>E-Mail</th><th>Initial</th><th>Stamp Number</th></tr>

<?

$q = mysqli_query($link, 'SELECT * FROM inspectors WHERE active=\'1\' AND id!=\'2\' ORDER BY name ASC');

while($row = mysqli_fetch_assoc($q)) {
	echo '<tr onclick="document.location=\''.root().'?page=edit_inspector&id='.$row['id'].'\'">
		<td>'.$row['name'].'</td>
		<td>'.$row['email'].'</td>
		<td>'.$row['initial'].'</td>
		<td>'.$row['stamp_number'].'</td>
		</tr>';
}
?>
</table>      
<script>
    $(document).ready(function(){
        <?php
            if($_SESSION['message_content'] != '')
            {
                echo '$.notify("'.$_SESSION['message_content'] .'", "'.$_SESSION['message_type'] .'")';
                
                unset($_SESSION['message_content']);
	            unset($_SESSION['message_type']);
            }
        ?>
    })
</script>
