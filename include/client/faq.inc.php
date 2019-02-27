<?php
if(!defined('OSTCLIENTINC') || !$faq  || !$faq->isPublished()) die('Access Denied');

$category=$faq->getCategory();

?>
<div class="header-block">
    <h3 class="title"><?php echo __('Frequently Asked Question');?></h3>
    <p class="description">
    <div id="breadcrumbs" style="padding-top:2px;">
    <a href="index.php"><?php echo __('All Categories');?></a>
    &raquo; <a href="faq.php?cid=<?php echo $category->getId(); ?>"><?php
    echo $category->getFullName(); ?></a>
    </div>
    </p>
</div>
<section class="section">
    <div class="row">
        <div class="col-xl-8">
            <div class="card sameheight-items">
                <div class="card-block">
                    <div class="header-block border-bottom">
                    <h3 class="title"><?php echo $faq->getLocalQuestion() ?></h3>
                    <p class="title-description"><?php echo sprintf(__('Last Updated %s'),
                    Format::relativeTime(Misc::db2gmtime($faq->getUpdateDate()))); ?></p>        
                    </div>
                    <section><div class="card-block">
                        <?php echo $faq->getLocalAnswerWithImages(); ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>

<div class="col-xl-4">
    <div class="card sameheight-items">
        <div class="card-block">
            <div class="searchbar">
                <form role="form" method="get" action="faq.php">
                    <div class="form-group">
                        <input type="hidden" name="a" value="search"/>
                        <input class="form-control" type="text" name="q" class="search" placeholder="<?php
                            echo __('Search our knowledge base'); ?>"/>
                        <input type="submit" style="display:none" value="search"/>
                    </div>
                </form>
            </div>
            <?php
            if ($attachments = $faq->getLocalAttachments()->all()) { ?>
                <section>
                    <div class="header-block">
                        <h3 class="title"><?php echo __('Attachments');?>:</h3>
                    </div>
                    <div class="card-block">
                        <?php foreach ($attachments as $att) { ?>
                        <div>
                        <a href="<?php echo $att->file->getDownloadUrl(['id' => $att->getId()]);
                        ?>" class="no-pjax">
                        <i class="icon-file"></i>
                        <?php echo Format::htmlchars($att->getFilename()); ?>
                        </a>
                        </div>
                        <?php } ?>
                    </div>
                </section>
            <?php }
            if ($faq->getHelpTopics()->count()) { ?>
                <section>
                    <div class="header-block">
                        <h3 class="title"><?php echo __('Help Topics'); ?></h3>
                    </div>
                    <div class="card-block">
                        <?php foreach ($faq->getHelpTopics() as $T) { ?>
                        <div><?php echo $T->topic->getFullName(); ?></div>
                        <?php } ?>
                    </div>
                </section>
            <?php }
            ?>
        </div>
    </div>
</div>

</div>
</section>
