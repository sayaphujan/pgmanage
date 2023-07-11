<div class="container">
    <div class="row">
        <div class="col-sm-12 pt-5">
            <h1>Your Referrals</h1>
            <p class="lead">
                Below is a list of your referrals you have submitted and their current statuses. When your referral is completed and their rig is paid for, you will see "confirmed" next to their name. Orders that have been placed, but not yet paid for, will show "pending." Referrals that have not yet been completed - order not placed, will show as "unconfirmed."
            </p>
            <div class="text-right"><button type="button" class="btn btn-primary" onclick="location='<?=root('new/'); ?>';">Create Referral</button></div><br />
            <div class="table-responsive-sm pre-scrollable">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Referral Name</th>
                            <th width="20%">Created</th>
                            <th width="10%">Status</th>
                            <th width="15%">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        $chk = mysqli_query($link, 'SELECT *, DATE_FORMAT(created, \'%m/%d/%Y - %l:%i %p\') AS dt FROM referrals WHERE referrer_id=\''.make_safe($_SESSION['rid']).'\' AND deleted=\'\' LIMIT 1');
                        if (mysqli_num_rows($chk) > 0) {
                            while ($row = mysqli_fetch_assoc($chk)) {
                                echo '<tr><td>'.$row['name'].'</td><td class="text-center">'.$row['dt'].'</td><td class="text-center">'.$row['approved'].'</td><td class="text-center"></td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" align="center">No referrals found!</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>