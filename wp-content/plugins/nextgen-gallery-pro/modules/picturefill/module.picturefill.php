<?php

/**
{
Module: photocrati-nextgen_picturefull
}
 **/

class M_NextGen_PictureFill extends C_Base_Module
{
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
			'photocrati-nextgen_picturefill',
			'Picturefill',
			'Provides support for the picture element using the PictureFill polyfill library',
			'1.0.1',
			'Imagely',
			'https://www.imagely.com',
			$context
		);
	}

	function _register_hooks()
	{
		add_action('init', array(&$this, 'register_picturefill'));
	}

	function _register_adapters()
	{
		$this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_PictureFill_Display_Type_Controller');
		$this->get_registry()->add_adapter('I_GalleryStorage_Driver', 'A_PictureFill_Gallery_Storage');
	}

	function register_picturefill()
	{
		wp_register_script(
			'picturefill',
			C_Router::get_instance()->get_static_url('photocrati-nextgen_picturefill#picturefill.min.js'),
            array(),
            '3.0.2'
		);
	}

	static function _render_picture_source($image, $named_size, $retina_named_size=NULL, $media=NULL)
	{
		$storage = C_Gallery_Storage::get_instance();
		$srcsets = str_replace(' ', '%20', array( $storage->get_image_url($image, $named_size)));
		if ($retina_named_size) {
			$srcsets[] = str_replace(' ', '%20', $storage->get_image_url($image, $retina_named_size)) . ' 2x';
		}

		return sprintf(
			"<source srcset='%s' media='%s'>",
			implode(", ", $srcsets),
			$media
		);
	}

	/**
	 * Return the named size for a retina-version of another named size. E.g. if the named size
	 * that you provide is "thumbnails", this method will return the named size for the retina-version of
	 * the thumbnail
	 * @param $image
	 * @param $original_named_size
	 *
	 * @return null
	 */
	static function get_retina_named_size($image, $original_named_size)
	{
		// Generate a named size for the 2x version
		$retina_named_size = $original_named_size;

		// Don't generate Retina image if "resize images upon upload" isn't enabled
		if ((C_NextGen_Settings::get_instance()->get('imgAutoResize', FALSE))) {
			$storage        = C_Gallery_Storage::get_instance();
			$dynthumbs      = C_Dynamic_Thumbnails_Manager::get_instance();

			// Copy the original image generation parameters, but double the image size
			$retina_params  = NULL;
			if ($dynthumbs && $dynthumbs->is_size_dynamic($original_named_size)) {
				$retina_params = $dynthumbs->get_params_from_name($original_named_size, true);
			}
			else {
				$retina_params = $storage->get_image_size_params($image, $original_named_size);
			}

			if ($retina_params)
			{
				// We need to ensure that our original image is large enough to generate the Retina/2x image.
				if (!empty($retina_params['width']))
					$retina_params['width'] *= 2;
				if (!empty($retina_params['height']))
					$retina_params['height'] *= 2;
				if (isset( $retina_params['crop_frame']))
					$retina_params['crop'] = true;

				// If a backup exists, we'll generate from that
				if (($backup_abspath = $storage->get_image_abspath($image, 'backup', TRUE))) {
					$backup_dimensions = $storage->get_image_dimensions($image, 'backup');
					if (!isset($backup_dimensions['width']) || !isset($backup_dimensions['height'])) {
						$size = getimagesize($backup_abspath);
						if (is_array($size) && isset($size[0]) && isset($size[1])) {
							$backup_dimensions['width'] = $size[0];
							$backup_dimensions['height']= $size[1];
						}
					}
					if (isset($backup_dimensions['width'])
					&&  isset($backup_dimensions['height'])
					&&  isset($retina_params['width'])
					&&  isset($retina_params['height']))
					{
						if ( $retina_params['width'] >= $backup_dimensions['width'] || $retina_params['height'] >= $backup_dimensions['height'] ) {
							$retina_params['width']  = $backup_dimensions['width'];
							$retina_params['height'] = $backup_dimensions['height'];
						}
						$retina_named_size = $dynthumbs->get_size_name( $retina_params );
					}
					else $retina_named_size = 'full';
				}
				else {
					$dimensions = $storage->get_image_dimensions($image);
					if (isset($dimensions['width']) && isset($dimensions['height'])) {
						if ($retina_params['width'] >= $dimensions['width'] || $retina_params['height'] >= $dimensions['height']) {
							$retina_named_size ='full';
						}
						else $retina_named_size = $dynthumbs->get_size_name($retina_params);
					}

				}
			}
		}

		return $retina_named_size;
	}

	/**
	 * Renders a picture element for a particular image at at named size.
	 *
	 * A source is added for the named size, as well as a retina version
	 * @param $image
	 * @param $params_or_named_size
	 * @param bool $echo
	 *
	 * @return string
	 */
	static function render_picture_element($image, $params_or_named_size, $attrs=array(), $echo=TRUE)
	{
		$retval = '';

		if (!is_object($image))
			$image = C_Image_Mapper::get_instance()->find($image);

		if ($image)
		{
			$parts = self::prepare_picture_element($image, $params_or_named_size, $attrs, $echo);
			$retval = implode("\n", array(
				'<picture>',
				"\t",
				implode("\n\t", $parts['sources']),
				"<img {$parts['attr_str']}/>",
				'</picture>'));
		}

		if ($echo)
			echo $retval;

		return $retval;
	}

	static function prepare_picture_element($image, $params_or_named_size, $attrs = array())
	{
		$retval = array();

		$dynthumbs = C_Dynamic_Thumbnails_Manager::get_instance();
		$storage    = C_Gallery_Storage::get_instance();
		$sources    = array();
		$srcsets    = array();
		$srcsets2   = array(); // returned unprocessed, unlike srcsets above

		// Get the named size to display
		$named_size = $params_or_named_size;
		if (is_array($params_or_named_size))
			$named_size = $dynthumbs->get_size_name($params_or_named_size);
		$image_url = str_replace(' ', '%20', $storage->get_image_url($image, $named_size, TRUE));

		// Get retina named size to display
		$retina_named_size = self::get_retina_named_size($image, $named_size);
		$retina_url        = str_replace(' ', '%20', $storage->get_image_url($image, $retina_named_size));

		// Set attributes
		$srcsets[]  = isset($_REQUEST['force_retina']) ? $retina_url : $image_url;
		$srcsets2['original'] = isset($_REQUEST['force_retina']) ? $retina_url : $image_url;
		$dimensions = $storage->get_image_dimensions($image, $named_size);
		if (!array_key_exists('title', $attrs)) $attrs['title'] = $image->alttext;
		if (!array_key_exists('alt',   $attrs)) $attrs['alt']   = $image->alttext;

		if ($dimensions && isset($dimensions['width']) && isset($dimensions['height']))
		{
			if (!array_key_exists('width',  $attrs)) $attrs['width']  = $dimensions['width'];
			if (!array_key_exists('height', $attrs)) $attrs['height'] = $dimensions['height'];
			if (!array_key_exists('style', $attrs)) $attrs['style'] = "max-width:{$attrs['width']}px;max-height:{$attrs['height']}px";
		}

		// Add sources
		if ($retina_named_size != $named_size)
		{
			$sources[] = self::_render_picture_source($image, $named_size, $retina_named_size);
			$srcsets[] = $retina_url . ' 2x';
			$srcsets2['hdpi'] = $retina_url;
		}
		else {
			$sources[] = self::_render_picture_source($image, $named_size);
		}

		// Create attribute strings
		$attrs['srcset'] = implode(", ", $srcsets);
		$attr_str_array = array();
		foreach ($attrs as $key => $value) {
			if (!is_null($value))
				$attr_str_array[$key] = esc_attr($key) . '="' . esc_attr($value) . '"';
		}
		$attr_strs = implode(' ', $attr_str_array);

		$retval['use_hdpi']           = ($retina_named_size != $named_size);
		$retval['srcsets']            = $srcsets;
		$retval['srcsets_unfiltered'] = $srcsets2;
		$retval['sources']            = $sources;
		$retval['attr_str']           = $attr_strs;
		$retval['attr_array']         = $attr_str_array;

		return $retval;
	}

	function get_type_list()
	{
		return array(
			'A_PictureFill_Display_Type_Controller'  =>  'adapter.picturefill_display_type_controller.php',
			'A_PictureFill_Gallery_Storage'          =>  'adapter.picturefill_gallery_storage.php'
		);
	}
}

new M_NextGen_PictureFill;
