<?
if ( $_SESSION[ 'type' ] !== 'admin' )exit();
?>
<div class="floatl">
    <h2 class="form-signin-heading">Customer Referrals</h2>
</div>
<div class="floatr">
    <button class="btn btn-primary" onclick="document.location='<?=root()?>?page=referral_users';">Manage Referral Users</button>
</div>
<div class="clear"></div>
<table class="table table-striped table-bordered table-hover">
    <tr>
        <th width="30%" class="text-center">Name</th>
        <th class="text-center">Contact</th>
        <th class="text-center">Join Date</th>
        <th class="text-center">Referrals</th>
        <th class="text-center">Code</th>
        <th class="text-center">Credits</th>
        <th class="text-center">Payout</th>
        <th class="text-center">Status</th>
    </tr>
    <?

    $q = mysqli_query($link, 'SELECT (SELECT COUNT(id) FROM `referrals` WHERE referrer_id=referral_users.id) as referal, referral_users.id as id_referrer, referral_users.name as referrer, referral_users.email as email_referrer, referral_users.* FROM referral_users LEFT JOIN referrals ON referrals.referrer_id = referral_users.id  WHERE referral_users.deleted=\'\' GROUP BY referral_users.id ORDER BY referral_users.id DESC');
    while($row = mysqli_fetch_assoc($q)) {
            
        if(empty($row['referal_code'])){
           
            $row['referrer'] = str_replace(' ', '%20', $row['referrer']);
            $request = "https://projects.ndevix.com/pgdesign_dev/do/get_refcode/?email=".$row['email_referrer']."&name=".$row['referrer'];
//echo $request;
            // Generate curl request
            $session = curl_init($request);
            curl_setopt ($session, CURLOPT_POST, true);
            curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
            curl_setopt($session, CURLOPT_HEADER, 0);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            
            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            
            $order_data = json_decode($response, true);
            
            //echo $request;
            //echo"<pre>";
            //print_r($order_data);
            //echo"</pre>";
            
            if($order_data['payout'] == 1){
                $order_data['payout'] = 100;
            }else if($order_data['payout'] == 2){
                $order_data['payout'] = 120;
            }

            mysqli_query($link,'UPDATE referral_users SET `address`=\''.sf($order_data['address']).'\', `dz`=\''.sf($order_data['dz']).'\',`payment`=\''.sf($order_data['payment']).'\',`credits`=\''.sf($order_data['payout']).'\',`referal_code`=\''.sf($order_data['referal_code']).'\', `phone`=\''.sf($order_data['phone']).'\'  WHERE id=\''.sf($row['id_referrer']).'\'');
            $row['referal_code'] = $order_data['referal_code'];
            $row['payment']      = $order_data['payment'];
            $row['credit']       = $order_data['payout'];
        }
        
        if($row['status'] == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        
        if($row['payment'] == 'card'){
            $payout = 'Visa';
        }else{
            $payout = 'Credits';
        }
        
        echo '
        <tr onclick="document.location=\''.root().'?page=referral&id='.$row['id'].'\'">
        <td class="text-capitalize">'.$row['name'].'</td>
        <td><strong>'.$row['email'].'</strong><br />'.$row['phone'].'</td>
        <td class="text-center">'.$row['created'].'</td>
        <td class="text-center">'.$row['referal'].'</td>
        <td class="text-center">'.$row['referal_code'].'</td>
        <td class="text-center">'.$row['credits'].'</td>
        <td class="text-center">'.$payout.'</td>
        <td class="text-center">'.$status.'</td>
        </tr>';
    }
    ?>
</table>