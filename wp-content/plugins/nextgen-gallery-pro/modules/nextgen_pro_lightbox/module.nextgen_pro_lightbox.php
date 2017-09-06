<?php
/*
{
    Module: photocrati-nextgen_pro_lightbox,
    Depends: { photocrati-lightbox }
}
 */

define('NGG_PRO_LIGHTBOX', 'photocrati-nextgen_pro_lightbox');
define('NGG_PRO_LIGHTBOX_TRIGGER', NGG_PRO_LIGHTBOX);
define('NGG_PRO_LIGHTBOX_COMMENT_TRIGGER', 'photocrati-nextgen_pro_lightbox_comments');
define('NGG_PRO_LIGHTBOX_VERSION', '0.63');

class M_NextGen_Pro_Lightbox extends C_Base_Module
{
    // See self::add_component() below to extend the pro-lightbox
    static $_components = array();

    function define($id = 'pope-module',
                    $name = 'Pope Module',
                    $description = '',
                    $version = '',
                    $uri = '',
                    $author = '',
                    $author_uri = '',
                    $context = FALSE)
    {
        parent::define(
            'photocrati-nextgen_pro_lightbox',
            'NextGEN Pro Lightbox',
            'Provides a lightbox with integrated commenting, social sharing, and e-commerce functionality',
            NGG_PRO_LIGHTBOX_VERSION,
            'https://www.imagely.com/wordpress-gallery-plugin/nextgen-pro/',
            'Imagely',
            'https://www.imagely.com',
            $context
        );

        C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Lightbox_Installer');
    }

    function initialize()
    {
        parent::initialize();

        if (!is_admin())
        {
            $triggers = C_Displayed_Gallery_Trigger_Manager::get_instance();
            $triggers->add(NGG_PRO_LIGHTBOX_TRIGGER, 'C_NextGen_Pro_Lightbox_Trigger');
            $triggers->add(NGG_PRO_LIGHTBOX_COMMENT_TRIGGER, 'C_NextGen_Pro_Lightbox_Trigger');
        }
    }

    function _register_adapters()
    {
        $this->get_registry()->add_adapter('I_Ajax_Controller', 'A_NextGen_Pro_Lightbox_Ajax');

        if (!is_admin())
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Lightbox_Effect_Code');

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            // add additional settings to each supported display type
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_THUMBNAILS);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_SLIDESHOW);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_IMAGEBROWSER);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_SINGLEPIC);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_SLIDESHOW);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_HORIZONTAL_FILMSTRIP);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_THUMBNAIL_GRID);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_BLOG_GALLERY);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_FILM);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_MASONRY);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Album_Form', NGG_PRO_GRID_ALBUM);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Album_Form', NGG_PRO_LIST_ALBUM);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Album_Form', NGG_BASIC_COMPACT_ALBUM);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Album_Form', NGG_BASIC_EXTENDED_ALBUM);

            // lightbox settings form
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Form', NGG_PRO_LIGHTBOX);
        }
    }

    function _register_utilities()
    {
        if (!is_admin())
        {
            $this->get_registry()->add_utility('I_OpenGraph_Controller', 'C_OpenGraph_Controller');
        }
    }

    function _register_hooks()
    {
        add_action('admin_init', array(&$this, 'register_forms'));
        add_action('ngg_registered_default_lightboxes', array(&$this, 'register_lightbox'));
        if (!is_admin())
            add_action('init', array(&$this, 'define_routes'), 2);
        if (!is_admin())
            add_action('wp_enqueue_scripts', array($this, 'maybe_enqueue_fontawesome'));
    }

    function define_routes()
    {
        $router = C_Router::get_instance();
        $app = $router->create_app('/nextgen-share');
        $app->rewrite("/{displayed_gallery_id}/{image_id}", '/displayed_gallery_id--{displayed_gallery_id}/image_id--{image_id}/named_size--thumb', FALSE, TRUE);
        $app->rewrite('/{displayed_gallery_id}/{image_id}/{named_size}', '/displayed_gallery_id--{displayed_gallery_id}/image_id--{image_id}/named_size--{named_size}');
        $app->route('/', 'I_OpenGraph_Controller#index');
    }

    function register_lightbox()
    {
        $router     = C_Router::get_instance();
        $settings   = C_NextGen_Settings::get_instance()->get('ngg_pro_lightbox', array());
        $lightboxes = C_Lightbox_Library_Manager::get_instance();

        // Define the Pro Lightbox
        $lightbox = class_exists('C_NGG_Lightbox') ? new C_NGG_Lightbox(NGG_PRO_LIGHTBOX) : new stdClass;
        $lightbox->albums_supported = TRUE;
        $lightbox->title   = __('NextGEN Pro Lightbox', 'nextgen-gallery-pro');
        $lightbox->code    = "class='nextgen_pro_lightbox' data-nplmodal-gallery-id='%PRO_LIGHTBOX_GALLERY_ID%'";
        $lightbox->styles  = array(
            'photocrati-nextgen_pro_lightbox#style.css',
            'photocrati-nextgen_pro_lightbox#theme/galleria.nextgen_pro_lightbox.css'
        );
        $lightbox->scripts = array(
            'wordpress#underscore',
            'photocrati-nextgen_pro_lightbox#velocity.min.js',
            'photocrati-nextgen_pro_lightbox#parsesrcset.js',
            'wordpress#ngg_galleria',
            'photocrati-nextgen_pro_lightbox#nextgen_pro_lightbox.js',
            'photocrati-nextgen_pro_lightbox#theme/galleria.nextgen_pro_lightbox.js'
        );

        // Set lightbox display properties
        $settings['is_front_page']  = is_front_page() ? 1 : 0;
        $settings['share_url']      = $router->get_url('/nextgen-share/{gallery_id}/{image_id}/{named_size}', TRUE, 'root');
        $settings['wp_site_url']    = $router->get_base_url('site');
        $settings['protect_images'] = (!empty(C_NextGen_Settings::get_instance()->protect_images) ? TRUE :  FALSE);
        $settings['style']          = str_replace('.css', '', $settings['style']); // this once (~2.1.4) referenced files

        // provide the current language so ajax requests can request translations in the same locale
        if (defined('ICL_LANGUAGE_CODE'))
            $settings['lang'] = $router->param('lang', NULL, FALSE) ? $router->param('lang') : ICL_LANGUAGE_CODE;

        $settings['i18n'] = array(
            'toggle_social_sidebar' => __('Toggle social sidebar', 'nextgen-gallery-pro'),
            'play_pause'            => __('Play / Pause', 'nextgen-gallery-pro'),
            'toggle_fullsize'       => __('Toggle fullsize', 'nextgen-gallery-pro'),
            'toggle_image_info'     => __('Toggle image info', 'nextgen-gallery-pro'),
            'close_window'          => __('Close window', 'nextgen-gallery-pro'),
            'share' => array(
                'twitter'   => __('Share on Twitter', 'nextgen-gallery-pro'),
                'googlep'   => __('Share on Google+', 'nextgen-gallery-pro'),
                'facebook'  => __('Share on Facebook', 'nextgen-gallery-pro'),
                'pinterest' => __('Share on Pinterest', 'nextgen-gallery-pro')
            )
        );

        $lightbox->values = array('nplModalSettings' => $settings);
        $lightboxes->register(NGG_PRO_LIGHTBOX, $lightbox);
    }

    function register_forms()
    {
        $forms = C_Form_Manager::get_instance();
        $forms->add_form(NGG_LIGHTBOX_OPTIONS_SLUG, NGG_PRO_LIGHTBOX);
    }

    static function get_components()
    {
        return self::$_components;
    }

    static function add_component($name, $handler)
    {
        self::$_components[$name] = $handler;
    }

    static function remove_component($name, $handler)
    {
        unset(self::$_components[$name]);
    }

    function maybe_enqueue_fontawesome()
    {
        $settings = C_NextGen_Settings::get_instance();
        $context = isset($settings->thumbEffectContext) ? $settings->thumbEffectContext : '';
        if ($context != 'nextgen_images')
            M_Gallery_Display::enqueue_fontawesome();
    }

    function get_type_list()
    {
        return array(
            'A_Pro_Lightbox_Mapper'                => 'adapter.pro_lightbox_mapper.php',
            'A_NextGen_Pro_Lightbox_Pages'         => 'adapter.nextgen_pro_lightbox_pages.php',
            'A_Nextgen_Pro_Lightbox_Effect_Code'   => 'adapter.nextgen_pro_lightbox_effect_code.php',
            'A_Nextgen_Pro_Lightbox_Form'          => 'adapter.nextgen_pro_lightbox_form.php',
            'A_Nextgen_Pro_Lightbox_Triggers_Form' => 'adapter.nextgen_pro_lightbox_triggers_form.php',
            'C_NextGen_Pro_Lightbox_Trigger'       => 'class.nextgen_pro_lightbox_trigger.php',
            'A_NextGen_Pro_Lightbox_Ajax'          => 'adapter.nextgen_pro_lightbox_ajax.php',
            'C_Opengraph_Controller'               => 'class.opengraph_controller.php',
            'M_NextGen_Pro_Lightbox'               => 'module.nextgen_pro_lightbox.php',
            'A_NextGen_Pro_Lightbox_Album_Form'    => 'adapter.nextgen_pro_lightbox_album_form.php'
        );
    }
}

class C_NextGen_Pro_Lightbox_Installer
{
    function get_registry()
    {
        return C_Component_Registry::get_instance();
    }

    function set_attr(&$obj, $key, $val)
    {
        if (!isset($obj->$key))
            $obj->$key = $val;
    }

    function install_pro_lightbox_settings(C_Photocrati_Settings_Manager $settings, $reset = FALSE)
    {
        $defaults = array(
            'background_color'          => 1,
            'enable_routing'            => 1,
            'icon_color'                => '',
            'icon_background'           => '',
            'icon_background_enabled'   => 0,
            'icon_background_rounded'   => 1,
            'overlay_icon_color'        => '',
            'sidebar_button_color'      => '',
            'sidebar_button_background' => '',
            'router_slug'               => 'gallery',
            'carousel_background_color' => '',
            'carousel_text_color'       => '',
            'enable_comments'           => 1,
            'enable_sharing'            => 1,
            'enable_fullscreen'         => 0,
            'display_comments'          => 0,
            'display_captions'          => 0,
            'display_carousel'          => 1,
            'image_crop'                => 'false', // it is important that this not be a number zero
            'image_pan'                 => 0,
            'interaction_pause'         => 1,
            'sidebar_background_color'  => '',
            'slideshow_speed'           => 5,
            'style'                     => 'white',
            'touch_transition_effect'   => 'slide',
            'transition_effect'         => 'slide',
            'transition_speed'          => 0.4,
            'localize_limit'            => 100,
            'enable_twitter_cards'      => 0,
            'twitter_username'          => '',
            'padding'                   => '0',
            'padding_unit'              => 'px',
            'facebook_app_id'           => ''
        );

        // Create settings array
        if (!$settings->exists('ngg_pro_lightbox'))
            $settings->set('ngg_pro_lightbox', array());
        $ngg_pro_lightbox = $settings->get('ngg_pro_lightbox');

        // Need migration logic from custom post type
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = 'lightbox_library' AND post_title = %s", NGG_PRO_LIGHTBOX));
        if ($row)
        {
            $row->post_content = M_DataMapper::unserialize($row->post_content);
            $ngg_pro_lightbox  = $row->post_content['display_settings'];
            @wp_delete_post($row->ID, TRUE);
        }

        // Set defaults
        foreach ($defaults as $key => $value) {
            if (!array_key_exists($key, $ngg_pro_lightbox))
                $ngg_pro_lightbox[$key] = $value;
        }

        // Save the data
        $settings->set('ngg_pro_lightbox', $ngg_pro_lightbox);
    }

    function install($reset = FALSE)
    {
        $this->install_pro_lightbox_settings(C_NextGen_Settings::get_instance());
    }

    function uninstall($hard = FALSE)
    {
        $settings = C_Photocrati_Settings_Manager::get_instance();
        if ($hard)
        {
            $settings->delete('ngg_pro_lightbox');
            $settings->save();
        }
    }
}

new M_NextGen_Pro_Lightbox;
