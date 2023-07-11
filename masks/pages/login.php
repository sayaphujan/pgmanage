<div class="container-fluid">
    <div class="h-100 row align-items-center mt-5">
        <div class="col-sm-6 mx-auto">
            <form action="<?=root('do/login/'); ?>" method="post">
                <div class="form-group">
                    <label for="email" class="control-label"><strong>Email:</strong></label>
                    <input type="email" class="form-control" id="cemail" name="cemail" placeholder="Please enter your email..."/>
                </div>
                <? if (!isset($_SESSION['forgot'])) { ?>
                <div class="form-group">
                    <label for="password" class="control-label"><strong>Password:</strong></label>
                    <input type="password" class="form-control" id="cpassword" name="cpassword" autocomplete="off" placeholder="Please enter your password..."/>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <? } ?>
                <button type="submit" class="btn btn-primary" name="forgot">Forgot My Password</button>
            </form>
        </div>
    </div>
</div>
<?
unset($_SESSION['forgot']);
?>