<div class="header-block">
    <h3 class="title"><?php echo __('Frequently Asked Questions');?></h3>
    <p class="title-description"><strong><?php echo __('Search Results'); ?></strong></p>
</div>
<section class="section">
    <div class="row">
        <div class="col-xl-8">
            <div class="card sameheight-items">
                <div class="card-block">
                    <?php
                    if ($faqs->exists(true)) {
                        echo '<div id="faq">'.sprintf(__('%d FAQs matched your search criteria.'),
                        $faqs->count())
                        .'<ol>';
                        foreach ($faqs as $F) {
                            echo sprintf(
                            '<li><a href="faq.php?id=%d" class="previewfaq">%s</a></li>',
                            $F->getId(), $F->getLocalQuestion(), $F->getVisibilityDescription());
                        }
                        echo '</ol></div>';
                    } else {
                        echo '<strong class="faded">'.__('The search did not match any FAQs.').'</strong>';
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
                        <div class="card-block">
                            <div class="header-block">
                                <h3 class="title"><?php echo __('Help Topics'); ?></h3>
                            </div><?php
                            foreach (Topic::objects()
                                ->annotate(array('faqs_count'=>SqlAggregate::count('faqs')))
                                ->filter(array('faqs_count__gt'=>0))
                            as $t) { ?>
                                <div><a href="?topicId=<?php echo urlencode($t->getId()); ?>"
                                ><?php echo $t->getFullName(); ?></a></div>
                            <?php } ?>
                        </div>
                    </section>
                    <section>
                        <div class="card-block">
                            <div class="header-block">
                                <h3 class="title"><?php echo __('Categories'); ?></h3>
                            </div>
                            <?php
                            foreach (Category::objects()
                                ->exclude(Q::any(array('ispublic'=>Category::VISIBILITY_PRIVATE)))
                                ->annotate(array('faqs_count'=>SqlAggregate::count('faqs')))
                                ->filter(array('faqs_count__gt'=>0))
                            as $C) { ?>
                                <div><a href="?cid=<?php echo urlencode($C->getId()); ?>"
                                ><?php echo $C->getLocalName(); ?></a></div>
                            <?php } ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
