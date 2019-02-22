<?php
// Return if no visible fields
global $thisclient;
if (!$form->hasAnyVisibleFields($thisclient))
    return;

$isCreate = (isset($options['mode']) && $options['mode'] == 'create');
?>
<div class="subtitle-block">
    <h3 class="subtitle"><?php echo Format::htmlchars($form->getTitle()); ?></h3>
</div>
<section class="section">
    <div class="row sameheight-container">
        <div class="col-md-12">
            <div class="card card-block sameheight-item">
                <div class="title-block">
                    <h3 class="title"><?php echo Format::display($form->getInstructions()); ?></h3>
                </div>
                <?php
                // Form fields, each with corresponding errors follows. Fields marked
                // 'private' are not included in the output for clients
                foreach ($form->getFields() as $field) {
                    try {
                        if (!$field->isEnabled())
                        continue;
                    }
                    catch (Exception $e) {
                        // Not connected to a DynamicFormField
                    }
                    if ($isCreate) {
                        if (!$field->isVisibleToUsers() && !$field->isRequiredForUsers())
                            continue;
                    } elseif (!$field->isVisibleToUsers()) {
                        continue;
                    }           
                    ?>
                    <div class="form-group">
                        <?php if (!$field->isBlockLevel()) { ?>
                        <label for="<?php echo $field->getFormName(); ?>">
                            <span class="<?php
                            if ($field->isRequiredForUsers()) echo 'required'; ?>">
                            <?php echo Format::htmlchars($field->getLocal('label')); ?>
                            <?php if ($field->isRequiredForUsers() &&
                            ($field->isEditableToUsers() || $isCreate)) { ?>
                                <span class="error">*</span>
                            <?php }
                            ?></span><?php
                            if ($field->get('hint')) { ?>
                                <br /><em style="color:gray;"><?php
                                echo Format::viewableImages($field->getLocal('hint')); ?></em>
                            <?php
                            } ?>
                        <?php
                        }
                        if ($field->isEditableToUsers() || $isCreate) { ?>
                            </label>
                            <?php
                            $field->render(array('client'=>true));
                            ?><?php
                            foreach ($field->errors() as $e) { ?>
                                <div class="error"><?php echo $e; ?></div>
                            <?php }
                            $field->renderExtras(array('client'=>true));
                        } else {
                            echo sprintf('%s </label>', $val);
                            $val = '';
                            if ($field->value)
                                $val = $field->display($field->value);
                            elseif (($a=$field->getAnswer()))
                                $val = $a->display();
                        }       
                        ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
    
