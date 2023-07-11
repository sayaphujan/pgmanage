<?php
if($_SESSION['type']!=='admin') 
    exit();

if($_POST['id']) 
{
    $que = 'UPDATE referral_users SET 
	                        `name`=\''.sf($_POST['name']).'\'
	                        , `address`=\''.sf($_POST['address']).'\'
	                        , `email`=\''.sf($_POST['email']).'\'
	                        , `phone`=\''.sf($_POST['phone']).'\'
	                        , `dz`=\''.sf($_POST['dz']).'\'
	                        , `uspa`=\''.sf($_POST['uspa']).'\'
	                        WHERE id=\''.sf($_POST['id']).'\'';
	                        //echo $que;
	$update = mysqli_query($link, $que);	
	                        
            if($update)
                echo "1";
            else
                echo "0";
	
}
//USER INFO
$qr = mysqli_query( $link, 'SELECT * FROM referral_users WHERE id=\'' . sf( $_GET['id'] ) . '\'' );
$r = mysqli_fetch_assoc( $qr );
?>
<div class="row">
    <div class="col-md-10">
        <h2>Edit Referral #<?=$r['id']?></h2>
        <hr/>
    </div>
    <div class="col-md-1 text-right project-edit-btn">
        <a href="<?php echo root('/?page=referral&id='.$_GET['id']); ?>"><button class="btn" style="margin-top:-2px;">Back to Referral</button></a>
    </div>
</div>

<div class="row">
   <div class="col-md-3">
        <h3><u>Edit Referral Info</u></h3>
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
            <strong>Drop Zone:</strong>
            <input id="dz" name="dz" placeholder="Drop Zone" class="form-control input-md" type="text" value="<?=$r['dz']?>"><br />
        </div><br/>
        <div class="text-capitalize">
            <strong>USPA License Number:</strong>
            <input id="uspa" name="uspa" placeholder="Uspa" class="form-control input-md" type="text" value="<?=$r['uspa']?>" required="required"><br />
        </div><br/>
        <button type="button" class="btn btn-info" onclick="edit_referral();">Save</button>
    </div>
    <div class="col-md-9">
        <?php
                $member = array();
                $member_option = '<select name="cust_id" id="cust_id">
    		                        <option value="0">Select Referral</option>';
    		                        
                $check = mysqli_query( $link, 'SELECT * FROM customers WHERE email NOT IN (SELECT email FROM referrals WHERE referrer_id > 0)' );
                if(mysqli_num_rows($check)>0){
                    while($row = mysqli_fetch_assoc($check)) 
                    {
                        $member[] = $row;      
                    }   
                }
                    foreach($member as $m)
                	{
                	    $selected = '';
                	    $name = strtolower($m['name']);
            	        $member_option .= '<option '.$selected.' value="'.$m['id'].'">'.ucfirst($name).'&nbsp;&nbsp;&lt;'.$m['email'].'&gt;</option>';
                	}
    		    $member_option .= '</select>';
            ?>
            <form id="attach_form">
                <?=$member_option;     ?>
                <button type="button" class="btn btn-info" onclick="attach_referral();">Attach Referral</button>
            </form>
        <br><br>
        <table class="table table-striped table-bordered table-hover dt-responsive nowrap no-footer" cellspacing="0" style="width:100%;">
                <tr>
                    <th width="15%">Referal Name</th>
                    <th width="5%">Order#</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
        
            <?php
                $qu = mysqli_query( $link, 'SELECT *,referrals.id as referral_id FROM referrals LEFT JOIN project_referrals ON referrals.id=project_referrals.referral_id WHERE referrals.referrer_id=\'' . sf( $_GET['id'] ) . '\'' );
                if(mysqli_num_rows($qu)>0)
                {
                    while($row = mysqli_fetch_assoc($qu)) 
                    {
                        //print_r($row);
                        if($row['project_id'] == 0){
                            $row['project_id'] = '';
                            $pro['started'] = '';
                            $pro['last_status'] = '';
                        }else{
                            $que = mysqli_query( $link, 'SELECT *,(SELECT product_steps.name FROM project_steps, product_steps WHERE project_steps.project=projects.id AND project_steps.status=\'Completed\' AND project_steps.step = product_steps.id ORDER BY product_steps.order DESC LIMIT 1) as last_status FROM projects WHERE id=\'' . sf( $row['project_id'] ) . '\'' );        
                            $pro = mysqli_fetch_assoc($que);
                            
                            if($pro['last_status'] == '')
                            {
                                $pro['last_status'] = 'STARTED';
                            }
                        }
                        
                        echo '<tr>
                            <td>'.$row['name'].'</td>
                            <td>'.$row['project_id'].'</td>
                            <td>'.$pro['started'].'</td>
                            <td>'.$pro['last_status'].'</td>
                            <td><button type="button" class="btn btn-danger" onclick="detach_referral('.$row['referral_id'].');">Detach Referral</button></td>
                            </tr>
                        ';
                    }
                }
            ?>
            </table>      
    </div>
    <script>
		function edit_referral() {
		    $.post('<?=root()?>exec/edit_referral/?id=<?=$_GET['id']?>', $('#referral_form').serialize(), function(result){
                if(result){
                    $.notify('Referral have been succesfully updated!', 'success')
                    //location.reload();
                    window.location.href='https://design.peregrinemfginc.com/do/parent/?id='+<?=$_GET['id']?>;    
                }
                else{
                    $.notify('Failed to update project!', 'error')
                }
            })
		}
		
		 function attach_referral() {
		    var cust_id = $('#cust_id').val();
		    data = {'cust_id' : cust_id, 'referrer_id' : <?=$_GET['id']?>}
		    //$.post('<?=root()?>exec/edit_referral/?id=<?=$_GET['id']?>', data, null, 'script');
		    
		    $.post('<?php echo root("do/attach_referral_ajax/"); ?>', data, function(result){
                if(result){
                    $.notify('Referral have been succesfully attached!', 'success')
                    //alert(result);
                    window.location.href='https://design.peregrinemfginc.com/do/referral/?id='+result+'&ref_id='+<?=$_GET['id']?>;    
                }
                else{
                    $.notify('Failed to attach project!', 'error')
                }
		    })
		}
		
		function detach_referral(id) {
		    if(confirm('Are you sure you want to remove this Referral?')) {
				data = {'id' : id}
				$.post( '<?php echo root("do/detach_referral_ajax/"); ?>', data, function(result){
                    if(result){
                        $.notify('Referral have been succesfully detached!', 'success')
                        window.location.href='https://design.peregrinemfginc.com/do/referral_detach/?id='+result+'&ref_id='+<?=$_GET['id']?>;    
                    }
                    else{
                        $.notify('Failed to detach project!', 'error')
                    }
				})
				
			}
		}
	</script>
    </form>
    <div class="clear"></div>
<br/><br/><br/>
</div>
<script>
    $(document).ready(function()
    {
        $('.field_update').change(function()
        {
            var id = $(this).data('id');
            var field = $(this).attr('name');
            var value = $(this).val();
            
            update_value = {'id' : id}
            update_value[field] = value
            
            $.post('<?php echo root("do/update_referral_ajax/"); ?>', update_value, function(result){
                if(result){
                    $.notify('Referral have been succesfully updated!', 'success')
                }
                else{
                    $.notify('Failed to update project!', 'error')
                }
            })
        })
    })
</script>