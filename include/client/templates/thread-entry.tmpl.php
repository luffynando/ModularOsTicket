<?php
global $cfg;
$entryTypes = ThreadEntry::getTypes();
$user = $entry->getUser() ?: $entry->getStaff();
if ($entry->staff && $cfg->hideStaffName())
    $name = __('Staff');
else
    $name = $user ? $user->getName() : $entry->poster;
$avatar = '';
if ($cfg->isAvatarsEnabled() && $user)
    $avatar = $user->getAvatar();
?>
<?php
 $type = $entryTypes[$entry->type];
 ?>

<div class="timeline-badge <?php echo ($entry->type == 'M') ? 'success': 'info'; ?>">
    <i class="fa fa-mail-<?php echo ($entry->type == 'M') ? 'forward': 'reply'; ?>"></i>
</div>
<div class="timeline-panel">
    <div class="timeline-heading">
        <div class="pull-right">
            <?php if ($avatar) { ?>
                <?php echo $avatar; ?>
            <?php } else { ?>
                <span><?php echo ($entry->type); ?></span>
            <?php } ?>
            <span style="vertical-align:middle;" class="textra">
                <?php if ($entry->flags & ThreadEntry::FLAG_EDITED) { ?>
                <span class="label label-bare" title="<?php
                echo sprintf(__('Edited on %s by %s'), Format::datetime($entry->updated), 'You');
                ?>"><?php echo __('Edited'); ?></span>
                <?php } ?>
            </span>
        </div>
        <h4 class="timeline-title"><?php
                echo $entry->title; ?></h4>
        <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php
            echo sprintf(__('<b>%s</b> posted %s'), $name,
                sprintf('<time datetime="%s" title="%s">%s</time>',
                    date(DateTime::W3C, Misc::db2gmtime($entry->created)),
                    Format::daydatetime($entry->created),
                    Format::datetime($entry->created)
                )
            ); ?></small></p>
    </div>
    <div class="timeline-body" id="thread-id-<?php echo $entry->getId(); ?>">
        <hr>
        <?php echo $entry->getBody()->toHtml(); ?>
        <div class="clear"></div>
    </div>
    <?php
        if ($entry->has_attachments) { ?>
        <div class="timeline-footer">
            <hr>
            <div class="attachments"><?php
                foreach ($entry->attachments as $A) {
                    if ($A->inline)
                        continue;
                    $size = '';
                    if ($A->file->size)
                        $size = sprintf('<small class="filesize faded">%s</small>', Format::file_size($A->file->size));
                    ?>
                    <span class="attachment-info">
                        <i class="icon-paperclip icon-flip-horizontal"></i>
                        <a  class="no-pjax truncate filename"
                            href="<?php echo $A->file->getDownloadUrl(['id' => $A->getId()]);
                        ?>" download="<?php echo Format::htmlchars($A->getFilename()); ?>"
                        target="_blank"><?php echo Format::htmlchars($A->getFilename());
                        ?></a><?php echo $size;?>
                    </span>
                <?php   }  ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php
        if ($urls = $entry->getAttachmentUrls()) { ?>
        <script type="text/javascript">
            $('#thread-id-<?php echo $entry->getId(); ?>')
                .data('urls', <?php
                    echo JsonDataEncoder::encode($urls); ?>)
                .data('id', <?php echo $entry->getId(); ?>);
        </script>
        <?php
        }?>
