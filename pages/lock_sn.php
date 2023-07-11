<?php
if($_SESSION['type']!=='admin') 
    exit();
    
$q = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');
$u = mysqli_fetch_assoc($q);

?>
<br/><br/>
<div class="clear"></div>
<form id="user_form">
          <strong>Serial Number</strong>
		  <input id="serial" name="serial" placeholder="Serial Number" class="form-control input-md" type="text" value="<?=$u['serial']?>" required="required" readonly="readonly"><br />
          <strong>Status</strong>
          <select class="field_update" name="not_used_sn">
              <option value="1" <?php if ($u['not_used_sn'] == '1') echo 'selected'; ?>>NOT USED</option>
              <option value="0" <?php if ($u['not_used_sn'] == '0') echo 'selected'; ?>>USED</option>
          </select>
          <br />
		<br />
		<button type="button" class="btn btn-info" onclick="edit_sn();">Save</button>
		<button type="button" class="btn btn-danger" onclick="del_sn();">Delete</button>

		<script>
		function edit_sn() {

		    $.post('<?=root()?>exec/edit_sn_status/?id=<?=$_GET['id']?>', $('#user_form').serialize(), function(result){
		            if(result){
                        $.notify('Serial Number status have been succesfully updated!', 'success')
                    }
                    else{
                        $.notify('Failed to update Serial Number status!', 'error')
                    } 
		    });
		}
		
		function del_sn() {

		    $.post('<?=root()?>exec/del_sn_status/?id=<?=$_GET['id']?>', $('#user_form').serialize(), function(result){
		            if(result){
                        $.notify('Serial Number status have been succesfully deleted!', 'success')
                    }
                    else{
                        $.notify('Failed to delete Serial Number status!', 'error')
                    } 
		    });
		    
		    setTimeout(function() {
              window.location.href = '<?=root();?>?page=serial_number';
            }, 3000);
		}
		
		</script>
</form>