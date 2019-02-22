<?php
$info = $_POST;
if (!isset($info['timezone']))
    $info += array(
        'backend' => null,
    );
if (isset($user) && $user instanceof ClientCreateRequest) {
    $bk = $user->getBackend();
    $info = array_merge($info, array(
        'backend' => $bk::$id,
        'username' => $user->getUsername(),
    ));
}
$info = Format::htmlchars(($errors && $_POST)?$_POST:$info);

?>
<div class="title-block">
    <h3 class="title"><?php echo __('Account Registration'); ?></h3>
    <p class="title-description"><?php echo __('Use the forms below to create or update the information we have on file for your account'); ?></p>
</div>
<form action="account.php" method="post">
    <?php csrf_token(); ?>
    <input type="hidden" name="do" value="<?php echo Format::htmlchars($_REQUEST['do']
    ?: ($info['backend'] ? 'import' :'create')); ?>" />
    <?php
        $cf = $user_form ?: UserForm::getInstance();
        $cf->render(array('staff' => false, 'mode' => 'create'));
    ?>
    <div class="subtitle-block">
        <h3 class="subtitle"><?php echo __('Preferences'); ?></h3>
    </div>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-md-12">
                <div class="card card-block sameheight-item">
                    <div class="title-block">
                        <h3 class="title"><?php echo __('Time Zone');?>:</h3>
                    </div>
                    <div class="form-inline">
                        <?php
                            $TZ_NAME = 'timezone';
                            $TZ_TIMEZONE = $info['timezone'];
                            include INCLUDE_DIR.'staff/templates/timezone.tmpl.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="subtitle-block">
        <h3 class="subtitle"><?php echo __('Access Credentials'); ?></h3>
    </div>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-md-12">
                <div class="card card-block sameheight-item">
                <?php if ($info['backend']) { ?>
                    <div class="title-block">
                        <h3 class="title"><?php echo __('Login With'); ?>:</h3>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="backend" value="<?php echo $info['backend']; ?>"/>
                        <input type="hidden" name="username" value="<?php echo $info['username']; ?>"/>
                        <?php foreach (UserAuthenticationBackend::allRegistered() as $bk) {
                            if ($bk::$id == $info['backend']) {
                                echo $bk->getName();
                                break;
                            }
                        } ?>
                    </div>
                <?php } else { ?>
                    <div class="form-group <?php echo $errors['passwd1']?'has-error':''; ?>">
                        <label for="passwd1"><?php echo __('Create a Password'); ?>:</label>
                        <input class="form-control" type="password" size="18" name="passwd1" value="<?php echo $info['passwd1']; ?>">
                        <?php echo $errors['passwd1']?'<span class="has-error">'.$errors['passwd1'].'</span>':''; ?>
                    </div>
                    <div class="form-group <?php echo $errors['passwd2']?'has-error':''; ?>">
                        <label for="passwd2"><?php echo __('Confirm New Password'); ?>:</label>
                        <input class="form-control" type="password" size="18" name="passwd2" value="<?php echo $info['passwd2']; ?>">
                        <?php echo $errors['passwd2']?'<span class="has-error">'.$errors['passwd2'].'</span>':''; ?>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <p style="text-align: center;">
        <input class="btn btn-primary" type="submit" value="Register"/>
        <input class="btn btn-info" type="button" value="Cancel" onclick="javascript:
        window.location.href='index.php';"/>
    </p>
</form>
<?php if (!isset($info['timezone'])) { ?>
<!-- Auto detect client's timezone where possible -->
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jstz.min.js?d4e240b"></script>
<script type="text/javascript">
$(function() {
    var zone = jstz.determine();
    $('#timezone-dropdown').val(zone.name()).trigger('change');
});
</script>
<?php }
