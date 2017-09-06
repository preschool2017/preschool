<?php
/**
 * Class A_NextGen_Pro_Film_Controller
 * @mixin C_Display_Type_Controller
 * @adapts I_Display_Type_Controller using "photocrati-nextgen_pro_film" context
 */
class A_NextGen_Pro_Film_Controller extends Mixin
{
    function initialize()
    {
        parent::initialize();
        $this->object->add_mixin('Mixin_NextGen_Basic_Pagination');
    }
    function is_cachable()
    {
        return FALSE;
    }
    function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
        wp_enqueue_style('nextgen_pro_film', $this->get_static_url('photocrati-nextgen_pro_film#nextgen_pro_film.css'));
        wp_enqueue_style('nextgen_pagination_style', $this->get_static_url('photocrati-nextgen_pagination#style.css'));
        $this->enqueue_ngg_styles();
    }
    function index_action($displayed_gallery, $return = FALSE)
    {
        $id = $displayed_gallery->id();
        $display_settings = $displayed_gallery->display_settings;
        $current_page = (int) $this->param('nggpage', $displayed_gallery->id(), 1);
        if (!isset($display_settings['images_per_page'])) {
            $display_settings['images_per_page'] = C_NextGen_Settings::get_instance()->images_per_page;
        }
        $offset = $display_settings['images_per_page'] * ($current_page - 1);
        $total = $displayed_gallery->get_entity_count();
        $images = $displayed_gallery->get_included_entities($display_settings['images_per_page'], $offset);
        if (in_array($displayed_gallery->source, array('random', 'recent'))) {
            $display_settings['disable_pagination'] = TRUE;
        }
        if ($images) {
            if ($display_settings['images_per_page'] && !$display_settings['disable_pagination']) {
                $pagination_result = $this->object->create_pagination($current_page, $total, $display_settings['images_per_page']);
            }
        }
        $pagination = !empty($pagination_result['output']) ? $pagination_result['output'] : NULL;
        // Get named size of thumbnail images
        $thumbnail_size_name = 'thumbnail';
        if ($display_settings['override_thumbnail_settings']) {
            $dynthumbs = C_Dynamic_Thumbnails_Manager::get_instance();
            $dyn_params = array('width' => $display_settings['thumbnail_width'], 'height' => $display_settings['thumbnail_height']);
            if ($display_settings['thumbnail_quality']) {
                $dyn_params['quality'] = $display_settings['thumbnail_quality'];
            }
            if ($display_settings['thumbnail_crop']) {
                $dyn_params['crop'] = true;
            }
            if ($display_settings['thumbnail_watermark']) {
                $dyn_params['watermark'] = true;
            }
            $thumbnail_size_name = $dynthumbs->get_size_name($dyn_params);
        }
        // Calculate image statistics
        $stats = $this->object->get_entity_statistics($images, $thumbnail_size_name, TRUE);
        $images = $stats['entities'];
        $display_settings['longest'] = $stats['longest'];
        $display_settings['widest'] = $stats['widest'];
        // Enqueue dynamic stylesheet
        $dyn_styles = C_Dynamic_Stylesheet_Controller::get_instance('all');
        $dyn_styles->enqueue('nextgen_pro_film', $this->array_merge_assoc($display_settings, array('id' => $id)));
        $params = array('images' => $images, 'storage' => C_Gallery_Storage::get_instance(), 'thumbnail_size_name' => $thumbnail_size_name, 'effect_code' => $this->object->get_effect_code($displayed_gallery), 'id' => $id, 'pagination' => $pagination);
        $params = $this->object->prepare_display_parameters($displayed_gallery, $params);
        // Render view/template
        return preg_replace('~>\\s*\\n\\s*<~', '><', $this->render_view('photocrati-nextgen_pro_film#nextgen_pro_film', $params, $return));
    }
}
/**
 * Class A_NextGen_Pro_Film_Form
 * @mixin C_Form
 * @adapts I_Form using "photocrati-nextgen_pro_film" context
 */
class A_NextGen_Pro_Film_Form extends Mixin_Display_Type_Form
{
    function get_display_type_name()
    {
        return NGG_PRO_FILM;
    }
    function enqueue_static_resources()
    {
        wp_enqueue_script($this->object->get_display_type_name() . '-js', $this->get_static_url('photocrati-nextgen_pro_film#settings.js'));
        $atp = C_Attach_Controller::get_instance();
        if ($atp != null && $atp->has_method('mark_script')) {
            $atp->mark_script($this->object->get_display_type_name() . '-js');
        }
    }
    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array('thumbnail_override_settings', 'nextgen_pro_film_images_per_page', 'nextgen_pro_film_image_spacing', 'nextgen_pro_film_border_size', 'nextgen_pro_film_frame_size', 'nextgen_pro_film_border_color', 'nextgen_pro_film_frame_color', 'display_type_view');
    }
    /**
     * Renders the images_per_page settings field
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    function _render_nextgen_pro_film_images_per_page_field($display_type)
    {
        return $this->_render_number_field($display_type, 'images_per_page', __('Images per page', 'nextgen-gallery-pro'), $display_type->settings['images_per_page'], __('"0" will display all images at once', 'nextgen-gallery-pro'), FALSE, __('# of images', 'nextgen-gallery-pro'), 0);
    }
    function _render_nextgen_pro_film_border_size_field($display_type)
    {
        return $this->_render_number_field($display_type, 'border_size', __('Border size', 'nextgen-gallery-pro'), $display_type->settings['border_size'], '', FALSE, '', 0);
    }
    function _render_nextgen_pro_film_border_color_field($display_type)
    {
        return $this->_render_color_field($display_type, 'border_color', __('Border color', 'nextgen-gallery-pro'), $display_type->settings['border_color']);
    }
    function _render_nextgen_pro_film_frame_size_field($display_type)
    {
        return $this->_render_number_field($display_type, 'frame_size', __('Frame size', 'nextgen-gallery-pro'), $display_type->settings['frame_size'], '', FALSE, '', 0);
    }
    function _render_nextgen_pro_film_frame_color_field($display_type)
    {
        return $this->_render_color_field($display_type, 'frame_color', __('Frame color', 'nextgen-gallery-pro'), $display_type->settings['frame_color']);
    }
    function _render_nextgen_pro_film_image_spacing_field($display_type)
    {
        return $this->_render_number_field($display_type, 'image_spacing', __('Image spacing', 'nextgen-gallery-pro'), $display_type->settings['image_spacing'], '', FALSE, '', 0);
    }
}
/**
 * Class A_NextGen_Pro_Film_Mapper
 * @mixin C_Display_Type_Mapper
 * @adapts I_Display_Type_Mapper
 */
class A_NextGen_Pro_Film_Mapper extends Mixin
{
    function set_defaults($entity)
    {
        $this->call_parent('set_defaults', $entity);
        if ($entity->name == NGG_PRO_FILM) {
            $settings = C_NextGen_Settings::get_instance();
            $this->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 0);
            $this->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
            $this->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
            $this->_set_default_value($entity, 'settings', 'thumbnail_quality', $settings->thumbquality);
            $this->_set_default_value($entity, 'settings', 'thumbnail_crop', 0);
            $this->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);
            $this->_set_default_value($entity, 'settings', 'images_per_page', $settings->galImages);
            $this->_set_default_value($entity, 'settings', 'disable_pagination', 0);
            $this->_set_default_value($entity, 'settings', 'border_color', '#CCCCCC');
            $this->_set_default_value($entity, 'settings', 'border_size', 1);
            $this->_set_default_value($entity, 'settings', 'frame_color', '#FFFFFF');
            $this->_set_default_value($entity, 'settings', 'frame_size', 20);
            $this->_set_default_value($entity, 'settings', 'image_spacing', 5);
            $this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'always');
            $this->_set_default_value($entity, 'settings', 'display_type_view', 'default');
        }
    }
}