<?
if($_SESSION['type']!=='admin') exit();
?>
<h1>Add Serial Number</h1>
<br>
<form id="user_form">
         <strong>Serial Number</strong>
		  <input id="serial" name="serial" placeholder="Serial Number" class="form-control input-md" type="text" required="required"><br />
          <strong>Status</strong>
          <select class="field_update" name="not_used_sn">
              <option value="0" <?php if ($u['not_used_sn'] == '0') echo 'selected'; ?>>USED</option>
              <option value="1" <?php if ($u['not_used_sn'] == '1') echo 'selected'; ?>>NOT USED</option>
          </select>

		<br />
		<br />
		<button type="button" class="btn btn-info" onclick="add_sn();">Save</button>

		<script>
		function add_sn() {

		    $.post('<?=root()?>exec/add_lock_sn/?id=<?=$_GET['id']?>', $('#user_form').serialize(), function(result){
		            if(result){
                        $.notify('Serial Number have been succesfully inserted!', 'success')
                    }
                    else{
                        $.notify('Failed to insert Serial Number!', 'error')
                    } 
		    });
		}
		
		</script>
</form>