<div class="title-block">
    <h3 class="title"><?php echo __('Forgot My Password'); ?></h3>
    <p class="title-description"><?php echo __(
    'Enter your username or email address in the form below and press the <strong>Send Email</strong> button to have a password reset link sent to your email account on file.');
    ?></p>
</div>
<form action="pwreset.php" method="post" id="clientLogin">
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div style="width:50%;display:inline-block"><?php echo __(
                        'We have sent you a reset email to the email address you have on file for your account. If you do not receive the email or cannot reset your password, please submit a ticket to have your account unlocked.'
                        ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
