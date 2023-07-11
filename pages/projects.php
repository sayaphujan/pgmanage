<style>
    .container{
        width: 100%;
    }
</style>
<?
if(!check_access('production')) exit();

if($_GET['save-assignment']) 
{
	$update = mysqli_query($link, 'UPDATE projects SET assignment_notes=\''.sf($_POST['project_assignment_notes']).'\' WHERE id=\''.sf($_GET['save-assignment']).'\'');
    if($update)
    {
        echo "$.notify('Successfully update assignment notes for project!', 'success');";
    }
    else
    {
        echo "$.notify('Failed to update assignment notes for project!', 'error');";
    }
	exit();
}

if(!isset($_GET['status'])) $_GET['status']='started';
if(empty($_GET['sort_by'])) $_GET['sort_by']='pod';

?>
<div class="col-md-2">
	<h2 class="form-signin-heading">Orders</h2>
</div>

<div class="col-md-4" style="padding-left:110px">
	<div class="col-md-2" style="width:auto">
		<strong>Status</strong>
		<br />
		
		<select id="status">
			<option value="">Display All</option>
			<option value="started" <?=($_GET['status']=='started' ? 'selected="selected"' : '')?>>Started</option>
			<option value="completed" <?=($_GET['status']=='completed' ? 'selected="selected"' : '')?>>Completed</option>
		</select>
		
	</div>
    
	<div class="col-md-2" style="width:auto">
		<strong>Location</strong>
		<br />
		
		<select id="location">
			<option value="">Display All</option>	
			<?
			$locations = mysqli_query($link, 'SELECT distinct(location) as location FROM `projects` WHERE location!=\'\'');
			
			while($location = mysqli_fetch_assoc($locations)) {
				echo '<option value="'.$location['location'].'" '.($location['location']==$_GET['location'] ? 'selected="selected"' : '').'>'.$location['location'].'</option>';
			}
			
			?>
			
		</select>
	</div>
</div>

<div class="col-md-4">
    <div class="col-md-6" style="width:auto">
		<strong>Search by</strong>
		<br />
		<select id="cat">
			<option value="">Display All</option>
			<option value="name" <?=($_GET['cat']=='name' ? 'selected="selected"' : '')?>>Product</option>
			<option value="customer_name" <?=($_GET['cat']=='customer_name' ? 'selected="selected"' : '')?>>Client</option>
			<option value="serial" <?=($_GET['cat']=='serial' ? 'selected="selected"' : '')?>>Serial</option>
			<option value="estimated_completion" <?=($_GET['cat']=='estimated_completion' ? 'selected="selected"' : '')?>>Requested Completion</option>
			<option value="pod" <?=($_GET['cat']=='pod' ? 'selected="selected"' : '')?>>Production Cycle</option>
			<option value="last_step" <?=($_GET['cat']=='last_step' ? 'selected="selected"' : '')?>>Current Step</option>
		</select>
	</div>
	<div class="col-md-6" style="width:auto">
        <br />
       <input type="text" placeholder="search" id="livesearch" class="livesearch" value="<?php echo $_GET['search']; ?>">
    </div>
</div>
<script>

	var assignment_input_timeout = null;
	
	function save_assignment(project_id) 
	{
		clearTimeout(assignment_input_timeout);
		assignment_input_timeout = setTimeout(function () {
			$.post('<?php echo root("exec/projects/?save-assignment="); ?>'+project_id, 'project_assignment_notes='+$('#project_assignment_notes_'+project_id).val(), null, 'script');
		}, 500);
	}

	$(function() {
			
			$('#status').change(function() {
				
				document.location='<?=root()?>?page=projects&location=<?=$_GET['location']?>&status='+$('#status').val();
				
			});
			
			
			$('#location').change(function() {
				
				document.location='<?=root()?>?page=projects&status=<?=$_GET['status']?>&location='+$('#location').val();
				
			});
			
			$('#cat').change(function() {
				
				document.location='<?=root()?>?page=projects&location=<?=$_GET['location']?>&status=<?=$_GET['status']?>&search=<?=$_GET['search']?>&cat='+$('#cat').val();
				
			});
			
			var timer = null;
			$('#livesearch').keypress(function() {
			    clearTimeout(timer); 
                timer = setTimeout(doStuff, 1000)
			});
			
			function doStuff()
			{
			    $.notify('Please wait we are applying your search....', 'success');
			    
			    $.get('<?=root()?>exec/projects/?sort_by=<?=$_GET['sort_by']?>&status=<?=$_GET['status']?>&location=<?=$_GET['location']?>&cat=<?=$_GET['cat']?>&search='+$('#livesearch').val(), function(result){
			        $('.container.main-page').html(result)
			    })
			    //$('#data_table').load('<?=root()?>exec/projects/?sort_by=<?=$_GET['sort_by']?>&status=<?=$_GET['status']?>&location=<?=$_GET['location']?>&cat=<?=$_GET['cat']?>&search='+$('#livesearch').val()+' #data_table');
			}
			
			
			
			/*$('.projects-assignment-notes').keyup(function(){
				
				var project_id = $(this).attr('project-id');

				save_assignment(project_id);
				
			});*/
			
			
			
			
	});
</script>

<div class="col-md-12 text-right">
<br />
	
	<button class="btn btn-success" onclick="document.location='<?=root()?>?page=add_project';">New Order</button>
	
</div>
<div class="clear"></div>
<br/><br/>
<div id="data_table" style="width: 98vw; left: 0; position: absolute;">
<!--<table class="table table-striped table-bordered table-hover">-->
    <table class="table table-striped table-bordered table-hover dt-responsive nowrap no-footer" cellspacing="0" style="width:100%;">
<tr>
    <th width="5%"><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=id">ID</a> <?=($_GET['sort_by']=='id' ? '&#x25BC;' : '')?></th>
    <th width="5%">Order ID</th>
	<th width="20%"><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=name">Product</a> <?=($_GET['sort_by']=='name' ? '&#x25BC;' : '')?></th>
	<th width="15%"><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=customer_name">Client</a> <?=($_GET['sort_by']=='customer_name' ? '&#x25BC;' : '')?></th>
	<th width="15%"><a href="?page=projects&status=<?=$_GET['status']?>">Referral/Retailer</a></th>
	<th><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=serial">Serial</a> <?=($_GET['sort_by']=='serial' ? '&#x25BC;' : '')?></th>
	<th><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=status">Project Status</a> <?=($_GET['sort_by']=='status' ? '&#x25BC;' : '')?></th>
	<th><a href="?page=projects&status_sn=<?=$_GET['status']?>&sort_by=not_used_sn">Serial Status</a> <?=($_GET['sort_by']=='status_sn' ? '&#x25BC;' : '')?></th>
	<th><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=estimated_completion">Requested Completion</a> <?=($_GET['sort_by']=='estimated_completion' ? '&#x25BC;' : '')?></th>
	<th><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=pod">Production Cycle</a> <?=($_GET['sort_by']=='pod' ? '&#x25BC;' : '')?></th>
	<th><a href="?page=projects&status=<?=$_GET['status']?>&sort_by=last_step">Current Step</a> <?=($_GET['sort_by']=='last_step' ? '&#x25BC;' : '')?></th>
	<th width="20%">Assignment Notes</th>
</tr>

<?


//$q = mysqli_query($link, 'SELECT projects.*, customers.name as customer_name FROM projects LEFT JOIN customers ON projects.customer = customers.id '.(!empty($_GET['status']) ? 'WHERE status=\''.sf($_GET['status']).'\'' : '').' ORDER BY '.sf($_GET['sort_by']).' ASC');

if($_GET['search']) {
    if($_GET['cat'] == 'name')
    {
        $cat = 'projects.name';
    }
    else if($_GET['cat'] == 'customer_name')
    {
        $cat = 'customers.name';
    }
    else if($_GET['cat'] == 'serial')
    {
        $cat = 'projects.serial';
    }
    else if($_GET['cat'] == 'pod')
    {
        $cat = 'projects.pod';
    }
    else
    {
        $cat = '';
    }
    
	
	if($cat == '')
	{
	    $search_sql = " AND (projects.name LIKE '%".sf($_GET['search'])."%' OR customers.name LIKE '%".sf($_GET['search'])."%' OR projects.serial LIKE '%".sf($_GET['search'])."%' OR projects.payment LIKE '%".sf($_GET['search'])."%' OR projects.notes LIKE '%".sf($_GET['search'])."%' OR projects.pod LIKE '%".sf($_GET['search'])."%' OR projects.colors LIKE '%".sf($_GET['search'])."%' OR projects.metadata LIKE '%".sf($_GET['search'])."%')";
	}
	else
	{
	    $search_sql = "AND (".$cat." LIKE '%".sf($_GET['search'])."%')";
	}
	
}

$query = 'SELECT projects.*, 
            (SELECT product_steps.name 
                FROM project_steps, product_steps 
                WHERE project_steps.project=projects.id 
                  AND project_steps.status=\'Completed\' 
                  AND project_steps.step = product_steps.id 
                ORDER BY product_steps.order DESC LIMIT 1) 
             as last_status, 
             customers.name as customer_name, 
             products.name as product_name
          FROM projects 
          LEFT JOIN 
              customers ON projects.customer = customers.id, 
              products WHERE projects.product = products.id 
                  AND '.(!empty($_GET['status']) ? 'projects.status=\''.sf($_GET['status']).'\'' : 'projects.status!=\'deleted\'').(!empty($_GET['location']) ? ' AND projects.location=\''.sf($_GET['location']).'\'' : '').' '.$search_sql.' ORDER BY '.sf($_GET['sort_by']).' ASC';
//echo $query;
$q = mysqli_query($link, $query);


if(!empty($_SESSION['new_projects'])) {
	$highlight_new = true;
}

while($row = mysqli_fetch_assoc($q)) {
    $row['not_used_sn'] = ($row['not_used_sn'] == '1') ? 'NOT USED' : 'USED';
    
	echo '<tr>
            <td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['id'].'</td>
            <td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['peregrine_id'].'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['product_name'].'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.strtoupper($row['customer_name']).'</td>';
	
              $global_vars = json_decode($row['global_vars'], true);
              $referal_print = false;
              foreach($global_vars as $index => $group) 
              {
                  if($index == 0)
                  {
                      foreach($group['vars'] as $var_name=>$val) 
                      {
                        if($var_name=='Name')
                        {
                            $parent_name = strtoupper($val);
                            
                            if($parent_name != strtoupper($row['customer_name']))
                            {
                                $query = 'SELECT * FROM referrals WHERE referrals.project_id = '.sf($row['id']);
                                //echo $query;
                                $que = mysqli_query($link, $query);
                                $res = mysqli_fetch_assoc($que);
                                
                                if(!empty($res['referrer_id'])) { $res['referrer_id'] = $res['referrer_id']; }else{ $res['referrer_id'] = 0; }
                                $query2 = 'SELECT * FROM referral_users WHERE id = '.sf($res['referrer_id']);
                                //echo $query;
                                $que2 = mysqli_query($link, $query2);
                                $ref = mysqli_fetch_assoc($que2);
                                echo '<td onclick="document.location=\''.root().'?page=referral&id='.$ref['id'].'\'">'.$parent_name.'</td>';
                                
                                $referal_print = true;
                            }
                            else
                            {
                                echo '<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">-</td>';
                                $referal_print = true;
                            }
                        }
                      }
                  }
              }
    
    if(!$referal_print)
    {
        echo '<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">-</td>';
    }
			
	echo'<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['serial'].'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['status'].' '.((!empty($_SESSION['new_projects']) && strtotime($row['started'])>=strtotime($_SESSION['previous_login'])) ? ' (new)' : '').'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['not_used_sn'].'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.date('Y-m-d', strtotime($row['estimated_completion'])).'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['pod'].'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['last_status'].'</td>
			<td style="padding: 0px">';
			
			if(check_access('admin')) {
				echo '<textarea class="projects-assignment-notes" id="project_assignment_notes_'.$row['id'].'" project-id="'.$row['id'].'" onkeyup="save_assignment('.$row['id'].');">'.$row['assignment_notes'].'</textarea>';
			} else {
				echo $row['assignment_notes'];
			}
			
			echo '</td>
		</tr>';
}
?>

</table>      
</div>