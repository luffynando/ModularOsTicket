<div class="header-block">
    <h3 class="title"><?php echo __('Manage Your Profile Information'); ?></h3>
    <p class="title-description"><?php echo __(
'Use the forms below to update the information we have on file for your account'
); ?></p>
</div>
<hr>
<form action="profile.php" method="post">
    <?php csrf_token(); ?>
    <?php
    foreach ($user->getForms() as $f) {
        $f->render(['staff' => false]);
    }
    if ($acct = $thisclient->getAccount()) {
        $info=$acct->getInfo();
        $info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
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
                    <?php if ($cfg->getSecondaryLanguages()) { ?>
                        <div class="subtitle-block">
                            <h3 class="subtitle"><?php echo __('Preferred Language'); ?>:</h3>
                        </div>
                        <div class="form-group <?php echo $errors['lang'] ? 'has-error':''; ?>">
                            <?php
                            $langs = Internationalization::getConfiguredSystemLanguages(); ?>
                            <select class="form-control" name="lang">
                                <option value="">&mdash; <?php echo __('Use Browser Preference'); ?> &mdash;</option>
                                <?php foreach($langs as $l) {
                                    $selected = ($info['lang'] == $l['code']) ? 'selected="selected"' : ''; ?>
                                    <option value="<?php echo $l['code']; ?>" <?php echo $selected;
                                    ?>><?php echo Internationalization::getLanguageDescription($l['code']); ?></option>
                                <?php } ?>
                            </select>
                            <?php echo $errors['lang'] ?'<span class="has-error">'.$errors['lang'].'</span>':''; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php if ($acct->isPasswdResetEnabled()) { ?>
    <div class="subtitle-block">
        <h3 class="subtitle"><?php echo __('Access Credentials'); ?></h3>
    </div>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-md-12">
                <div class="card card-block sameheight-item">
                    <?php if (!isset($_SESSION['_client']['reset-token'])) { ?>
                    <div class="form-group <?php echo $errors['cpasswd']?'has-error':''; ?>">
                        <label for="cpasswd"><?php echo __('Current Password'); ?>:</label>
                        <input class="form-control" type="password" size="18" name="cpasswd" value="<?php echo $info['cpasswd']; ?>">
                        <?php echo $errors['cpasswd']?'<span class="has-error">'.$errors['cpasswd'].'</span>':''; ?>
                    </div>
                    <?php } ?>
                    <div class="form-group <?php echo $errors['passwd1']? 'has-error':''; ?>">
                        <label for="passwd1"><?php echo __('New Password'); ?>:</label>
                        <input class="form-control" type="password" size="18" name="passwd1" value="<?php echo $info['passwd1']; ?>">
                        <?php echo $errors['passwd1']?'<span class="has-error">'.$errors['passwd1'].'</span>':''; ?>
                    </div>
                    <div class="form-group <?php echo $errors['passwd2']?'has-error':''; ?>">
                        <label for="passwd2"><?php echo __('Confirm New Password'); ?>:</label>
                        <input class="form-control" type="password" size="18" name="passwd2" value="<?php echo $info['passwd2']; ?>">
                        <?php echo $errors['passwd2']?'<span class="has-error">'.$errors['passwd2'].'</span>':''; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
    <?php } ?>
    <p style="text-align: center;">
        <input class="btn btn-primary" type="submit" value="Update"/>
        <input class="btn btn-info" type="reset" value="Reset"/>
        <input class="btn btn-secondary" type="button" value="Cancel" onclick="javascript:
        window.location.href='index.php';"/>
    </p>
</form>
