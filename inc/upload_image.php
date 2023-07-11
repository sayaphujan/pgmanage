<?
function base64_to_png($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb"); 

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp); 

    return $output_file; 
}

$pq = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($_GET['id']).'\'');

$project = mysqli_fetch_assoc($pq);

$project_meta = json_decode($project['metadata'], true);

$pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($project['product']).'\'');

$product = mysqli_fetch_assoc($pq);

//this part is kindof a hack, but it works.
//$check_step_exists = mysqli_query($link, 'SELECT * FROM project_steps WHERE `project`=\''.$project['id'].'\' AND `step`=\''.sf($_GET['get_step']).'\'');
//if(mysqli_num_rows($check_step_exists)==0)  mysqli_query($link, 'INSERT INTO project_steps (`project`, `step`) VALUES (\''.$project['id'].'\', \''.sf($_GET['get_step']).'\')');

$step_q = mysqli_query($link, 'SELECT product_steps.*, project_steps.status, project_steps.started, project_steps.completed, project_steps.metadata as step_values FROM product_steps LEFT JOIN project_steps ON project_steps.step = product_steps.id WHERE product=\''.$product['id'].'\' AND product_steps.id=\''.sf($_GET['get_step']).'\' ORDER BY `order` ASC');

$step = mysqli_fetch_assoc($step_q);

$_GET['is_private'] = isset($_GET['is_private']) ? $_GET['is_private'] : 0;
$query = 'INSERT INTO images (`project`,`step`,`name`,`private`) VALUES (\''.sf($_GET['id']).'\', \''.sf($_GET['get_step']).'\', \''.sf($_FILES['file']['name']).'\', \''.sf($_GET['is_private']).'\')';
//echo $query;

mysqli_query($link, $query);

$image_id = mysqli_insert_id($link);
//handle file upload. Attach to project step

if($_GET['take_picture']==1 && $_POST['base64']) {

	base64_to_png($_POST['base64'], '../media/images/'.$image_id.'.png');
	
	echo "$('#take-picture').modal('hide');
	
	$('#step_images').load('".root()."exec/project_step/?id=".$_GET['id']."&get_step=".$_GET['get_step']."&refresh_images=true');";
	
} else {

	scaleimage($_FILES['file'], 1000, 800, '../media/images/'.$image_id.'.png', true, 'png');

}
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=project_step&id='.$_GET['id'].'&get_step='.$_GET['get_step'].'&refresh_images=true>';

?>