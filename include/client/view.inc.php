<?php
if(!defined('OSTCLIENTINC') || !$thisclient || !$ticket || !$ticket->checkUserAccess($thisclient)) die('Access Denied!');

$info=($_POST && $errors)?Format::htmlchars($_POST):array();

$dept = $ticket->getDept();

if ($ticket->isClosed() && !$ticket->isReopenable())
    $warn = sprintf(__('%s is marked as closed and cannot be reopened.'), __('This ticket'));

//Making sure we don't leak out internal dept names
if(!$dept || !$dept->isPublic())
    $dept = $cfg->getDefaultDept();

if ($thisclient && $thisclient->isGuest()
    && $cfg->isClientRegistrationEnabled()) { ?>

<div class="alert alert-info" role="alert">
    <div class="row align-middle">
        <div class="col-sm-1 text-center">
            <i class="fa fa-compass fa-2x"></i> 
        </div>
        <div class="col-sm-11">
            <strong><?php echo __('Looking for your other tickets?'); ?></strong>
            <a href="<?php echo ROOT_PATH; ?>login.php?e=<?php
                echo urlencode($thisclient->getEmail());
            ?>" style="text-decoration:underline"><?php echo __('Sign In'); ?></a>
            <?php echo sprintf(__('or %s register for an account %s for the best experience on our help desk.'),
            '<a href="account.php?do=create" style="text-decoration:underline">','</a>'); ?>
        </div>
    </div>     
</div>
<?php } ?>
<section class="section">
    <div class="row sameheight-container">
        <div class="col-md-12">
            <div class="card sameheight-items items">
                <div class="card-header bordered">
                    <div class="header-block">
                            <a class="primary" href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="<?php echo __('Reload'); ?>"><i class="refresh icon-refresh"></i></a>
                            <h3 class="title"><b class="d-none d-md-block">
                            <?php $subject_field = TicketForm::getInstance()->getField('subject');
                            echo $subject_field->display($ticket->getSubject()); ?>
                            </b>
                            &nbsp;#<?php echo $ticket->getNumber(); ?></h3>
                    </div>
                    <div class="header-block pull-right">
                        <a class="btn btn-success-outline" href="tickets.php?a=print&id=<?php
                        echo $ticket->getId(); ?>"><i class="icon-print"></i> <?php echo __('Print'); ?></a>
                        <?php if ($ticket->hasClientEditableFields()
                        // Only ticket owners can edit the ticket details (and other forms)
                        && $thisclient->getId() == $ticket->getUserId()) { ?>
                            <a class="btn btn-primary-outline" href="tickets.php?a=edit&id=<?php
                                echo $ticket->getId(); ?>"><i class="icon-edit"></i> <?php echo __('Edit'); ?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-block">
                    <section>
                        <div class="row sameheight-items">
                            <div class="col-md-6">
                                <div class="subtitle-block">
                                    <h3 class="subtitle"><?php echo __('Basic Ticket Information'); ?></h3>
                                </div>
                                <div class="row">
                                    <b class="col-md-4"><?php echo __('Ticket Status');?>:</b>
                                    <p class="col-md-8"><?php echo ($S = $ticket->getStatus()) ? $S->getLocalName() : ''; ?></p>
                                    <b class="col-md-4"><?php echo __('Department');?>:</b>
                                    <p class="col-md-8"><?php echo Format::htmlchars($dept instanceof Dept ? $dept->getName() : ''); ?></p>
                                    <b class="col-md-4"><?php echo __('Create Date');?>:</b>
                                    <p class="col-md-8"><?php echo Format::datetime($ticket->getCreateDate()); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="subtitle-block">
                                    <h3 class="subtitle"><?php echo __('User Information'); ?></h3>
                                </div>
                                <div class="row">
                                    <b class="col-md-4"><?php echo __('Name');?>:</b>
                                    <p class="col-md-8"><?php echo mb_convert_case(Format::htmlchars($ticket->getName()), MB_CASE_TITLE); ?></p>
                                    <b class="col-md-4"><?php echo __('Email');?>:</b>
                                    <p class="col-md-8"><?php echo Format::htmlchars($ticket->getEmail()); ?></p>
                                    <b class="col-md-4"><?php echo __('Phone');?>:</b>
                                    <p class="col-md-8"><?php echo $ticket->getPhoneNumber(); ?></p>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div class="row sameheight-items">
                            <div class="col-md-12">
                                <!-- Custom Data -->
                                <?php
                                $sections = array();
                                foreach (DynamicFormEntry::forTicket($ticket->getId()) as $i=>$form) {
                                    // Skip core fields shown earlier in the ticket view
                                    $answers = $form->getAnswers()->exclude(Q::any(array(
                                    'field__flags__hasbit' => DynamicFormField::FLAG_EXT_STORED,
                                    'field__name__in' => array('subject', 'priority'),
                                    Q::not(array('field__flags__hasbit' => DynamicFormField::FLAG_CLIENT_VIEW)),
                                    )));
                                    // Skip display of forms without any answers
                                    foreach ($answers as $j=>$a) {
                                        if ($v = $a->display())
                                            $sections[$i][$j] = array($v, $a);
                                    }
                                }
                                foreach ($sections as $i=>$answers) {
                                ?>
                                    <div class="subtitle-block">
                                        <h3 class="subtitle"><?php echo $form->getTitle(); ?></h3>
                                    </div>
                                    <div class="row">
                                    <?php foreach ($answers as $A) {
                                        list($v, $a) = $A; ?>
                                        <b class="col-md-4"><?php echo $a->getField()->get('label'); ?>:</b>
                                        <p class="col-md-8"><?php echo $v; ?></p>
                                    <?php } ?>
                                    </div>
                                <?php
                                } ?>
                            </div>
                        </div>
                    </section>
                    <section>
                        <?php
                        $email = $thisclient->getUserName();
                        $clientId = TicketUser::lookupByEmail($email)->getId();

                        $ticket->getThread()->render(array('M', 'R', 'user_id' => $clientId), array(
                            'mode' => Thread::MODE_CLIENT,
                            'html-id' => 'ticketThread')
                        );
                        ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
<?php if($errors['err']) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $errors['err']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php }elseif($msg) { ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo $msg; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php }elseif($warn) { ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?php echo $warn; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>
<?php 
if (!$ticket->isClosed() || $ticket->isReopenable()) { ?>
    <form id="reply" role="form" action="tickets.php?id=<?php echo $ticket->getId();
        ?>#reply" name="reply" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <div class="title-block">
            <h3 class="title"><?php echo __('Post a Reply');?></h3>
            <p class="title-description"><em><?php echo __('To best assist you, we request that you be specific and detailed'); ?></em></p>
        </div>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="reply">
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-12">
                    <div class="card sameheight-items">
                        <div class="card-block">
                            <div class="form-group <?php echo $errors['message']?'has-error':''; ?>">
                                <textarea name="message" id="message" cols="50" rows="9" wrap="soft"
                                    class="<?php if ($cfg->isRichTextEnabled()) echo 'richtext';
                                    ?> draft form-control" <?php
                                    list($draft, $attrs) = Draft::getDraftAndDataAttrs('ticket.client', $ticket->getId(), $info['message']);
                                    echo $attrs; ?>><?php echo $draft ?: $info['message'];
                                ?></textarea>
                                <?php echo $errors['message']?'<span class="has-error">'.$errors['message'].'</span>':''; ?>
                            </div>
                            <div class="form-group">
                                <?php
                                if ($messageField->isAttachmentsEnabled()) {
                                    print $attachments->render(array('client'=>true));
                                } ?>
                            </div>
                            <?php
                            if ($ticket->isClosed() && $ticket->isReopenable()) { ?>
                                <div class="alert alert-warning" role="alert">
                                    <?php echo __('Ticket will be reopened on message post'); ?>        
                                </div>
                            <?php } ?>
                        </div>
                        <div class="card-footer">
                            <p style="text-align:center">
                                <input class="btn btn-primary" type="submit" value="<?php echo __('Post Reply');?>">
                                <input class="btn btn-info" type="reset" value="<?php echo __('Reset');?>">
                                <input class="btn btn-secondary" type="button" value="<?php echo __('Cancel');?>" onClick="history.go(-1)">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>  
    </form>
<?php
} ?>
<script type="text/javascript">
<?php
// Hover support for all inline images
$urls = array();
foreach (AttachmentFile::objects()->filter(array(
    'attachments__thread_entry__thread__id' => $ticket->getThreadId(),
    'attachments__inline' => true,
)) as $file) {
    $urls[strtolower($file->getKey())] = array(
        'download_url' => $file->getDownloadUrl(['type' => 'H']),
        'filename' => $file->name,
    );
} ?>
showImagesInline(<?php echo JsonDataEncoder::encode($urls); ?>);
</script>
