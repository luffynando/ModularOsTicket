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
    <p class="description"><?php echo __('Thanks for registering for an account.'); ?></p>
</div>
<section class="section">
    <div class="card">
        <div class="card-block">
            
            <p><?php echo __(
            "We've just sent you an email to the address you entered. Please follow the link in the email to confirm your account and gain access to your tickets."
            ); ?>
            </p>
        </div>
    </div>
</section>
<?php } ?>
