<h3 class="col-md-10 col-md-offset-2 right-content-10 header">Password Reset</h3>

<div class="col-md-10 col-md-offset-2 right-content-10">

    <form method="POST" action="<?= ($settings->enable_https) ? secure_url('password/reset') : URL::to('password/reset'); ?><?= '/' . $token ?>" accept-charset="UTF-8" class="form-signin">  
            
        <?php if (Session::has('error')): ?>
            <span class="error"><?= trans(Session::get('reason')) ?></span>
        <?php elseif (Session::has('success')): ?>
            <span class="success"><?= Lang::get('lang.email_has_been_set') ?></span>
        <?php endif; ?>

        <p>Enter your email and your new password below to finish resetting your password.</p>
     
        <input name="email" type="text" id="email" class="form-control" placeholder="email">
     
        <input name="password" type="password" id="password" class="form-control" placeholder="password">
     
        <input name="password_confirmation" type="password" id="password_confirmation" class="form-control" placeholder="confirm password">
     
        <input name="token" type="hidden" value="<?= $token ?>"> 
        <input name="_token" type="hidden" value="<?php echo csrf_token(); ?>">    
        <button class="btn btn-lg btn-primary btn-block password-reset-btn" type="submit">Set Your New Password</button>
     
    </form>

</div>