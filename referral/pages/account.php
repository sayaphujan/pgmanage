<div class="container">
    <div class="row">
        <div class="col-sm-12 pt-5">
            <h1>Peregrine Referral Program</h1>
            <h3 class="pt-3">Need some extra cash? Refer a customer and earn commision!</h3>
            <p class="lead">
                For every customer you refer that makes a completed order through invoicing, you will earn $100 (Visa Gift Card), or $120 in credit on your PMI account with us. Once you create a referral account you can track the progress of your referrals and see how much you have made already. Your info will then auto-fill every time after account creation to save you the hassle. After you submit the referral we'll take it from there.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 pt-5">
            <h2>Your Info</h2>
            <form id="create" action="<?=root('do/create/'); ?>" method="post">
                <div class="form-group">
                    <label for="cname" class="control-label">
                        <strong>Name:</strong>
                    </label>
                

                    <input type="text" value="<?=$_SESSION['cname']; ?>" name="cname" id="cname" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="caddress" class="control-label">
                        <strong>Full Address:</strong>
                    </label>

                    <input type="text" value="<?=$_SESSION['caddress']; ?>" name="caddress" id="caddress" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="cuspa" class="control-label">
                        <strong>USPA License Number:</strong>
                    </label>
                

                    <input type="text" value="<?=$_SESSION['cuspa']; ?>" name="cuspa" id="cuspa" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="cdz" class="control-label">
                        <strong>Drop Zone:</strong>
                    </label>
                

                    <input type="text" value="<?=$_SESSION['cdz']; ?>" name="cdz" id="cdz" autocomplete="off" class="form-control"/>
                </div>
                
                
                <div class="form-group">
                    <label for="cemail" class="control-label">
                        <strong>Email:</strong>
                    </label>
                

                    <input type="email" value="<?=$_SESSION['cemail']; ?>" name="cemail" id="cemail" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="cpassword" class="control-label">
                        <strong>Password:</strong>
                    </label>
                

                    <input type="password" value="" name="cpassword" id="cpassword" autocomplete="off" class="form-control"/>
                </div>
                <br/>
                <p>Preferred method of payment (this can be changed at the beginning of each monthly period)</p>
                <hr/>
                <div class="form-group form-check">
                    <label for="ccpay1" class="form-check-label">
                      <input class="form-check-input" type="radio" name="cppay" id="cppay1" value="card" <? if ($_SESSION['cppay']=='card') echo 'checked="checked"'; ?> /> Mail me a Visa Gift Card to the address above - $100/order completed through invoicing
                    </label>
                </div>
                <div class="form-group form-check">
                    <label for="cppay2" class="form-check-label">
                      <input class="form-check-input" type="radio" name="cppay" id="cppay2" value="credit" <? if ($_SESSION['cppay']=='credit') echo 'checked="checked""'; ?> /> Receive $120/order completed through invoicing in PMI credit towards rig or spare parts
                    </label>
                </div>
                <hr/>
                <button type="submit" class="btn btn-primary" name="submit">Create Account</button>
            </form>
        </div>
    </div>
</div>
<?
$vars = array('cname','caddress','cuspa','cdz','cemail','cppay');
unset_sess($vars);
?>