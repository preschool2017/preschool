<?php
/**
 * Because enqueueing an albums child entities (for use in lightboxes) is slow to do inside of cache_action() and
 * we can't guarantee index_action() will run on every hit (thanks to page caching) we inline those entities into
 * our Pro Albums templates under a window.load listener.
 *
 * @mixin C_MVC_View
 * @adapts I_MVC_View
 */
class A_NextGen_Pro_Album_Child_Entities extends Mixin
{
    protected static $_runonce = FALSE;
    public static $_entities = array();
    /**
     * The pro album controller will invoke this filter when its _render_album() method is called
     */
    function __construct()
    {
        if (!self::$_runonce) {
            add_filter('ngg_pro_album_prepared_child_entity', array($this, 'register_child_gallery'), 10, 2);
        } else {
            self::$_runonce = TRUE;
        }
    }
    /**
     * Register each gallery belonging to the album that has just been rendered, so that when the MVC controller
     * system 'catches up' and runs $this->render_object() that method knows what galleries to inline as JS.
     *
     * @param array $gallery
     * @param $displayed_gallery
     * @return array mixed
     */
    function register_child_gallery($galleries, $displayed_gallery)
    {
        if (!$this->is_pro_album($displayed_gallery)) {
            return $galleries;
        }
        $id = $displayed_gallery->ID();
        foreach ($galleries as $gallery) {
            if ($gallery->is_album) {
                continue;
            }
            self::$_entities[$id][] = $gallery;
        }
        return $galleries;
    }
    function is_pro_album($displayed_gallery)
    {
        return in_array($displayed_gallery->display_type, array(NGG_PRO_GRID_ALBUM, NGG_PRO_LIST_ALBUM));
    }
    /**
     * Determine if we need to append the JS to the current template
     *
     * @param $display_settings
     * @return bool
     */
    function are_child_entities_enabled($display_settings)
    {
        $retval = FALSE;
        if (empty($display_settings['open_gallery_in_lightbox'])) {
            $display_settings['open_gallery_in_lightbox'] = 0;
        }
        if ($display_settings['open_gallery_in_lightbox'] == 1) {
            $retval = TRUE;
        }
        return $retval;
    }
    /**
     * Search inside the template for the inside of the container and append our inline JS
     */
    function render_object()
    {
        $root_element = $this->call_parent('render_object');
        if ($displayed_gallery = $this->object->get_param('displayed_gallery')) {
            if (!$this->is_pro_album($displayed_gallery)) {
                return $root_element;
            }
            $ds = $displayed_gallery->display_settings;
            if ($this->are_child_entities_enabled($ds)) {
                $id = $displayed_gallery->ID();
                foreach ($root_element->find('nextgen_gallery.gallery_container', TRUE) as $container) {
                    $container->append($this->object->generate_script(self::$_entities[$id]));
                }
            }
        }
        return $root_element;
    }
    /**
     * Generate the JS that will be inserted into the template
     *
     * @param array $galleries
     * @return string
     */
    function generate_script($galleries)
    {
        $retval = '<script type="text/javascript">window.addEventListener("load", function() {';
        foreach ($galleries as $gallery) {
            $dg = $gallery->displayed_gallery;
            $id = $dg->id();
            $retval .= 'galleries.gallery_' . $id . ' = ' . json_encode($dg->get_entity()) . ';';
            $retval .= 'galleries.gallery_' . $id . '.wordpress_page_root = "' . get_permalink() . '";';
        }
        $retval .= '}, false);</script>';
        return $retval;
    }
}
/*
 * This form is meant to be extended by each album type, it provides defaults for common settings
 * @class
 */
/**
 * Class A_NextGen_Pro_Album_Form
 * @mixin C_Form
 * @adapts I_Form using "photocrati-nextgen_pro_list_album" and "photocrati-nextgen_pro_grid_album" contexts
 */
class A_NextGen_Pro_Album_Form extends Mixin_Display_Type_Form
{
    /**
     * Enqueues static resources required by this form
     */
    function enqueue_static_resources()
    {
        wp_enqueue_script('nextgen_pro_albums_settings_script', $this->object->get_static_url('photocrati-nextgen_pro_albums#settings.js'), array('jquery.nextgen_radio_toggle'));
        $atp = C_Attach_Controller::get_instance();
        if ($atp != null && $atp->has_method('mark_script')) {
            $atp->mark_script('nextgen_pro_albums_settings_script');
        }
    }
    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array('thumbnail_override_settings', 'nextgen_pro_albums_display_type', 'nextgen_pro_albums_enable_breadcrumbs', 'nextgen_pro_albums_caption_color', 'nextgen_pro_albums_caption_size', 'nextgen_pro_albums_border_color', 'nextgen_pro_albums_border_size', 'nextgen_pro_albums_background_color', 'nextgen_pro_albums_padding', 'nextgen_pro_albums_spacing', 'nextgen_pro_albums_child_descriptions', 'display_type_view');
    }
    function _render_nextgen_pro_albums_child_descriptions_field($display_type)
    {
        return $this->_render_radio_field($display_type, 'enable_descriptions', __('Display album and gallery descriptions', 'nggallery'), $display_type->settings['enable_descriptions']);
    }
    /*
     * Let users choose which display type galleries inside albums use
     */
    function _render_nextgen_pro_albums_display_type_field($display_type)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        $types = array();
        foreach ($mapper->find_by_entity_type('image') as $dt) {
            $types[$dt->name] = $dt->title;
        }
        return $this->_render_select_field($display_type, 'gallery_display_type', __('Display galleries as', 'nextgen-gallery-pro'), $types, $display_type->settings['gallery_display_type'], __('How would you like galleries to be displayed?', 'nextgen-gallery-pro'));
    }
    function _render_nextgen_pro_albums_enable_breadcrumbs_field($display_type)
    {
        if (version_compare(NGG_PLUGIN_VERSION, '2.0.80') <= 0) {
            return '';
        }
        return $this->_render_radio_field($display_type, 'enable_breadcrumbs', __('Enable breadcrumbs', 'nggallery'), isset($display_type->settings['enable_breadcrumbs']) ? $display_type->settings['enable_breadcrumbs'] : FALSE);
    }
    function _render_nextgen_pro_albums_caption_color_field($display_type)
    {
        return $this->_render_color_field($display_type, 'caption_color', __('Caption color', 'nextgen-gallery-pro'), $display_type->settings['caption_color']);
    }
    function _render_nextgen_pro_albums_caption_size_field($display_type)
    {
        return $this->_render_number_field($display_type, 'caption_size', __('Caption size', 'nextgen-gallery-pro'), $display_type->settings['caption_size'], '', FALSE, '', 0);
    }
    function _render_nextgen_pro_albums_border_color_field($display_type)
    {
        return $this->_render_color_field($display_type, 'border_color', __('Border color', 'nextgen-gallery-pro'), $display_type->settings['border_color']);
    }
    function _render_nextgen_pro_albums_border_size_field($display_type)
    {
        return $this->_render_number_field($display_type, 'border_size', __('Border size', 'nextgen-gallery-pro'), $display_type->settings['border_size'], '', FALSE, '', 0);
    }
    function _render_nextgen_pro_albums_background_color_field($display_type)
    {
        return $this->_render_color_field($display_type, 'background_color', __('Background color', 'nextgen-gallery-pro'), $display_type->settings['background_color']);
    }
    function _render_nextgen_pro_albums_padding_field($display_type)
    {
        return $this->_render_number_field($display_type, 'padding', __('Padding', 'nextgen-gallery-pro'), $display_type->settings['padding'], '', FALSE, '', 0);
    }
    function _render_nextgen_pro_albums_spacing_field($display_type)
    {
        return $this->_render_number_field($display_type, 'spacing', __('Spacing', 'nextgen-gallery-pro'), $display_type->settings['spacing'], '', FALSE, '', 0);
    }
}
/**
 * Class A_NextGen_Pro_Album_Mapper
 * @mixin C_DataMapper_Driver_Base
 * @adapts I_DataMapper
 */
class A_NextGen_Pro_Album_Mapper extends Mixin
{
    function set_defaults($entity)
    {
        $this->call_parent('set_defaults', $entity);
        if (in_array($entity->name, array(NGG_PRO_LIST_ALBUM, NGG_PRO_GRID_ALBUM))) {
            $settings = C_NextGen_Settings::get_instance();
            // Galleries within the album will be displayed as NextGEN Pro Thumbnails, or
            // if not available, then NextGEN Basic Thumbnails
            $gallery_display_type = defined('NGG_PRO_THUMBNAIL_GRID') ? NGG_PRO_THUMBNAIL_GRID : NGG_BASIC_THUMBNAILS;
            $this->_set_default_value($entity, 'settings', 'gallery_display_type', $gallery_display_type);
            // Basic style settings
            $this->_set_default_value($entity, 'settings', 'enable_breadcrumbs', 1);
            $this->_set_default_value($entity, 'settings', 'enable_descriptions', 0);
            $this->_set_default_value($entity, 'settings', 'caption_color', '#333333');
            $this->_set_default_value($entity, 'settings', 'border_color', '#CCCCCC');
            $this->_set_default_value($entity, 'settings', 'border_size', 1);
            $this->_set_default_value($entity, 'settings', 'background_color', '#FFFFFF');
            $this->_set_default_value($entity, 'settings', 'padding', 20);
            $this->_set_default_value($entity, 'settings', 'spacing', 10);
            $this->_set_default_value($entity, 'settings', 'display_type_view', 'default');
            // Thumbnail dimensions
            $this->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 0);
            $this->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
            $this->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
            $this->_set_default_value($entity, 'settings', 'thumbnail_quality', $settings->thumbquality);
            $this->_set_default_value($entity, 'settings', 'thumbnail_crop', $settings->thumbfix);
            $this->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);
            $this->object->_set_default_value($entity, 'settings', 'open_gallery_in_lightbox', 0);
        }
        // Grid albums do not share a caption_size
        if ($entity->name == NGG_PRO_GRID_ALBUM) {
            $this->_set_default_value($entity, 'settings', 'caption_size', 13);
        }
        if ($entity->name == NGG_PRO_LIST_ALBUM) {
            $this->_set_default_value($entity, 'settings', 'description_color', '#33333');
            $this->_set_default_value($entity, 'settings', 'description_size', 13);
            $this->_set_default_value($entity, 'settings', 'caption_size', 18);
        }
    }
}
/**
 * Class A_NextGen_Pro_Album_Routes
 * @mixin C_Displayed_Gallery_Renderer
 * @adapts I_Displayed_Gallery_Renderer
 */
class A_NextGen_Pro_Album_Routes extends Mixin
{
    function render($displayed_gallery, $return = FALSE, $mode = NULL)
    {
        $do_rewrites = FALSE;
        $album_types = array(NGG_PRO_ALBUMS, NGG_PRO_LIST_ALBUM, NGG_PRO_GRID_ALBUM);
        // Get the original display type
        $original_display_type = isset($displayed_gallery->display_settings['original_display_type']) ? $displayed_gallery->display_settings['original_display_type'] : '';
        if (in_array($displayed_gallery->display_type, $album_types)) {
            $do_rewrites = TRUE;
            $router = C_Router::get_instance();
            $app = $router->get_routed_app();
            $slug = '/' . C_NextGen_Settings::get_instance()->router_param_slug;
            // ensure to pass $stop=TRUE to $app->rewrite() on parameters that may be shared with other display types
            $app->rewrite('{*}' . $slug . '/page/{\\d}{*}', '{1}' . $slug . '/nggpage--{2}{3}', FALSE, TRUE);
            $app->rewrite('{*}' . $slug . '/page--{*}', '{1}' . $slug . '/nggpage--{2}', FALSE, TRUE);
            $app->rewrite('{*}' . $slug . '/{\\w}', '{1}' . $slug . '/album--{2}');
            $app->rewrite('{*}' . $slug . '/{\\w}/{\\w}', '{1}' . $slug . '/album--{2}/gallery--{3}');
            $app->rewrite('{*}' . $slug . '/{\\w}/{\\w}/{\\w}{*}', '{1}' . $slug . '/album--{2}/gallery--{3}/{4}{5}');
        } elseif (in_array($original_display_type, $album_types)) {
            $do_rewrites = TRUE;
            $router = C_Router::get_instance();
            $app = $router->get_routed_app();
            $slug = '/' . C_NextGen_Settings::get_instance()->router_param_slug;
            $app->rewrite("{*}{$slug}/album--{\\w}", "{1}{$slug}/{2}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}", "{1}{$slug}/{2}/{3}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}/{*}", "{1}{$slug}/{2}/{3}/{4}");
        }
        if ($do_rewrites) {
            $app->do_rewrites();
        }
        // Continue rendering
        return $this->call_parent('render', $displayed_gallery, $return, $mode);
    }
}
class Mixin_NextGen_Pro_Album_Controller extends Mixin
{
    function set_param_for($url, $key, $value, $id = NULL, $use_prefix = FALSE)
    {
        $retval = $this->call_parent('set_param_for', $url, $key, $value, $id, $use_prefix);
        // Adjust the return value
        while (preg_match("#album--([^/]+)#", $retval, $matches)) {
            $retval = str_replace($matches[0], $matches[1], $retval);
        }
        while (preg_match("#gallery--([^/]+)#", $retval, $matches)) {
            $retval = str_replace($matches[0], $matches[1], $retval);
        }
        return $retval;
    }
    function _render_gallery($display_type, $original_display_type, $original_settings, $original_entities, $return = FALSE)
    {
        // Try finding the gallery by slug first. If nothing is found, we assume that
        // the user passed in a gallery id instead
        $gallery = $gallery_slug = $this->object->param('gallery');
        $mapper = C_Gallery_Mapper::get_instance();
        $tmp = $mapper->select()->where(array('slug = %s', $gallery))->limit(1)->run_query();
        $result = reset($tmp);
        unset($tmp);
        if ($result) {
            $gallery = $result->{$result->id_field};
        }
        add_filter('ngg_displayed_gallery_rendering', array($this, 'add_breadcrumbs_to_legacy_templates'), 10, 2);
        $renderer = C_Displayed_Gallery_Renderer::get_instance();
        $output = $renderer->display_images(array('source' => 'galleries', 'container_ids' => array($gallery), 'display_type' => $display_type, 'original_display_type' => $original_display_type, 'original_settings' => $original_settings, 'original_album_entities' => $original_entities), $return);
        remove_filter('ngg_displayed_gallery_rendering', array($this, 'add_breadcrumbs_to_legacy_templates'));
        return $output;
    }
    function _get_displayed_gallery_thumbnail_size_name($displayed_gallery)
    {
        // Generate the named thumbnail size
        $thumbnail_size_name = 'thumb';
        if (isset($displayed_gallery->display_settings['override_thumbnail_settings']) && $displayed_gallery->display_settings['override_thumbnail_settings']) {
            $dynthumbs = C_Dynamic_Thumbnails_Manager::get_instance();
            $dyn_params = array('width' => $displayed_gallery->display_settings['thumbnail_width'], 'height' => $displayed_gallery->display_settings['thumbnail_height']);
            if ($displayed_gallery->display_settings['thumbnail_quality']) {
                $dyn_params['quality'] = $displayed_gallery->display_settings['thumbnail_quality'];
            }
            if ($displayed_gallery->display_settings['thumbnail_crop']) {
                $dyn_params['crop'] = true;
            }
            if ($displayed_gallery->display_settings['thumbnail_watermark']) {
                $dyn_params['watermark'] = true;
            }
            $thumbnail_size_name = $dynthumbs->get_size_name($dyn_params);
        }
        return $thumbnail_size_name;
    }
    function _render_album($displayed_gallery, $original_entities, $return)
    {
        // The HTML id of the gallery
        $id = 'displayed_gallery_' . $displayed_gallery->id();
        $thumbnail_size_name = $this->object->_get_displayed_gallery_thumbnail_size_name($displayed_gallery);
        // Get entities
        $entities = $this->object->_prepare_entities($displayed_gallery, $thumbnail_size_name);
        // Render view/template
        $params = array_merge($displayed_gallery->display_settings, array('entities' => $entities, 'effect_code' => $this->object->get_effect_code($displayed_gallery), 'id' => $id, 'thumbnail_size_name' => $thumbnail_size_name, 'css_class' => $this->object->_get_css_class()));
        $params = $this->object->prepare_display_parameters($displayed_gallery, $params);
        if (!is_null($original_entities)) {
            $displayed_gallery->display_settings['original_album_id'] = 'a' . $displayed_gallery->container_ids[0];
            $displayed_gallery->display_settings['original_album_entities'] = $original_entities;
        }
        return $this->render_view('photocrati-nextgen_pro_albums#index', $params, $return);
    }
    function _prepare_entities($displayed_gallery, $thumbnail_size_name)
    {
        $current_url = $this->object->get_routed_url(TRUE);
        $storage = C_Gallery_Storage::get_instance();
        $mapper = C_Image_Mapper::get_instance();
        $entities = $displayed_gallery->get_included_entities();
        foreach ($entities as &$entity) {
            $entity->entity_type = $entity_type = intval($entity->is_gallery) ? 'gallery' : 'album';
            // Is the gallery actually a link to a page? Stupid feature...
            if (isset($entity->pageid) && $entity->pageid > 0) {
                $entity->link = get_page_link($entity->pageid);
            } else {
                $page_url = $current_url;
                if (intval($entity->is_gallery) && !$this->param('album')) {
                    $page_url = $this->object->set_param_for($page_url, 'album', 'galleries');
                }
                $entity->link = $this->object->set_param_for($page_url, $entity_type, $entity->slug);
            }
            // Add image information to the gallery object
            $entity->previewpic_image = $preview_img = $mapper->find($entity->previewpic);
            $entity->thumb_size = $storage->get_image_dimensions($preview_img, $thumbnail_size_name);
            $entity->previewpic_image_url = $storage->get_image_url($preview_img, 'full');
            $entity->previewpic_thumbnail_url = $entity->previewpic_thumb_url = $storage->get_image_url($preview_img, $thumbnail_size_name, TRUE);
            // If the setting is on we need to inject an effect code
            if (!empty($displayed_gallery->display_settings['open_gallery_in_lightbox']) && $entity_type == 'gallery') {
                $entity = $this->object->make_child_displayed_gallery($entity, $displayed_gallery->display_settings);
                if ($this->does_lightbox_support_displayed_gallery($displayed_gallery)) {
                    $entity->displayed_gallery->effect_code = $this->object->get_effect_code($entity->displayed_gallery);
                }
            }
        }
        $entities = apply_filters('ngg_pro_album_prepared_child_entity', $entities, $displayed_gallery);
        return $entities;
    }
    /**
     * Creates a displayed gallery of a gallery belonging to an album. Shared by index_action() and cache_action() to
     * allow lightboxes to open album children directly.
     *
     * @param $gallery
     * @param $display_settings
     * @return $gallery
     */
    function make_child_displayed_gallery($gallery, $display_settings)
    {
        $gallery->displayed_gallery = new C_Displayed_Gallery();
        $gallery->displayed_gallery->container_ids = array($gallery->{$gallery->id_field});
        $gallery->displayed_gallery->display_settings = $display_settings;
        $gallery->displayed_gallery->returns = 'included';
        $gallery->displayed_gallery->source = 'galleries';
        $gallery->displayed_gallery->images_list_count = $gallery->displayed_gallery->get_entity_count();
        $gallery->displayed_gallery->is_album_gallery = TRUE;
        $gallery->displayed_gallery->to_transient();
        return $gallery;
    }
    function index_action($displayed_gallery, $return = FALSE)
    {
        // Ensure that the open_gallery_in_lightbox setting is present
        if (!array_key_exists('open_gallery_in_lightbox', $displayed_gallery->display_settings)) {
            $displayed_gallery->display_settings['open_gallery_in_lightbox'] = 0;
        }
        $retval = '';
        // Determine what to render:
        // 1) A gallery
        if ($this->param('gallery')) {
            $retval = $this->object->_render_gallery($displayed_gallery->display_settings['gallery_display_type'], $displayed_gallery->display_type, $displayed_gallery->display_settings, $displayed_gallery->get_albums(), TRUE);
        } else {
            if ($album_id = $this->param('album')) {
                if (!is_numeric($album_id)) {
                    $mapper = C_Album_Mapper::get_instance();
                    $result = array_pop($mapper->select()->where(array("slug = %s", $album_id))->limit(1)->run_query());
                    $album_id = $result->{$result->id_field};
                }
                $original_entities = $displayed_gallery->get_albums();
                $displayed_gallery->container_ids = array($album_id);
                $retval = $this->object->_render_album($displayed_gallery, $original_entities, $return);
            } else {
                $retval = $this->object->_render_album($displayed_gallery, NULL, $return);
            }
        }
        return $retval;
    }
    function add_breadcrumbs_to_legacy_templates($html, $displayed_gallery)
    {
        if (version_compare(NGG_PLUGIN_VERSION, '2.0.80') <= 0) {
            return $html;
        }
        $this->object->add_mixin('A_NextGen_Album_Breadcrumbs');
        $breadcrumbs = $this->object->render_legacy_template_breadcrumbs($displayed_gallery, $displayed_gallery->display_settings['original_album_entities'], $displayed_gallery->conatiner_ids);
        if (!empty($breadcrumbs)) {
            $html = $breadcrumbs . $html;
        }
        return $html;
    }
    /**
     * TODO: Remove after 2.1.50 is well circulated
     * @param $displayed_gallery
     * @param null $lightbox
     * @return bool
     */
    function does_lightbox_support_displayed_gallery($displayed_gallery, $lightbox = NULL)
    {
        if (!$lightbox) {
            $lightbox = C_Lightbox_Library_Manager::get_instance()->get_selected();
        }
        $retval = FALSE;
        if ($lightbox) {
            // HANDLE COMPATIBILITY BREAK
            // In NGG 2.1.48 and earlier, lightboxes were stdClass objects, and it was assumed
            // that they only supported galleries that contained images, not albums that contained galleries.
            // After NGG 2.1.48, lightboxes are now C_NGG_Lightbox instances which have a 'is_supported()' method
            // to test if the lightbox can work with the displayed gallery settings
            if (get_class($lightbox) == 'stdClass') {
                $retval = !in_array($displayed_gallery->source, array('album', 'albums')) || isset($lightbox->albums_supported);
            } else {
                $retval = $lightbox->is_supported($displayed_gallery);
            }
        }
        return $retval;
    }
}
/**
 * Class A_NextGen_Pro_Grid_Album_Form
 * @mixin C_Form
 * @adapts I_Form using ""photocrati-nextgen_pro_grid_album" context
 */
class A_NextGen_Pro_Grid_Album_Form extends A_NextGen_Pro_Album_Form
{
    function get_display_type_name()
    {
        return NGG_PRO_GRID_ALBUM;
    }
}
/**
 * Class A_NextGen_Pro_List_Album_Controller
 * @mixin C_Display_Type_Controller
 * @adapts I_Display_Type_Controller for "photocrati-nextgen_pro_list_album" context
 */
class A_NextGen_Pro_List_Album_Controller extends Mixin_NextGen_Pro_Album_Controller
{
    function _get_css_class()
    {
        return 'nextgen_pro_list_album';
    }
    function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
        $ds = $displayed_gallery->display_settings;
        if (!empty($ds['enable_breadcrumbs']) && $ds['enable_breadcrumbs'] || !empty($ds['original_settings']['enable_breadcrumbs']) && $ds['original_settings']['enable_breadcrumbs']) {
            wp_enqueue_style('nextgen_basic_album_breadcrumbs_style', $this->object->get_static_url('photocrati-nextgen_basic_album#breadcrumbs.css'));
        }
        wp_enqueue_style('nextgen_pro_list_album', $this->get_static_url('photocrati-nextgen_pro_albums#nextgen_pro_list_album.css'));
        wp_enqueue_script('nextgen_pro_albums', $this->get_static_url('photocrati-nextgen_pro_albums#nextgen_pro_album_init.js'));
        // Enqueue the dynamic stylesheet
        $dyn_styles = C_Dynamic_Stylesheet_Controller::get_instance('all');
        $dyn_styles->enqueue($this->object->_get_css_class(), $this->array_merge_assoc($displayed_gallery->display_settings, array('id' => 'displayed_gallery_' . $displayed_gallery->id())));
        $this->enqueue_ngg_styles();
    }
}
/**
 * Class A_NextGen_Pro_List_Album_Form
 * @mixin C_Form
 * @adapts I_Form using "photocrati-nextgen_pro_list_album" context
 */
class A_NextGen_Pro_List_Album_Form extends A_NextGen_Pro_Album_Form
{
    function get_display_type_name()
    {
        return NGG_PRO_LIST_ALBUM;
    }
    /**
     * Adds pro-list-album specific fields to the defaults provided in A_NextGen_Pro_ALbums_Form
     */
    function _get_field_names()
    {
        $fields = parent::_get_field_names();
        $fields[] = 'nextgen_pro_list_album_description_color';
        $fields[] = 'nextgen_pro_list_album_description_size';
        return $fields;
    }
    function _render_nextgen_pro_list_album_description_color_field($display_type)
    {
        return $this->_render_color_field($display_type, 'description_color', 'Description color', $display_type->settings['description_color']);
    }
    function _render_nextgen_pro_list_album_description_size_field($display_type)
    {
        return $this->_render_number_field($display_type, 'description_size', 'Description size', $display_type->settings['description_size'], '', FALSE, '', 0);
    }
}
/**
 * Class A_NextGen_Pro_Grid_Album_Controller
 * @mixin C_Display_Type_Controller
 * @adapts I_Display_Type_Controller for "photocrati-nextgen_pro_grid_album" contexts
 */
class A_NextGen_Pro_Grid_Album_Controller extends Mixin_NextGen_Pro_Album_Controller
{
    function _get_css_class()
    {
        return 'nextgen_pro_grid_album';
    }
    function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
        $ds = $displayed_gallery->display_settings;
        if (!empty($ds['enable_breadcrumbs']) && $ds['enable_breadcrumbs'] || !empty($ds['original_settings']['enable_breadcrumbs']) && $ds['original_settings']['enable_breadcrumbs']) {
            wp_enqueue_style('nextgen_basic_album_breadcrumbs_style', $this->object->get_static_url('photocrati-nextgen_basic_album#breadcrumbs.css'));
        }
        wp_enqueue_script('jquery.dotdotdot', $this->object->get_static_url('photocrati-nextgen_basic_album#jquery.dotdotdot-1.5.7-packed.js'), array('jquery'));
        wp_enqueue_style('nextgen_pro_grid_album', $this->get_static_url('photocrati-nextgen_pro_albums#nextgen_pro_grid_album.css'));
        wp_enqueue_script('nextgen_pro_albums', $this->get_static_url('photocrati-nextgen_pro_albums#nextgen_pro_album_init.js'));
        // Enqueue the dynamic stylesheet
        $dyn_styles = C_Dynamic_Stylesheet_Controller::get_instance('all');
        $dyn_styles->enqueue($this->object->_get_css_class(), $this->array_merge_assoc($displayed_gallery->display_settings, array('id' => 'displayed_gallery_' . $displayed_gallery->id())));
        $this->enqueue_ngg_styles();
    }
}