<div class="container-fluid">
    <div class="h-100 row align-items-center mt-5">
        <div class="col-sm-6 mx-auto">
            <form action="<?=root('do/recover/'); ?>" method="post">
                <input type="hidden" value="<?=$_GET['hash']; ?>" id="rhash" name="rhash" />
                <div class="form-group">
                    <label for="email" class="control-label"><strong>New Password:</strong></label>
                    <input type="password" class="form-control" id="npassword" name="npassword" placeholder="Please enter a new password..."/>
                </div>
                <div class="form-group">
                    <label for="password" class="control-label"><strong>Confirm  Password:</strong></label>
                    <input type="password" class="form-control" id="cnpassword" name="cnpassword" autocomplete="off" placeholder="Confirm new password..."/>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>