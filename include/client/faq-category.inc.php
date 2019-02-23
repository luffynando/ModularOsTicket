<?php
if(!defined('OSTCLIENTINC') || !$category || !$category->isPublic()) die('Access Denied');
?>
<div class="header-block">
    <h3 class="title"><?php echo $category->getFullName(); ?></h3>
    <p class="title-description"><?php echo Format::safe_html($category->getLocalDescriptionWithImages()); ?></p>
</div>
<section class="section">
    <div class="row">
        <div class="col-xl-8">
            <div class="card sameheight-items">
                <div class="card-block">
                <?php
                if (($subs=$category->getSubCategories(array('public' => true)))) {
                    echo '<section><div class="card-block border-bottom">';
                    foreach ($subs as $c) {
                    echo sprintf('<div><i class="icon-folder-open-alt"></i>
                    <a href="faq.php?cid=%d">%s (%d)</a></div>',
                    $c->getId(),
                    $c->getLocalName(),
                    $c->getNumFAQs()
                    );
                    }
                    echo '</div></section>';
                } ?>
                <?php
                $faqs = FAQ::objects()
                ->filter(array('category'=>$category))
                ->exclude(array('ispublished'=>FAQ::VISIBILITY_PRIVATE))
                ->annotate(array('has_attachments' => SqlAggregate::COUNT(SqlCase::N()
                ->when(array('attachments__inline'=>0), 1)
                ->otherwise(null)
                )))
                ->order_by('-ispublished', 'question');
                if ($faqs->exists(true)) {
                    echo '<section><div class="card-block"><div class="header-block">
                    <h3 class="title">'.__('Frequently Asked Questions').'</h3></div>
                    <div class="card-block" id="faq">
                    <ol>';
                foreach ($faqs as $F) {
                    $attachments=$F->has_attachments?'<span class="Icon file"></span>':'';
                    echo sprintf('
                    <li><a href="faq.php?id=%d" >%s &nbsp;%s</a></li>',
                    $F->getId(),Format::htmlchars($F->question), $attachments);
                }
                echo '  </ol>
                    </div></div></section>';
                } elseif (!$category->children) {
                echo '<strong>'.__('This category does not have any FAQs.').' <a href="index.php">'.__('Back To Index').'</a></strong>';
                }
                ?>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card sameheight-items">
                <div class="card-block">
                    <div class="searchbar">
                        <form method="get" action="faq.php">
                            <div class="form-group">
                                <input type="hidden" name="a" value="search"/>
                                <input type="text" name="q" class="search form-control" placeholder="<?php
                                echo __('Search our knowledge base'); ?>"/>
                                <input type="submit" style="display:none" value="search"/>
                            </div>
                        </form>
                    </div>
                    <section>
                        <div class="header-block">
                            <h3 class="title"><?php echo __('Help Topics'); ?></h3>
                        </div>
                        <?php
                        foreach (Topic::objects()
                        ->filter(array('faqs__faq__category__category_id'=>$category->getId()))
                        ->distinct('topic_id')
                        as $t) { ?>
                            <a href="?topicId=<?php echo urlencode($t->getId()); ?>"
                            ><?php echo $t->getFullName(); ?></a>
                        <?php } ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
