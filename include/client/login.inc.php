<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['luser']?:$_GET['e']);
$passwd=Format::input($_POST['lpasswd']?:$_GET['t']);

$content = Page::lookupByType('banner-client');

if ($content) {
    list($title, $body) = $ost->replaceTemplateVariables(
        array($content->getLocalName(), $content->getLocalBody()));
} else {
    $title = __('Sign In');
    $body = __('To better serve you, we encourage our clients to register for an account and verify the email address we have on record.');
}

?>
<div class="title-block">
    <h3 class="title"><?php echo Format::display($title); ?></h3>
    <p class="title-description"><?php echo Format::display($body); ?></p>
</div>
<section class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-block">
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <form action="login.php" method="post" id="clientLogin" role="form">
                                <?php csrf_token(); ?>
                                <strong><?php echo Format::htmlchars($errors['login']); ?></strong>
                                <div class="form-group">
                                    <label for="username"><?php echo __('Email or Username'); ?></label>
                                    <input id="username" placeholder="<?php echo __('Email or Username'); ?>" type="text" name="luser" size="30" value="<?php echo $email; ?>" class="nowarn form-control">
                                </div>
                                <div class="form-group">
                                    <label for="passwd"><?php echo __('Password'); ?></label>
                                    <input id="passwd" placeholder="<?php echo __('Password'); ?>" type="password" name="lpasswd" size="30" value="<?php echo $passwd; ?>" class="nowarn form-control">
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" value="<?php echo __('Sign In'); ?>">
                                </div>
                                <p>
                                    <?php if ($suggest_pwreset) { ?>
                                            <a style="padding-top:4px;display:inline-block;" href="pwreset.php"><?php echo __('Forgot My Password'); ?></a>
                                    <?php } ?>
                                </p>         
                                <div class="form-group">
                                    <?php
                                    $ext_bks = array();
                                    foreach (UserAuthenticationBackend::allRegistered() as $bk)
                                        if ($bk instanceof ExternalAuthentication)
                                            $ext_bks[] = $bk;
                                    if (count($ext_bks)) {
                                        foreach ($ext_bks as $bk) { ?>
                                        <div class="external-auth"><?php $bk->renderExternalLink(); ?></div><?php
                                        }
                                    } ?>
                                </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                if ($cfg && $cfg->isClientRegistrationEnabled()) {
                                    if (count($ext_bks)) echo '<hr style="width:70%"/>'; ?>
                                    <div style="margin-bottom: 5px">
                                        <?php echo __('Not yet registered?'); ?> <a href="account.php?do=create"><?php echo __('Create an account'); ?></a>
                                    </div>
                                <?php } ?>
                                <div>
                                    <b><?php echo __("I'm an agent"); ?></b> —
                                    <a href="<?php echo ROOT_PATH; ?>scp/"><?php echo __('sign in here'); ?></a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="card-footer">
                    <?php
                    if ($cfg->getClientRegistrationMode() != 'disabled'
                    || !$cfg->isClientLoginRequired()) {
                        echo sprintf(__('If this is your first time contacting us or you\'ve lost the ticket number, please %s open a new ticket %s'),
                        '<a href="open.php">', '</a>');
                    } ?>
                </div>
            </div>
        </div>
    </div>
</section>
