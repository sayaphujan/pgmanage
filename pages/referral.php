<?

//USER INFO
$qr = mysqli_query( $link, 'SELECT * FROM referral_users WHERE id=\'' . sf( $_GET['id'] ) . '\'' );
$r = mysqli_fetch_assoc( $qr );
?>
<div class="row">
    <div class="col-md-10">
        <h2>Referral #<?=$r['id']?></h2>
        <hr/>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <h3><u>Referral Info</u></h3>
        <div class="text-capitalize">
            <strong>Name:</strong>
            <?=$r['name']?>
        </div><br/>
        <div class="text-capitalize">
            <strong>Address:</strong>
            <?=$r['address']?>
        </div><br/>
        <div class="text-capitalize">
            <strong>Email:</strong>
            <?=$r['email']?>
        </div><br/>
        <div class="text-capitalize">
            <strong>Phone:</strong>
            <?=$r['phone']?>
        </div><br/>
        <div class="text-capitalize">
            <strong>Drop Zone:</strong>
            <?=$r['dz']?>
        </div><br />
        <div class="text-capitalize">
            <strong>USPA License Number:</strong>
            <?=$r['uspa']?>
        </div><br/>
        <input type="button" class="btn" style="margin-bottom: 5px" onclick="document.location='<?=root()?>?page=edit_referral&id=<?=$_GET['id']?>';" value="Edit">
    </div>
    <div class="col-md-9">
        <br><br>
        <div id="data_table" style="width: 100%">
        <br/><br/>
            <table class="table table-striped table-bordered table-hover dt-responsive nowrap no-footer" cellspacing="0" style="width:100%;">
                <tr>
                    <th width="15%">Referal Name</th>
                    <th width="5%">Order#</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
        
            <?php
            
                $qu = mysqli_query( $link, 'SELECT *
                                                    , referrals.id as referral_id
                                                    , referrals.name as referral_name
                                                    , referrals.email as referral_email
                                                    , referral_users.email as referrer_email
                                            FROM referrals 
                                            LEFT JOIN referral_users ON referrals.referrer_id = referral_users.id
                                            WHERE referrals.referrer_id=\'' . sf( $_GET['id'] ) . '\'' );
                if(mysqli_num_rows($qu)>0)
                {
                    while($row = mysqli_fetch_assoc($qu)) 
                    {
                        $query = "SELECT *
                                        FROM project_referrals 
                                        WHERE referral_id='".sf($row['referral_id'])."' AND referrer_id='".sf($_GET['id'])."'";
                        $check = mysqli_query($link, $query);
                        if(mysqli_num_rows($check)==0)
                        {
                            $project_id=0;
                            $cust_id = 0;
                            
                            //check from referral
                            $check = mysqli_query( $link, 'SELECT * FROM customers WHERE email=\'' . sf( $row['referral_email'] ) . '\'' );
                            $c = mysqli_fetch_assoc($check);
                            $check_project = mysqli_query($link, "SELECT * FROM projects WHERE customer='".sf($c['id'])."' AND customer!=0");
                            if(mysqli_num_rows($check_project) == 0){  
                                //check from referral_users
                                $check = mysqli_query( $link, 'SELECT * FROM customers WHERE email=\'' . sf( $row['referrer_email'] ) . '\'' );
                                $c = mysqli_fetch_assoc($check);
                                $check_project = mysqli_query($link, "SELECT * FROM projects WHERE customer='".sf($c['id'])."'  AND customer!=0");
                                if(mysqli_num_rows($check_project) >0){ 
                                    $cp = mysqli_fetch_assoc($check_project);
                                    $project_id = $cp['id'];
                                    $cust_id = $cp['customer'];
                                }
                            }else{
                                    $cp = mysqli_fetch_assoc($check_project);
                                    $project_id = $cp['id'];
                                    $cust_id = $cp['customer'];
                            }
                            
                            if($project_id!='97'){
                                $insert = mysqli_query($link, "INSERT INTO project_referrals (referrer_id, referral_id, project_id, customer_id, status) VALUES ('".sf($_GET['id'])."', '".sf($row['referral_id'])."', '".sf($project_id)."', '".sf($cust_id)."', 1)");
                            }
                        }
                    }
                    
                    $check = mysqli_query( $link, 'SELECT *
                                                    , referrals.id as referral_id
                                                    , referrals.name as referral_name
                                            FROM referrals 
                                            LEFT JOIN project_referrals ON referrals.id = project_referrals.referral_id
                                            WHERE referrals.referrer_id=\'' . sf( $_GET['id'] ) . '\'' );
                    
                    while($row = mysqli_fetch_assoc($check)) 
                    {
                        $que = mysqli_query( $link, 'SELECT *
                                                            ,(SELECT product_steps.name FROM project_steps, product_steps WHERE project_steps.project=projects.id AND project_steps.status=\'Completed\' AND project_steps.step = product_steps.id ORDER BY product_steps.order DESC LIMIT 1) as last_status 
                                                    FROM projects 
                                                    WHERE id=\'' . sf( $row['project_id'] ) . '\'' );  
                        $pro = mysqli_fetch_assoc($que);
                            $row['project_id']  = ($row['project_id'] == 0) ? '' : $row['project_id'];
                            $row['started']     = $pro['started'];
                            $row['last_status'] = ($pro['last_status'] == '' && $row['project_id']>0) ? 'STARTED' : $pro['last_status'];
                                                        
                                    echo '<tr onclick="document.location=\''.root().'?page=edit_referral_user&id='.$row['referral_id'].'&ref_id='.$_GET['id'].'\'">
                                            <td>'.$row['referral_name'].'</td>
                                            <td>'.$row['project_id'].'</td>
                                            <td>'.$row['started'].'</td>
                                            <td>'.strtoupper($row['last_status']).'</td>
                                            <td></td>
                                            </tr>
                                        ';
                    }
                }
            ?>
            
            </table>      
        </div>
    </div>
    <script>
	</script>
    <div class="clear"></div>
<br/><br/><br/>
</div>