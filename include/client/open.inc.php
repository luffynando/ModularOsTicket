<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhoneNumber());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;

$form = null;
if (!$info['topicId']) {
    if (array_key_exists('topicId',$_GET) && preg_match('/^\d+$/',$_GET['topicId']) && Topic::lookup($_GET['topicId']))
        $info['topicId'] = intval($_GET['topicId']);
    else
        $info['topicId'] = $cfg->getDefaultTopicId();
}

$forms = array();
if ($info['topicId'] && ($topic=Topic::lookup($info['topicId']))) {
    foreach ($topic->getForms() as $F) {
        if (!$F->hasAnyVisibleFields())
            continue;
        if ($_POST) {
            $F = $F->instanciate();
            $F->isValidForClient();
        }
        $forms[] = $F->getForm();
    }
}

?>
<div class="title-block">
    <h3 class="title"><?php echo __('Open a New Ticket');?></h3>
    <p class="title-description"><?php echo __('Please fill in the form below to open a new ticket.');?></p>
</div>
<form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data" role="form">
    <?php csrf_token(); ?>
    <input type="hidden" name="a" value="open">
    <?php
        if (!$thisclient) {
            $uform = UserForm::getUserForm()->getForm($_POST);
            if ($_POST) $uform->isValid();
            $uform->render(array('staff' => false, 'mode' => 'create'));
        }
        else { ?>
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-12">
                    <div class="card card-block sameheight-item">
                        <div class="card-title-block">
                            <h3 class="title"><?php echo __('Email'); ?>:</h3>
                        </div>
                        <section class="section">
                            <p><?php echo $thisclient->getEmail(); ?></p>
                        </section>
                        <div class="card-title-block">
                            <h3 class="title"><?php echo __('Client'); ?>:</h3>
                        </div>
                        <section class="section">
                            <p><?php echo Format::htmlchars($thisclient->getName()); ?></p>
                        </section>
                    </div>
                </div>
            </div>
        </section> 
        <?php } ?>
        <div class="subtitle-block">
            <h3 class="subtitle"><?php echo __('Help Topic'); ?></h3>
        </div>
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-12">
                    <div class="card card-block sameheight-item">
                        <div class="form-group <?php echo $errors['topicId']?'has-error':''; ?>">
                            <select class="form-control" id="topicId" name="topicId" onchange="javascript:
                            var data = $(':input[name]', '#dynamic-form').serialize();
                            $.ajax(
                            'ajax.php/form/help-topic/' + this.value,
                            {
                            data: data,
                            dataType: 'json',
                            success: function(json) {
                                $('#dynamic-form').empty().append(json.html);
                                $(document.head).append(json.media);
                            }
                            });">
                            <option value="" selected="selected">&mdash; <?php echo __('Select a Help Topic');?> &mdash;</option>
                            <?php
                            if($topics=Topic::getPublicHelpTopics()) {
                                foreach($topics as $id =>$name) {
                                    echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
                                }
                            } else { ?>
                                <option value="0" ><?php echo __('General Inquiry');?></option>
                            <?php
                            } ?>
                            </select>
                            <?php echo $errors['topicId'] ? '<span class="has-error">'.$errors['topicId'].'</span>':''; ?> 
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div id="dynamic-form">
            <?php
            $options = array('mode' => 'create');
            foreach ($forms as $form) {
                include(CLIENTINC_DIR . 'templates/dynamic-form.tmpl.php');
            } ?>
        </div>
        <?php
        if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
            if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']=__('Please re-enter the text again');
            ?>
            <section class="section">
                <div class="row sameheight-container">
                    <div class="col-md-12">
                        <div class="card card-block sameheight-item">
                            <div class="title-block">
                                <h3 class="title required"><?php echo __('CAPTCHA Text');?>:</h3>
                            </div>
                            <div class="form-group <?php echo $errors['captcha']?'has-error':''; ?>">
                                <span class="captcha"><img class="img-fluid" src="captcha.php" border="0" align="left"></span>
                                &nbsp;&nbsp;
                                <input class="form-control" id="captcha" type="text" name="captcha" size="6" autocomplete="off">
                                <em><?php echo __('Enter the text shown on the image.');?></em>
                                <?php echo $errors['captcha']?'<span class="has-error">'.$errors['catcha'].'</span>':''; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php
        } ?>
        <p class="buttons" style="text-align:center;">
            <input class="btn btn-primary" type="submit" value="<?php echo __('Create Ticket');?>">
            <input class="btn btn-info" type="reset" name="reset" value="<?php echo __('Reset');?>">
            <input class="btn btn-secondary" type="button" name="cancel" value="<?php echo __('Cancel'); ?>" onclick="javascript:
                $('.richtext').each(function() {
                var redactor = $(this).data('redactor');
                if (redactor && redactor.opts.draftDelete)
                    redactor.draft.deleteDraft();
                });
                window.location.href='index.php';">
        </p>
</form>
