<?
//ajax controlled product option sets



switch($_GET['option']) {
	
	case 'add_product_step':
	    
	$order = ($_GET['i']+1);
	
	mysqli_query($link, 'INSERT INTO product_steps (`product`, `order`, `name`) VALUES (\''.sf($_GET['product']).'\', \''.sf($order).'\', \''.sf($_GET['name']).'\')');
		
	$id = mysqli_insert_id($link);
	
	?>
	<li class="product_step" id="product_step_<?=$id?>">
		<input type="hidden" name="step_id[]" value="<?=$id?>">
		<input type="hidden" name="steps[]" value="<?=$id?>">
		
		<h4>
			Step <?=$_GET['i']+1?> - <input type="text" name="step_name[]" value="<?=$_GET['name']?>"> 
			<small>
				<button type="button" class="btn btn-primary" onclick="add_sub_step(<?=$id?>, 'checkbox')">Add Sub Step</button>
				<button type="button" class="btn" onclick="step_parts(<?=$id?>)">Assign Parts</button>
				<button class="btn btn-info" type="button" onclick="set_email(<?=$id?>)">Completed Email Template</button>
				<button class="btn btn-danger" type="button" onclick="remove_step(<?=$id?>)">Remove Step</button>
			</small>
		</h4>
		<div class="step_inputs" id="product_step_inputs_<?=$id?>"></div>
	</li>
	
	<?
	break;
	
	
	case 'remove_product_step':
		mysqli_query($link, 'DELETE FROM product_steps WHERE id=\''.sf($_GET['id']).'\'');
		?>
		$('#product_step_<?=$_GET['id']?>').remove();
		<?
	break;
	
	case 'step_parts':
		$pq = mysqli_query($link, 'SELECT product_parts.id, product_parts.name, product_parts.p_order, product_part_categories.name as category FROM product_parts, product_part_categories WHERE product_parts.product=\''.sf($_GET['product']).'\' AND product_parts.category = product_part_categories.id ORDER BY product_part_categories.c_order, product_parts.p_order ASC');

		$product_parts = array();
				
		while($part = mysqli_fetch_assoc($pq)) {
			$product_parts[] = array('id'=>$part['id'], 'name'=>$part['name'], 'category'=>$part['category']);
		}
		
		
		$sq = mysqli_query($link, 'SELECT * FROM product_steps WHERE id=\''.sf($_GET['step']).'\'');
		$step = mysqli_fetch_assoc($sq);
		
		$step_parts = json_decode($step['parts'], true);
		
		$select_html = '
		<input type="hidden" name="step" value="'.$_GET['step'].'">
		
		<table class="table" id="docs-table">
		<thead>
			<tr>
				<th width="20%"></td>
				<th>Part</th>
			</tr>
		</thead>
		<tbody>
		';
		foreach($product_parts as $key=>$part) {
			$select_html .= '<tr>
					<td>
						<input type="checkbox" name="parts[]" value="'.$part['id'].'" '.(in_array($part['id'], $step_parts) ? 'checked="checked"' : '').'>
					</td>
					<td>
						'.$part['category'].' - '.$part['name'].'
					</td>
					
				</tr>';
		}
		
		$select_html .=  '</tbody></table>';
		
		echo $select_html;
	break;
	
	case 'save_step_parts':
	mysqli_query($link, 'UPDATE product_steps SET parts=\''.sf(json_encode($_POST['parts'])).'\' WHERE id=\''.sf($_POST['step']).'\'');
	echo "$('#step_parts_modal').modal('hide');";
	break;
	
	
	case 'step_documentation':
		$pq = mysqli_query($link, 'SELECT project_step_documents.* FROM project_step_documents WHERE product=\''.sf($_GET['product']).'\' AND step=\''.sf($_GET['step']).'\' AND sub_step=\''.sf($_GET['sub_step']).'\' ');
		
		?>
		
		
		<style>
		#holder { border: 10px dashed #ccc; width: 100px; min-height: 100px; margin: 20px auto;}
		#holder.hover { border: 10px dashed #0c0; }
		#holder img { display: block; margin: 10px auto; }
		#holder p { margin: 10px; font-size: 14px; }
		progress { width: 100%; }
		progress:after { content: '%'; }
		.fail { background: #c00; padding: 2px; color: #fff; }
		.hidden { display: none !important;}
		</style>
		<article>
		  <div id="holder">
		  </div> 
		  <p id="upload" class="hidden"><label>Drag & drop not supported, but you can still upload via this input field:<br><input type="file"></label></p>
		  <p id="filereader">File API & FileReader API not supported</p>
		  <p id="formdata">XHR2's FormData is not supported</p>
		  <p id="progress">XHR2's upload progress isn't supported</p>
		  <p>Upload progress: <progress id="uploadprogress" max="100" value="0">0</progress></p>
		  <p>Drag a video or PDF to the square above to upload new documentation.</p>
		</article>
		<script>
		var holder = document.getElementById('holder'),
		    tests = {
		      filereader: typeof FileReader != 'undefined',
		      dnd: 'draggable' in document.createElement('span'),
		      formdata: !!window.FormData,
		      progress: "upload" in new XMLHttpRequest
		    }, 
		    support = {
		      filereader: document.getElementById('filereader'),
		      formdata: document.getElementById('formdata'),
		      progress: document.getElementById('progress')
		    },
		    acceptedTypes = {
		      'image/png': true,
		      'image/jpeg': true,
		      'image/gif': true,
		      'video/mp4' : true,
		      'video/mov' : true
		    },
		    progress = document.getElementById('uploadprogress'),
		    fileupload = document.getElementById('upload');
		
		"filereader formdata progress".split(' ').forEach(function (api) {
		  if (tests[api] === false) {
		    support[api].className = 'fail';
		  } else {
		    support[api].className = 'hidden';
		  }
		});
		
		function previewfile(file) {
		  /*if (tests.filereader === true && acceptedTypes[file.type] === true) {
		    var reader = new FileReader();
		    reader.onload = function (event) {
		      var image = new Image();
		      image.src = event.target.result;
		      image.width = 250; // a fake resize
		      holder.appendChild(image);
		    };
		
		    reader.readAsDataURL(file);
		  }  else {
		    holder.innerHTML += '<p>Uploaded ' + file.name + ' ' + (file.size ? (file.size/1024|0) + 'K' : '');
		   // $('#step_documentation_modal').modal('hide');
		    console.log(file);
		  }*/
		  
		  //console.log('Uploaded file');
		}
		
		function readfiles(files) {
		    
		    var formData = tests.formdata ? new FormData() : null;
		    for (var i = 0; i < files.length; i++) {
		      if (tests.formdata) formData.append('file', files[i]);
		      previewfile(files[i]);
		    }
		
		    // now post a new XHR request
		    if (tests.formdata) {
		      var xhr = new XMLHttpRequest();
		      xhr.open('POST', 'exec/product_options/?option=upload_step_documentation&product=<?=$_GET['product']?>&step=<?=$_GET['step']?>&sub_step=<?=$_GET['sub_step']?>&order=<?=$_GET['order']?>');
		      xhr.onload = function() {
		        progress.value = progress.innerHTML = 100;
		      };
		
		      if (tests.progress) {
		        xhr.upload.onprogress = function (event) {
		          if (event.lengthComputable) {
		            var complete = (event.loaded / event.total * 100 | 0);
		            progress.value = progress.innerHTML = complete;
					//console.log(complete);
					if(complete==100) {
						console.log(complete);
						step_documentation(<?=$_GET['step']?>);
					}
		          }
		        }
		      }
		
		      xhr.send(formData);
		    }
		}
		
		if (tests.dnd) { 
		  holder.ondragover = function () { this.className = 'hover'; return false; };
		  holder.ondragend = function () { this.className = ''; return false; };
		  holder.ondrop = function (e) {
		    this.className = '';
		    e.preventDefault();
		    readfiles(e.dataTransfer.files);
		  }
		} else {
		  fileupload.className = 'hidden';
		  fileupload.querySelector('input').onchange = function () {
		    readfiles(this.files);
		  };
		}
		
		</script>
		
		
		
		
		<?
		
		$html = '
		<input type="hidden" name="step" value="'.$_GET['step'].'">
		
		<table class="table">
		<thead>
			<tr>
				<th width="80%">Name</td>
				<th></th>
			</tr>
		</thead>
		<tbody>
		';
		while($doc = mysqli_fetch_assoc($pq)) {
			$html .= '<tr id="doc-id-'.$doc['id'].'">
					<td>
						'.$doc['name'].'
					</td>
					<td>
						<button class="btn btn-danger" type="button" onclick="remove_doc('.$doc['id'].', '.$_GET['step'].');">Delete</button>
					</td>
					
				</tr>';
		}
		
		$html .=  '</tbody></table>';
		
		echo $html;
	break;
	
	case 'upload_step_documentation':
	
		if(!empty($_FILES['file']['name'])) {
			
			$ext = 'mp4';
			
			if(stripos($_FILES['file']['name'],'jpg')) {
				$ext = 'jpg';
			}
			
			if(stripos($_FILES['file']['name'],'png')) {
				$ext = 'png';
			}
			
			if(stripos($_FILES['file']['name'],'pdf')) {
				$ext = 'pdf';
			}
			
			echo 'INSERT INTO project_step_documents (`product`, `step`, `sub_step`, `name`, `m_order`, `type`) VALUES (\''.sf($_GET['product']).'\', \''.sf($_GET['step']).'\', \''.sf($_GET['sub_step']).'\', \''.sf($_FILES['file']['name']).'\', \''.sf($_GET['order']).'\',  \''.sf($ext).'\')';
			mysqli_query($link, 'INSERT INTO project_step_documents (`product`, `step`, `sub_step`, `name`, `m_order`, `type`) VALUES (\''.sf($_GET['product']).'\', \''.sf($_GET['step']).'\', \''.sf($_GET['sub_step']).'\', \''.sf($_FILES['file']['name']).'\',  \''.sf($_GET['order']).'\', \''.sf($ext).'\')');
			
			$id = mysqli_insert_id($link);
			
			move_uploaded_file($_FILES['file']['tmp_name'], '../media/documentation/'.$id.'.'.$ext);
		}

	break;
	
	case 'remove_step_documentation':
	
		mysqli_query($link, 'DELETE FROM project_step_documents WHERE id=\''.sf($_GET['delete']).'\'');
		?>
		$('#step_documentation_'.$_GET['order']).remove();
	    <?
	break;
	
	case 'add_product_sub_step':
	
		$pq = mysqli_query($link, 'SELECT product_parts.id, product_parts.name, product_parts.p_order, product_part_categories.name as category FROM product_parts, product_part_categories WHERE product_parts.product=\''.sf($_GET['product']).'\' AND product_parts.category = product_part_categories.id ORDER BY product_part_categories.c_order, product_parts.p_order ASC');

		$product_parts = array();
				
		while($part = mysqli_fetch_assoc($pq)) {
			$product_parts[] = array('id'=>$part['id'], 'name'=>$part['name'], 'category'=>$part['category']);
		}


		$select_html = '<option value="">Select a part (optional)</option>';
		foreach($product_parts as $key=>$part) {
			$select_html .= '<option value="'.$part['id'].'" '.($part['id']==$selected_part ? 'selected="selected"' : '').'>'.$part['category'].' - '.$part['name'].'</option>';
		}
	
		
		mysqli_query($link, 'INSERT INTO product_sub_steps (product, step, type) VALUES (\''.sf($_GET['product']).'\', \''.sf($_GET['step']).'\', \''.sf($_GET['type']).'\')');
			
		$id = mysqli_insert_id($link);
		echo '<div class="step_input" id="sub_step_'.$id.'">
			<input type="hidden" name="step_variable_id_'.$_GET['step'].'[]" value="'.$id.'">';
		
		if($_GET['type']=='checkbox') {
			
				echo '
				<input type="hidden" name="step_variable_type_'.$_GET['step'].'[]" value="checkbox">
				
				<div>
					<div class="col-md-4">
						<label class="control-label">Checkbox Input</label> &nbsp; [<a href="javascript: ;" onclick="remove_sub_step('.$id.');">remove</a>]
						<br>
						<input name="step_variable_'.$_GET['step'].'[]" placeholder="Variable Name" class="form-control input-md" required="" type="text" value="">
					</div>
					
					<div class="col-md-4">
						<label class="control-label">Paired Part</label>
						<br>
						
						<select name="step_variable_part_'.$_GET['step'].'[]" placeholder="Part" class="form-control input-md parts-select" required="" type="text">
							'.$select_html.'
						</select>
					</div>
					
					
					
				</div>
				<div class="clear"></div>
					';
		}
		
		if($_GET['type']=='text') {
			echo '				
				<input type="hidden" name="step_variable_type_'.$_GET['step'].'[]" value="text">
				
				<div>
					<div class="col-md-4">
						<label class="control-label">Text Input</label> &nbsp; [<a href="javascript: ;" onclick="remove_sub_step('.$id.');">remove</a>]
						<br>
						<input name="step_variable_'.$_GET['step'].'[]" placeholder="Variable Name" class="form-control input-md" required="" type="text" value="">
					</div>
					
					<div class="col-md-4">
						<label class="control-label">Paired Part</label>
						<br>
						
						<select name="step_variable_part_'.$_GET['step'].'[]" placeholder="Part" class="form-control input-md parts-select" required="" type="text">
							'.$select_html.'
						</select>
					</div>
					
					
					
				</div>
				<div class="clear"></div>
				
				
			';
		}
		echo '</div>';
					
	break;
	
	case 'remove_sub_step':
	
	mysqli_query($link, 'DELETE FROM product_sub_steps WHERE id=\''.sf($_GET['id']).'\'');
		?>
		$('#sub_step_<?=$_GET['id']?>').remove();
		<?
	
	break;
	
	case 'add_part_category':
	
	echo 'INSERT INTO product_part_categories (product, name) VALUES (\''.sf($_GET['product']).'\', \''.sf($_GET['name']).'\')';
	
	mysqli_query($link, 'INSERT INTO product_part_categories (product, name) VALUES (\''.sf($_GET['product']).'\', \''.sf($_GET['name']).'\')');
		
	$id = mysqli_insert_id($link);
	
	?>
	<li class="product_part" id="part_category_<?=$id?>">
		<input type="hidden" name="categories[]" value="<?=$id?>">
			<h4>Category <input type="text" name="category_name[]" value="<?=$_GET['name']?>">
				<small>
					<button type="button" class="btn" onclick="add_part(this, <?=$id?>)">Add Part</button>
					<button class="btn btn-danger" type="button" onclick="remove_part_category(<?=$id?>)">Remove Category</button>
				</small>
			</h4>
			<div class="part_inputs" id="part_inputs_<?=$id?>"></div>
			<hr></hr>
			<div class="clear"></div>
	</li>
	
	<?
	break;
	
	case 'remove_part_category':
	
		mysqli_query($link, 'DELETE FROM product_part_categories WHERE id=\''.sf($_GET['id']).'\'');
		?>
		$('#part_category_<?=$_GET['id']?>').remove();
		<?
	
	break;

	case 'add_part':
	
	mysqli_query($link, 'INSERT INTO product_parts (product, category, name) VALUES (\''.sf($_GET['product']).'\', \''.sf($_GET['id']).'\', \''.sf($_GET['name']).'\')');
		
	$id = mysqli_insert_id($link);
	
	?>
	<div class="part_input col-md-12" id="part_<?=$id?>">
		<input type="hidden" name="part[]" value="<?=$id?>">
		<div class="col-md-4">
			<label class="control-label">Part Name</label> &nbsp; [<a href="javascript: ;" onclick="remove_part('<?=$id?>');">remove</a>]
			<input name="part_name_<?=$id?>" placeholder="Part Name" class="form-control input-md" required="" type="text" value="">
		</div>
		<div class="col-md-4">
			<br />
			Require Batch Lot Number: <input type="checkbox" value="1" name="batch_lot_required_<?=$id?>">
		</div>
		<div class="col-md-4">
			<strong>Available Variables</strong> [<a href="javascript: ;" onclick="add_part_variable(<?=$id?>);">Add</a>]<br />
			
			
			<div id="part_variables_<?=$id?>"><?
			echo add_part_variable_html($id, 'Material');
			echo add_part_variable_html($id, 'Color');
			echo add_part_variable_html($id, 'Size');
			echo add_part_variable_html($id, 'Embroidery');
			echo add_part_variable_html($id, 'Notes');
			
			?></div>
		</div>
		
		
		
	</div>
	
	<?
	break;
	
	case 'remove_part':
	
	    echo 'DELETE FROM product_parts WHERE id=\''.sf($_GET['part']).'\'';
	    
		mysqli_query($link, 'DELETE FROM product_parts WHERE id=\''.sf($_GET['part']).'\'');
	
	break;
	
	//need to create some buffers and mgmt ui
	case 'add_part_variable':
	
	echo add_part_variable_html($_GET['id']);
	
	break;
	
	case 'add_global_var_group':
		
		$id = uni_id();
		
		?>
		
		<li class="product_vars" id="product_var_group_<?=$id?>">
		<input type="hidden" name="global_var_group[]" value="<?=$id?>">
			<h4>Group <input type="text" name="var_group_name_<?=$id?>[]" value="">
				<small>
					<button type="button" class="btn" onclick="add_global_var(<?=$id?>)">Add Variable</button>
					<button class="btn btn-danger" type="button" onclick="remove_global_var_group(<?=$id?>)">Remove Category</button>
				</small>
			</h4>
			<div class="part_inputs" id="var_inputs_<?=$id?>"></div>
			<hr></hr>
			<div class="clear"></div>
		</li>
		
		<?
	
	break;
	
	case 'add_global_var':
		$id = uni_id();
		?>
		
		<div class="global_var_input col-md-12" id="global_var_<?=$id?>">
			<div class="col-md-6">
				<label class="control-label">Variable Name</label> &nbsp; [<a href="javascript: ;" onclick="remove_global_var('<?=$id?>');">remove</a>]
				<input name="global_var_name_<?=$_GET['id']?>[]" placeholder="Variable Name" class="form-control input-md" required="" type="text" value="">
			</div>
		</div>
		
		<?
	break;
	
    case 'remove_global_var_group':
	    $que = mysqli_query($link, 'SELECT global_vars FROM products WHERE id=\''.sf($_GET['id']).'\'');
	    $res = mysqli_fetch_assoc($que);
        $var_parts = json_decode($res['global_vars'], true);
        
        if(is_array($var_parts)){
    		foreach($var_parts as $key=>$var){
    
    		    if($var['name'] == $_GET['name']){
    		      unset($var_parts[$key]);  
    		    }
    		    
    		}
            array_values($var_parts);    
        }
		
		print_r($var_parts);
		echo 'UPDATE products SET global_vars=\''.json_encode($var_parts).'\' WHERE id=\''.sf($_GET['id']).'\'';
		 $del = 'UPDATE products SET global_vars=\''.json_encode($var_parts).'\' WHERE id=\''.sf($_GET['id']).'\'';
		 $que = mysqli_query($link, $del);
		 
	break;
	
	case 'del_part_variable':
	    $que = mysqli_query($link, 'SELECT variables FROM product_parts WHERE id=\''.sf($_GET['id']).'\'');
	    $res = mysqli_fetch_assoc($que);
        $var_parts = json_decode($res['variables'], true);
        
        if(is_array($var_parts)){
    		foreach($var_parts as $key=>$var){
    
    		    if($var['name'] == $_GET['name']){
    		      unset($var_parts[$key]);  
    		    }
    		    
    		}
    		array_values($var_parts);
        }
		
		print_r($var_parts);
		echo 'UPDATE product_parts SET variables=\''.json_encode($var_parts).'\' WHERE id=\''.sf($_GET['id']).'\'';
		 $del = 'UPDATE product_parts SET variables=\''.json_encode($var_parts).'\' WHERE id=\''.sf($_GET['id']).'\'';
		 $que = mysqli_query($link, $del);
		 ?>
		  $('#part_variables_'+<?=$_GET['id']?>+'_'+<?=$_GET['order']?>).remove();
		<?
	break;

}


function add_part_variable_html($id, $var='') {

	$html = '<div class="row">
	<input name="part_variable_name" type="hidden"> 
		<div class="row">
			<div class="col-md-3">
				<input name="part_variable_name_'.$id.'[]" type="text" placeholder="Part Variable Name" style="width: auto;" value="'.$var.'">
			</div>
			<div class="col-md-4">
				<input name="part_variable_apiname_'.$id.'[]" type="text" placeholder="API matching name" style="width: auto;">
			</div>
		</div>
	</div>';
	
	return $html;

}


?>