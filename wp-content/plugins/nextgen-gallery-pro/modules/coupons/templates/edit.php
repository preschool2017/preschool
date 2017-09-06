<div class="wrap" id='ngg_page_content' style='position: relative; visibility: hidden;'>
    <h2><?php esc_html_e($page_heading)?></h2>
    <?php if ($errors): ?>
        <?php foreach ($errors as $msg): ?>
            <?php echo $msg ?>
        <?php endforeach ?>
    <?php endif ?>
    <?php if ($success AND empty($errors)): ?>
        <div class='success updated'>
            <p><?php esc_html_e($success);?></p>
        </div>
    <?php endif ?>
    <form method="POST" action="<?php echo nextgen_esc_url($_SERVER['REQUEST_URI'])?>">
        <input type="hidden" name="coupon[ID]" value="<?php echo esc_attr($model->id()) ?>"/>
        <br/>
        <div id="titlediv">
            <div id="titlewrap">
                <input type="text" placeholder='Title' autocomplete="off" id="title" value="<?php echo esc_attr($model->title)?>" size="30" name="coupon[title]">
            </div>
        </div>
        <?php if (isset($form_header)) { ?>
            <?php echo $form_header . "\n"; ?>
        <?php } ?>
        <input type="hidden" name="action"/>

        <div class="accordion" id="nextgen_admin_accordion">
            <?php foreach ($tabs as $tab) {
                echo $tab;
            } ?>
        </div>

        <?php if ($show_save_button) { ?>
            <p>
                <input type="submit" id='save_btn'   name='action_proxy' value="<?php _e('Save', 'nextgen-gallery-pro'); ?>" class="button-primary"/>
                <input type="submit"
                       value="<?php _e('Cancel', 'nextgen-gallery-pro'); ?>"
                       id="cancel_btn"
                       class="button-secondary"
                       data-redirect="<?php echo admin_url('/edit.php?post_type=ngg_coupon')?>"/>
            </p>
        <?php } ?>
    </form>
</div>
<script type="text/javascript">
    jQuery(function($){
        $('#cancel_btn').click(function(e) {
            e.stopPropagation();
            e.stopImmediatePropagation();
            e.preventDefault();
            window.location = $(this).attr('data-redirect');
            return false;
        });
    });
</script>