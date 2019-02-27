<?php
$TZ_NAME = @$TZ_NAME ?: 'timezone';
$TZ_ALLOW_DEFAULT = isset($TZ_ALLOW_DEFAULT) ? $TZ_ALLOW_DEFAULT : true;
$TZ_PLACEHOLDER = @$TZ_PLACEHOLDER ?: __('System Default');
$TZ_TIMEZONE = @$TZ_TIMEZONE ?: '';
?>
<div class="form-group <?php echo $errors['timezone']?'has-error':''; ?>">
<select class="form-control" name="<?php echo $TZ_NAME; ?>" id="timezone-dropdown"
        data-placeholder="<?php echo $TZ_PLACEHOLDER; ?>">
<?php if ($TZ_ALLOW_DEFAULT) { ?>
        <option value=""></option>
<?php }
    foreach (DateTimeZone::listIdentifiers() as $zone) { ?>
        <option value="<?php echo $zone; ?>" <?php
        if ($TZ_TIMEZONE == $zone)
            echo 'selected="selected"';
        ?>><?php echo str_replace('/',' / ',$zone); ?></option>
<?php } ?>
</select>
    <?php echo $errors['timezone']?'<span class="has-error">'.$errors['timezone'].'</span>':''; ?>
</div>
<div class="form-group">
    <button type="button" class="btn btn-primary form-control action-button" onclick="javascript:
$('head').append($('<script>').attr('src', '<?php
    echo ROOT_PATH; ?>js/jstz.min.js'));
var recheck = setInterval(function() {
    if (window.jstz !== undefined) {
        clearInterval(recheck);
        var zone = jstz.determine();
        $('#timezone-dropdown').val(zone.name()).trigger('change');

    }
}, 100);
return false;" style="vertical-align:middle"><i class="icon-map-marker"></i> <?php echo __('Auto Detect'); ?></button>
</div>

<script type="text/javascript">
$(function() {
    $('#timezone-dropdown').select2({
        allowClear: <?php echo $TZ_ALLOW_DEFAULT ? 'true' : 'false'; ?>,
        width: '300px'
    });
});
</script>
