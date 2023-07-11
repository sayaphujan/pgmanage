<? if($_GET['error']) { ?>
<div class="alert alert-danger fade in">Login Failed. Please try again</div>
<? } ?>
<form class="form-signin" id="login_form" method="post" action="<?=root()?>exec/login/">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" class="form-control" placeholder="Email address" id="email" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" id="password" required>
        
        <input type="submit" class="btn btn-lg btn-primary btn-block" type="button" id="login_btn" value="Login Now">
 </form>
 
 <script>
 
$(function() {
	
 
});

</script>