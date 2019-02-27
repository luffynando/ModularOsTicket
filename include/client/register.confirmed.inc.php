<?php if ($content) {
    list($title, $body) = $ost->replaceTemplateVariables(
        array($content->getName(), $content->getBody())); ?>
<div class="header-block">
    <h3 class="title"><?php echo Format::display($title); ?></h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-block">
            <?php
            echo Format::display($body); ?>
        </div>
    </div>
</section>
<?php } else { ?>
<div class="header-block">
    <h3 class="title"><?php echo __('Account Registration'); ?></h3>
    <p class="title-description"><strong><?php echo __('Thanks for registering for an account.'); ?></strong></p>
</div>
<section class="section">
    <div class="card">
        <div class="card-block">
            <p><?php echo __(
            "You've confirmed your email address and successfully activated your account.  You may proceed to check on previously opened tickets or open a new ticket."
            ); ?>
            </p>
            <p><em><?php echo __('Your friendly support center'); ?></em></p>
        </div>
    </div>
</section>
<?php } ?>
