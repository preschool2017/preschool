jQuery(function($) {
    $('#photocrati-nextgen_pro_lightbox_router_slug').prop('required', true);

    // All of the social settings are dependent on enable_routing being set to on
    // Further twitter_username needs both enable_twitter_cards and enable_sharing to be on
    $('input[name="photocrati-nextgen_pro_lightbox[enable_routing]"]').on('change', function() {
        if ($(this).val() == 0) {
            $('#tr_photocrati-nextgen_pro_lightbox_social, ' +
              '#tr_photocrati-nextgen_pro_lightbox_enable_comments, ' +
              '#tr_photocrati-nextgen_pro_lightbox_display_comments, ' +
              '#tr_photocrati-nextgen_pro_lightbox_enable_sharing, ' +
              '#tr_photocrati-nextgen_pro_lightbox_facebook_app_id, ' +
              '#tr_photocrati-nextgen_pro_lightbox_enable_twitter_cards, ' +
              '#tr_photocrati-nextgen_pro_lightbox_twitter_username').hide();
        } else {
            $('#tr_photocrati-nextgen_pro_lightbox_social, ' +
              '#tr_photocrati-nextgen_pro_lightbox_enable_comments, ' +
              '#tr_photocrati-nextgen_pro_lightbox_enable_sharing').show();

            if ($('input[name="photocrati-nextgen_pro_lightbox[enable_sharing]"]:checked').val() == '1') {
                $('#tr_photocrati-nextgen_pro_lightbox_enable_twitter_cards').show();
                $('#tr_photocrati-nextgen_pro_lightbox_facebook_app_id').show();
            }

            if ($('input[name="photocrati-nextgen_pro_lightbox[enable_comments]"]:checked').val() == '1') {
                console.log("I can't even");
                $('#tr_photocrati-nextgen_pro_lightbox_display_comments').show();
            }

            if ($('input[name="photocrati-nextgen_pro_lightbox[enable_twitter_cards]"]:checked').val() == '1'
            &&  $('input[name="photocrati-nextgen_pro_lightbox[enable_sharing]"]:checked').val() == '1') {
                console.log("stop");
                $('#tr_photocrati-nextgen_pro_lightbox_twitter_username').show();
            }
        }
    });

    // Again we must treat the fields dependent on this and its dependents as well
    $('input[name="photocrati-nextgen_pro_lightbox[enable_sharing]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_facebook_app_id'))
        .on('change', function() {
            if ($(this).val() == 1) {
                $('#tr_photocrati-nextgen_pro_lightbox_enable_twitter_cards').show();
                if ($('input[name="photocrati-nextgen_pro_lightbox[enable_twitter_cards]"]:checked').val() == '1') {
                    $('#tr_photocrati-nextgen_pro_lightbox_twitter_username').show();
                }
            } else {
                $('#tr_photocrati-nextgen_pro_lightbox_enable_twitter_cards').hide();
                $('#tr_photocrati-nextgen_pro_lightbox_twitter_username').hide();
            }
        });

    // Now back to your regularly scheduled if/then hide/show
    $('input[name="photocrati-nextgen_pro_lightbox[enable_comments]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_display_comments'));
    $('input[name="photocrati-nextgen_pro_lightbox[enable_twitter_cards]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_twitter_username'));
    $('input[name="photocrati-nextgen_pro_lightbox[icon_background_enabled]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_icon_background'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_lightbox_icon_background_rounded'));

    $('select[name="photocrati-nextgen_pro_lightbox[style]"]').on('change', function() {
        var $this = $(this);

        // TODO: make these dynamicly provided
        if ($this.val() == 'white.css' || $this.val() == 'white') {
            $('input[name="photocrati-nextgen_pro_lightbox[icon_color]"]').wpColorPicker('color', '#7a7a7a');
            $('input[name="photocrati-nextgen_pro_lightbox[carousel_text_color]"]').wpColorPicker('color', '#7a7a7a');
            $('input[name="photocrati-nextgen_pro_lightbox[background_color]"]').wpColorPicker('color', '#ffffff');
        } else {
            $('input[name="photocrati-nextgen_pro_lightbox[icon_color]"]').siblings().first().trigger('click');
            $('input[name="photocrati-nextgen_pro_lightbox[carousel_text_color]"]').siblings().first().trigger('click');
            $('input[name="photocrati-nextgen_pro_lightbox[background_color]"]').siblings().first().trigger('click');
        }

        $('input[name="photocrati-nextgen_pro_lightbox[carousel_text_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[carousel_background_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[sidebar_background_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[icon_background]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[overlay_icon_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[sidebar_button_color]"]').siblings().first().trigger('click');
        $('input[name="photocrati-nextgen_pro_lightbox[sidebar_button_background]"]').siblings().first().trigger('click');
    });
});
