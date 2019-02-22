<?php

if(!defined('OSTCLIENTINC') || !$thisclient || !$ticket || !$ticket->checkUserAccess($thisclient)) die('Access Denied!');

?>
<div class="title-block">
    <h3 class="title"><?php echo sprintf(__('Editing Ticket #%s'), $ticket->getNumber()); ?></h3>
</div>
<section class="section">
    <div class="row sameheight-container">
        <div class="col-md-12">
            <div class="card sameheight-items">
                <form action="tickets.php" method="post">
                    <?php echo csrf_token(); ?>
                    <input type="hidden" name="a" value="edit"/>
                    <input type="hidden" name="id" value="<?php echo Format::htmlchars($_REQUEST['id']); ?>"/>
                    <div class="card-block">
                        <?php if ($forms)
                        foreach ($forms as $form) {
                            $form->render(['staff' => false]);
                        } ?>
                    </div>
                    <div class="card-footer">
                        <p style="text-align: center;">
                            <input class="btn btn-primary" type="submit" value="Update"/>
                            <input class="btn btn-info" type="reset" value="Reset"/>
                            <input class="btn btn-secondary" type="button" value="Cancel" onclick="javascript:
                                window.location.href='index.php';"/>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
