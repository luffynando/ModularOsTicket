<section class="section">
    <div class="row">
        <div class="col-xl-8">
            <div class="card sameheight-items">
                <div class="card-block">
        <?php
        $categories = Category::objects()
        ->exclude(Q::any(array(
            'ispublic'=>Category::VISIBILITY_PRIVATE,
            Q::all(array(
                    'faqs__ispublished'=>FAQ::VISIBILITY_PRIVATE,
                    'children__ispublic' => Category::VISIBILITY_PRIVATE,
                    'children__faqs__ispublished'=>FAQ::VISIBILITY_PRIVATE,
                    ))
        )))
        //->annotate(array('faq_count'=>SqlAggregate::COUNT('faqs__ispublished')));
        ->annotate(array('faq_count' => SqlAggregate::COUNT(
                        SqlCase::N()
                        ->when(array(
                                'faqs__ispublished__gt'=> FAQ::VISIBILITY_PRIVATE), 1)
                        ->otherwise(null)
        )))
        ->annotate(array('children_faq_count' => SqlAggregate::COUNT(
                        SqlCase::N()
                        ->when(array(
                                'children__faqs__ispublished__gt'=> FAQ::VISIBILITY_PRIVATE), 1)
                        ->otherwise(null)
        )));

        // ->filter(array('faq_count__gt' => 0));
        if ($categories->exists(true)) { ?>
            <div class="header-block">
                <h3 class="title"><?php echo __('Click on the category to browse FAQs.'); ?></h3>
            </div>
            <div class="card-block">
                <section id="kb">
                <?php
                foreach ($categories as $C) {
                    // Don't show subcategories with parents.
                    if (($p=$C->parent)
                        && ($categories->findFirst(array(
                            'category_id' => $p->getId()))))
                        continue;
                    $count = $C->faq_count + $C->children_faq_count;
                    ?>
                    <section>
                        <div class="card-block row sameheight-items border-bottom">
                            <div class="col-md-2"><i class="fa fa-3x fa-folder-open"></i></div>
                            <div class="col-md-8">
                                <div class="header-block">
                                    <h3 class="title"><?php echo sprintf('<a class="text-primary" href="faq.php?cid=%d">%s %s</a>',
                                    $C->getId(), Format::htmlchars($C->getFullName()),
                                    $count ? "({$count})": ''
                                    ); ?></h3>
                                </div>
                                <div class="card-block">
                                    <?php echo Format::safe_html($C->getLocalDescriptionWithImages()); ?>
                                    <?php
                    if (($subs=$C->getPublicSubCategories())) {
                        echo '<p/><div style="padding-bottom:15px;">';
                        foreach ($subs as $c) {
                            echo sprintf('<div><i class="icon-folder-open"></i>
                            <a href="faq.php?cid=%d">%s (%d)</a></div>',
                            $c->getId(),
                            $c->getLocalName(),
                            $c->faq_count
                            );
                        }
                        echo '</div>';
                    }

                    foreach ($C->faqs
                        ->exclude(array('ispublished'=>FAQ::VISIBILITY_PRIVATE))
                        ->limit(5) as $F) { ?>
                        <div class="popular-faq"><i class="icon-file-alt"></i>
                            <a href="faq.php?id=<?php echo $F->getId(); ?>">
                            <?php echo $F->getLocalQuestion() ?: $F->getQuestion(); ?>
                        </a></div>
                    <?php       } ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php   } ?>
                </section>
            </div>
        <?php
        } else {
            echo __('NO FAQs found');
        }
        ?>      </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card sameheight-items">
                <div class="card-block">
                    <div class="searchbar">
                        <form method="get" action="faq.php">
                            <div class="form-group">
                                <input type="hidden" name="a" value="search"/>
                                <select name="topicId"  class="form-control"
                                onchange="javascript:this.form.submit();">
                                <option value="">—<?php echo __("Browse by Topic"); ?>—</option>
                                <?php
                                $topics = Topic::objects()
                                ->annotate(array('has_faqs'=>SqlAggregate::COUNT('faqs')))
                                ->filter(array('has_faqs__gt'=>0));
                                foreach ($topics as $T) { ?>
                                <option value="<?php echo $T->getId(); ?>"><?php echo $T->getFullName();
                                ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <section>
                        <div class="header-block">
                            <h3 class="title"><?php echo __('Other Resources'); ?></h3>
                        </div>
                        <div class="row sameheight-container">
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
