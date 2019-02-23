<?php
/*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('client.inc.php');

require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
<section class="section">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-block">
                    <?php
                    if ($cfg && $cfg->isKnowledgebaseEnabled()) { ?>
                    <form method="get" action="kb/faq.php">
                    <div class="row">
                        <div class="col-md-10 form-group">
                            <input type="hidden" name="a" value="search"/>
                            <input type="text" name="q" class="form-control" placeholder="<?php echo __('Search our knowledge base'); ?>"/>
                        </div>
                        <div class="col-md-2 form-group">
                            <button type="submit" class="btn btn-primary-outline"><?php echo __('Search'); ?></button>
                        </div>
                    </div>
                    <hr>
                    </form>
                    <?php } ?>
                    <?php if($cfg && ($page = $cfg->getLandingPage()))
                        echo $page->getBodyWithImages();
                    else
                        echo  '<h1>'.__('Welcome to the Support Center').'</h1>';
                    ?>
                </div>
            </div>
            <div>
            <?php
            if($cfg && $cfg->isKnowledgebaseEnabled()){
                //FIXME: provide ability to feature or select random FAQs ??
            ?>
            <?php
            $cats = Category::getFeatured();
            if ($cats->all()) { ?>
                <h1><?php echo __('Featured Knowledge Base Articles'); ?></h1>
                <?php
            }
            foreach ($cats as $C) { ?>
                <div class="featured-category front-page">
                    <i class="icon-folder-open icon-2x"></i>
                    <div class="category-name">
                        <?php echo $C->getName(); ?>
                    </div>
                    <?php foreach ($C->getTopArticles() as $F) { ?>
                    <div class="article-headline">
                        <div class="article-title"><a href="<?php echo ROOT_PATH;
                        ?>kb/faq.php?id=<?php echo $F->getId(); ?>"><?php
                        echo $F->getQuestion(); ?></a></div>
                        <div class="article-teaser"><?php echo $F->getTeaser(); ?></div>
                    </div>
                    <?php } ?>
                </div>
            <?php
            }
            }
            ?>
            </div>
        </div>
        <?php include CLIENTINC_DIR.'templates/sidebar.tmpl.php'; ?>
    </div>
</section>

<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
