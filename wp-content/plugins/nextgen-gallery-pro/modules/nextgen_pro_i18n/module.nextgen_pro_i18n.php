<?php
/***
{
Module: photocrati-nextgen_pro_i18n,
Depends: {photocrati-fs}
}
 ***/
class M_NextGen_Pro_I18N extends C_Base_Module
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
            'photocrati-nextgen_pro_i18n',
            'Pro Internationalization',
            "Adds I18N resources and methods",
            '0.15',
            'http://www.imagely.com/languages/',
            'Imagely',
            'https://www.imagely.com',
            $context
        );
    }

    function _register_hooks()
    {
        add_action('init', array(&$this, 'register_translation_hooks'), 2);
    }

    function register_translation_hooks()
    {
        $fs = C_Fs::get_instance();
        $dir = str_replace(
            $fs->get_document_root('plugins'),
            '',
            $fs->get_abspath('lang', 'photocrati-nextgen_pro_i18n')
        );

        // Load text domain
        load_plugin_textdomain('nextgen-gallery-pro', false, $dir);
    }

    static public function get_kses_allowed_html()
    {
        global $allowedtags;

        $our_keys = array(
            'a'      => array('href'  => array(),
                              'class' => array(),
                              'title' => array()),
            'br'     => array(),
            'em'     => array(),
            'strong' => array(),
            'u'      => array(),
            'p'      => array('class' => array()),
            'div'    => array('class' => array(), 'id' => array()),
            'span'   => array('class' => array(), 'id' => array())
        );

        return array_merge_recursive($allowedtags, $our_keys);
    }
}

new M_NextGen_Pro_I18N();
