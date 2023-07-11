<?php
if($_SESSION['type']!=='admin') 
    exit();

if($_POST['id']) 
{
    $que = 'UPDATE referrals SET 
	                        `name`=\''.sf($_POST['name']).'\'
	                        , `address`=\''.sf($_POST['address']).'\'
	                        , `email`=\''.sf($_POST['email']).'\'
	                        , `phone`=\''.sf($_POST['phone']).'\'
	                        , `contact_method`=\''.sf($_POST['contact_method']).'\'
	                        , `contact_window`=\''.sf($_POST['contact_window']).'\'
	                        , `uspa`=\''.sf($_POST['uspa']).'\'
	                        WHERE id=\''.sf($_POST['id']).'\'';
	                        //echo $que;
	$update = mysqli_query($link, $que);	
	                    
            if($update)
                echo "1";
            else
                echo "0";
	       
}

if($_POST['project_id']){
   // print_r($_POST);
   $qr = mysqli_query( $link, 'SELECT * FROM project_referrals WHERE referral_id=\'' . sf( $_GET['id'] ) . '\' AND referrer_id=\''.sf($_GET['ref_id']).'\'' );
   if(mysqli_num_rows($qr) > 0){
        while($check = mysqli_fetch_assoc($qr)){
            if($check['project_id'] == 0){
                //$que = 'UPDATE project_referrals SET project_id=\''.sf($_POST['project_id']).'\' AND referral_id=\''.sf($_GET['id']).'\' WHERE id=\'' . sf( $check['id'] ) . '\'';
                $que = 'UPDATE `project_referrals` SET `project_id` = \''.sf($_POST['project_id']).'\' WHERE `project_referrals`.`id` =\'' . sf( $check['id'] ) . '\'';
            }else{
             $que = 'INSERT INTO project_referrals 
                            (`project_id`, `referral_id`, `referrer_id`, `customer_id`, `status`) 
                    VALUES (\''.sf($_POST['project_id']).'\'
	                        , \''.sf($_GET['id']).'\'
	                        , \''.sf($_GET['ref_id']).'\'
	                        , \''.sf($_POST['cust_id']).'\'
	                        , \'1\')';   
            }
        }
   }else{
     $que = 'INSERT INTO project_referrals 
                            (`project_id`, `referral_id`, `referrer_id`, `customer_id`, `status`) 
                    VALUES (\''.sf($_POST['project_id']).'\'
	                        , \''.sf($_GET['id']).'\'
	                        , \''.sf($_GET['ref_id']).'\'
	                        , \''.sf($_POST['cust_id']).'\'
	                        , \'1\')';
   }
	                        //echo $que;
	$update = mysqli_query($link, $que);	
	                    
            if($update)
                echo "1";
            else
                echo "0";
}

if($_GET['remove']){
    $que = 'UPDATE project_referrals SET 
	                        project_id = 0
	                        WHERE project_id=\''.sf($_GET['remove']).'\'';
	                        echo $que;
	$update = mysqli_query($link, $que);	
	                    
            if($update)
                echo "1";
            else
                echo "0";
	
}
//USER INFO
$qr = mysqli_query( $link, 'SELECT * FROM referrals WHERE id=\'' . sf( $_GET['id'] ) . '\'' );
$r = mysqli_fetch_assoc( $qr );
?>
<div class="row">
    <div class="col-md-10">
        <h2>Edit Referral User #<?=$r['id']?></h2>
        <hr/>
    </div>
    <div class="col-md-1 text-right project-edit-btn">
        <a href="<?php echo root('/?page=referral&id='.$_GET['ref_id']); ?>"><button class="btn" style="margin-top:-2px;">Back to Referral</button></a>
    </div>
</div>

<div class="row">
   <div class="col-md-3">
        <h3><u>Edit Referral Users Info</u></h3>
        <form id="referral_form">
                <input id="id" name="id" placeholder="ID" class="form-control input-md" type="hidden" value="<?=$_GET['id']?>"><br />
            <div class="text-capitalize">
                <strong>Name:</strong>
                <input id="name" name="name" placeholder="Name" class="form-control input-md" type="text" value="<?=$r['name']?>"><br />
            </div><br/>
            <div class="text-capitalize">
                <strong>Address:</strong>
                <input id="address" name="address" placeholder="Address" class="form-control input-md" type="text" value="<?=$r['address']?>"><br />
            </div><br/>
            <div class="text-capitalize">
                <strong>Email:</strong>
                <input id="email" name="email" placeholder="Email" class="form-control input-md" type="email" value="<?=$r['email']?>"><br />
            </div><br/>
            <div class="text-capitalize">
                <strong>Phone:</strong>
                <input id="phone" name="phone" placeholder="Phone" class="form-control input-md" type="text" value="<?=$r['phone']?>"><br />
            </div><br/>
            <div class="text-capitalize">
                <strong>Contact Method:</strong>
                <input id="contact_method" name="contact_method" placeholder="Contact Method" class="form-control input-md" type="text" value="<?=$r['contact_method']?>"><br />
            </div><br/>
            <div class="text-capitalize">
                <strong>Contact Window:</strong>
                <input id="contact window" name="contact window" placeholder="Contact Window" class="form-control input-md" type="text" value="<?=$r['contact_window']?>"><br />
            </div><br/>
            <div class="text-capitalize">
                <strong>USPA License Number:</strong>
                <input id="uspa" name="uspa" placeholder="Uspa" class="form-control input-md" type="text" value="<?=$r['uspa']?>"><br />
            </div><br/>
            <button type="button" class="btn btn-info" onclick="edit_referral();">Save</button>
        </form>
    </div>
    <div class="col-md-9">
    <?php
    
            $project_option = '<select class="field_update" name="project_id">
    		                        <option value="0">Select Project</option>';
            $check = mysqli_query( $link, 'SELECT * FROM customers WHERE email=\'' . sf( $r['email'] ) . '\' LIMIT 1' );
            if(mysqli_num_rows($check)==0)
            {
                $check_again = mysqli_query( $link, 'SELECT * FROM referral_users WHERE id=\'' . sf( $r['referrer_id'] ) . '\'' );
                $get = mysqli_fetch_assoc($check_again);
                $check = mysqli_query( $link, 'SELECT * FROM customers WHERE email=\'' . sf( $get['email'] ) . '\' LIMIT 1' );    
            }
                $res = mysqli_fetch_assoc($check);
                $que = mysqli_query( $link, 'SELECT *, (SELECT product_steps.name FROM project_steps, product_steps WHERE project_steps.project=projects.id AND project_steps.status=\'Completed\' AND project_steps.step = product_steps.id ORDER BY product_steps.order DESC LIMIT 1) as last_status FROM projects WHERE customer=\'' . sf( $res['id'] ) . '\' AND projects.id NOT IN (SELECT project_id FROM project_referrals WHERE referral_id=\''.sf($_GET['id']).'\') AND projects.customer!=0' );        
                $project = array();
                if(mysqli_num_rows($que)>0){
                    while($row = mysqli_fetch_assoc($que)) 
                    {
                        $project[] = $row;      
                    }   
                }
        		                        
        		    foreach($project as $p)
                	{
                	    if(empty($p['last_status'])){
                	        $p['last_status'] = 'STARTED';
                	    }
                	    $selected = '';
            	        $project_option .= '<option '.$selected.' value="'.$p['id'].'">'.$p['id'].' - '.$p['last_status'].'</option>';
                	}
                    $project_option .= '</select>';
    ?>
        <form id="attach_form">
            <input name="name" type="hidden" value="<?=$r['name']?>">
            <input name="address" type="hidden" value="<?=$r['address']?>">
            <input name="email" type="hidden" value="<?=$r['email']?>">
            <input name="phone" type="hidden" value="<?=$r['phone']?>">
            <input name="contact_method" type="hidden" value="<?=$r['contact_method']?>">
            <input name="contact window" type="hidden" value="<?=$r['contact_window']?>">
            <input name="uspa" type="hidden" value="<?=$r['uspa']?>">
            <input name="cust_id" type="hidden" value="<?=$res['id']?>">
            <?=$project_option;     ?>
            <button type="button" class="btn btn-info" onclick="attach_referral();">Attach Project</button>
        </form>
        <br/><br/>
          <table class="table table-striped table-bordered table-hover dt-responsive nowrap no-footer" cellspacing="0" style="width:100%;">
                <tr>
                    <th width="5%">Order#</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
         <?php
            $query = 'SELECT *
                        , projects.id as project_id
                        , (SELECT product_steps.name FROM project_steps, product_steps WHERE project_steps.project=projects.id AND project_steps.status=\'Completed\' AND project_steps.step = product_steps.id ORDER BY product_steps.order DESC LIMIT 1) as last_status 
                        FROM projects
                        LEFT JOIN project_referrals ON projects.id=project_referrals.project_id
                        WHERE project_referrals.referral_id=\'' . sf( $_GET['id'] ) . '\' ORDER BY projects.id ASC';  
            //echo $query;
            $que = mysqli_query( $link, $query);
            if(mysqli_num_rows($que)>0)
            {
                while($row = mysqli_fetch_assoc($que)) 
                {
                     //print_r($row);
                    if($row['project_id'] == 0){
                        $row['project_id'] = '';
                        $pro['started'] = '';
                        $pro['last_status'] = '';
                    }else{
                        $row['last_status'] = ($row['last_status'] == '' && $row['project_id']>0) ? 'STARTED' : $row['last_status'];
                    }
                                echo '<tr>
                                        <td onclick="document.location=\''.root().'?page=project&id='.$row['project_id'].'\'">'.$row['project_id'].'</td>
                                        <td>'.$row['started'].'</td>
                                        <td>'.strtoupper($row['last_status']).'</td>
                                        <td><button type="button" class="btn btn-danger" onclick="detach_referral('.$row['project_id'].');">Detach Project</button></td>
                                        </tr>
                                    ';
                }
            }
            ?>
          </table>
    </div>
    <script>
		function edit_referral() {
		    $.post('<?=root()?>exec/edit_referral_user/?id=<?=$_GET['id']?>', $('#referral_form').serialize(), function(result){
                if(result){
                    $.notify('Referral have been succesfully updated!', 'success')
                    //location.reload();
                    window.location.href='https://design.peregrinemfginc.com/do/referral/?id='+<?=$_GET['id']?>+'&ref_id='+<?=$_GET['ref_id']?>;    
                }
                else{
                    $.notify('Failed to update project!', 'error')
                }
		    })
		}
		
		function attach_referral() {
		    $.post('<?=root()?>exec/edit_referral_user/?id=<?=$_GET['id']?>&ref_id=<?=$_GET['ref_id']?>', $('#attach_form').serialize(), function(result){
                if(result){
                    $.notify('Project have been succesfully attached!', 'success')
                    //location.reload();
                    window.location.href='https://design.peregrinemfginc.com/do/referral/?id='+<?=$_GET['id']?>+'&ref_id='+<?=$_GET['ref_id']?>;    
                }
                else{
                    $.notify('Failed to attach project!', 'error')
                }
		    })
		}
		
		function detach_referral(id) {
		    if(confirm('Are you sure you want to remove this project from referral?')) {
				
				$.get( '<?=root()?>exec/edit_referral_user/?id=<?=$_GET['id']?>&remove='+id , null, function(result){
                    if(result){
                        $.notify('Project have been succesfully detached!', 'success')
                        location.reload();
                    }
                    else{
                        $.notify('Failed to detach project!', 'error')
                    }
				})
				
			}
		}
	</script>
    <div class="clear"></div>
<br/><br/><br/>
</div>
