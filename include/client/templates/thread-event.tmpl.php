<?php
$desc = $event->getDescription(ThreadEvent::MODE_CLIENT); 
if (!$desc)
    return;
?>
<li>
<div class="timeline-badge">
  <i class="faded icon-<?php echo $event->getIcon(); ?>"></i>
</div>
<div class="timeline-panel">
  <div class="timeline-body">
    <span class="faded description"><?php echo $desc; ?></span>
  </div>
</div>
</li>