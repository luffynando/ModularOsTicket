<?php
$BUTTONS = isset($BUTTONS) ? $BUTTONS : true;
?>
<div class="col-xl-4">
    <?php if ($BUTTONS) { ?>
        <div class="card">
            <div class="card-block">
            <?php
            if ($cfg->getClientRegistrationMode() != 'disabled'
            || !$cfg->isClientLoginRequired()) { ?>
                <a href="open.php" class="btn btn-block btn-lg btn-info-outline">
                    <i class="fa fa-rocket"></i>
                    <span><?php echo __('Open a New Ticket');?></span>
                </a>
            <?php } ?>
                <a href="view.php" class="btn btn-block btn-lg btn-success-outline">
                    <i class="fa fa-clock-o"></i>
                    <span><?php echo __('Check Ticket Status');?></span>
                </a>
            </div>
        </div>
    <?php } ?>
    <?php
    if ($cfg->isKnowledgebaseEnabled()
        && ($faqs = FAQ::getFeatured()->select_related('category')->limit(5))
        && $faqs->all()) { ?>
    <div class="card">
        <div class="card-header">
            <div class="header-block">
                <h3 class="title"><?php echo __('Featured Questions'); ?></h3>
            </div>
        </div>
        <div class="card-block">
                <?php   foreach ($faqs as $F) { ?>
                <div><a href="<?php echo ROOT_PATH; ?>kb/faq.php?id=<?php
                    echo urlencode($F->getId());
                    ?>"><?php echo $F->getLocalQuestion(); ?></a></div>
                <?php   } ?>
        </div>
    </div>
    <?php
        }
    $resources = Page::getActivePages()->filter(array('type'=>'other'));
    if ($resources->all()) { ?>
    <div class="card">
        <div class="card-header">
            <div class="header-block">
                <h3 class="title"><?php echo __('Other Resources'); ?></h3>
            </div>
        </div>
        <div class="card-block">
            <?php   foreach ($resources as $page) { ?>
                <div><a href="<?php echo ROOT_PATH; ?>pages/<?php echo $page->getNameAsSlug();
                ?>"><?php echo $page->getLocalName(); ?></a></div>
            <?php   } ?>
        </div>
    </div>
        <?php
    }
    ?>
</div>

