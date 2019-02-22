<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$ticketid=Format::input($_POST['lticket']?$_POST['lticket']:$_GET['t']);

if ($cfg->isClientEmailVerificationRequired())
    $button = __("Email Access Link");
else
    $button = __("View Ticket");
?>
<div class="title-block">
    <h3 class="title"><?php echo __('Check Ticket Status'); ?></h3>
    <p class="title-description"><?php
echo __('Please provide your email address and a ticket number.');
if ($cfg->isClientEmailVerificationRequired())
    echo ' '.__('An access link will be emailed to you.');
else
    echo ' '.__('This will sign you in to view your ticket.');
?></p>
</div>
<form action="login.php" method="post" id="clientLogin" role="form">
    <?php csrf_token(); ?>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-md-12">
                <div class="card sameheight-items">
                    <div class="card-block">
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div><strong><?php echo Format::htmlchars($errors['login']); ?></strong></div>
                                    <div class="form-group">
                                        <label for="email"><?php echo __('Email Address'); ?>:</label>
                                        <input id="email" placeholder="<?php echo __('e.g. john.doe@osticket.com'); ?>" type="text"
                                        name="lemail" size="30" value="<?php echo $email; ?>" class="nowarn form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="ticketno"><?php echo __('Ticket Number'); ?>:</label>
                                        <input id="ticketno" type="text" name="lticket" placeholder="<?php echo __('e.g. 051243'); ?>"
                                        size="30" value="<?php echo $ticketid; ?>" class="nowarn form-control"></label>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" value="<?php echo $button; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($cfg && $cfg->getClientRegistrationMode() !== 'disabled') { ?>
                                        <?php echo __('Have an account with us?'); ?>
                                        <a href="login.php"><?php echo __('Sign In'); ?></a> <?php
                                        if ($cfg->isClientRegistrationEnabled()) { ?>
                                            <?php echo sprintf(__('or %s register for an account %s to access all your tickets.'),
                                            '<a href="account.php?do=create">','</a>');
                                        }
                                    }?>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="card-footer">
                        <?php
                        if ($cfg->getClientRegistrationMode() != 'disabled'
                        || !$cfg->isClientLoginRequired()) {
                            echo sprintf(
                            __("If this is your first time contacting us or you've lost the ticket number, please %s open a new ticket %s"),
                            '<a href="open.php">','</a>');
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
