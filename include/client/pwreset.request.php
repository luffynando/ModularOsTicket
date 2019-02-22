<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$userid=Format::input($_POST['userid']);
?>
<div class="title-block">
    <h3 class="title"><?php echo __('Forgot My Password'); ?></h3>
    <p class="title-description"><?php echo __(
    'Enter your username or email address in the form below and press the <strong>Send Email</strong> button to have a password reset link sent to your email account on file.');
    ?>  
    </p>
</div>
<form action="pwreset.php" method="post" id="clientLogin">
    <?php csrf_token(); ?>
    <input type="hidden" name="do" value="sendmail"/>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="title-block">
                            <h3 class="title"><strong><?php echo Format::htmlchars($banner); ?></strong></h3>
                        </div>
                        <div class="form-group">
                            <label for="username"><?php echo __('Username'); ?>:</label>
                            <input class="form-control" id="username" type="text" name="userid" size="30" value="<?php echo $userid; ?>">
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="<?php echo __('Send Email'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
