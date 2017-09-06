<?php


//自定义域页面
$new_meta_boxes_page =
array(

    "seo_title" => array(   
        "name" => "seo_title",      
        "title" => "自定义SEO标题,不填写择默认调取标题",   
        'std' => '',
        'type' => 'text',
    ),

    "seo_key" => array(   
        "name" => "seo_key",      
        "title" => "自定义SEO关键词key,多个关键词用英文逗号隔开，不填写择默认调取标签或者标题",   
        'std' => '',
        'type' => 'text',
    ),

    "seo_description" => array(   
        "name" => "seo_description",      
        "title" => "自定义SEO描述,不填写择默认调取内容指定截断的字数或者摘要",   
        'std' => '',
        'type' => 'textarea',
    ),
 
);


function new_meta_boxes_page() {   
    global $post, $new_meta_boxes_page;   
    foreach($new_meta_boxes_page as $meta_box) {   
        //获取保存的是   
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);   
        if($meta_box_value != "")      
            $meta_box['std'] = $meta_box_value;//将默认值替换为以保存的值   
           
        echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';   
        //通过选择类型输出不同的html代码   
        switch ( $meta_box['type'] ){  
                        
            case 'title':   
                echo'<h4>'.$meta_box['title'].'</h4>';   
                break;   
            case 'text':   
                echo'<h4>'.$meta_box['title'].'</h4>';   
                echo '<input type="text" size="40" name="'.$meta_box['name'].'_value" value="'.$meta_box['std'].'" /><br />';   
                break; 
                
            case 'uploader':   
                echo'<h4>'.$meta_box['title'].'</h4>';   
                echo '<input class="metabox_upload_input" type="text" size="80" value="'.$meta_box['std'].'" name="'.$meta_box['name'].'_value"/>';   
                echo '<input type="button" value="上传" class="metabox_upload_bottom"/>';
                echo '<br/>';  
                 //图片预览框   
                if($meta_box['std'] != ''){   
                echo '<span id="'.$meta_box['name'].'_value_img"><img src='.$meta_box['std'].' alt="" /></span>';}  
                wp_enqueue_script('kriesi_custom_fields_js', get_template_directory_uri(). '/js/metaup.js');  
                 wp_enqueue_style( 'meta_box_css', get_stylesheet_directory_uri().'/includes/meta_box_style.css'); 
                break;  
            
            case 'textarea':   
                echo'<h4>'.$meta_box['title'].'</h4>';   
                echo '<textarea cols="60" rows="4" name="'.$meta_box['name'].'_value">'.$meta_box['std'].'</textarea><br />';   
                break;   
            case 'dropdown':   
                echo'<h4>'.$meta_box['title'].'</h4>';   
                if($meta_box['subtype'] == 'cat'){   
                    $select = 'Select category';   
                    $entries = get_categories('title_li=&orderby=name&hide_empty=0');//获取分类   
                }   
                echo '<p><select name="'.$meta_box['name'].'_value"> ';   
                echo '<option value="">'.$select .'</option>  ';   
                foreach ($entries as $key => $entry){   
                    $id = $entry->term_id;   
                    $title = $entry->name;   
                    if ( $meta_box['std'] == $id ){   
                        $selected = "selected='selected'";   
                    }else{   
                        $selected = "";   
                    }   
                    echo "<option $selected value='". $id."'>". $title."</option>";   
                }   
                echo '</select><br />';   
                break;   
            case 'radio':   
                echo'<h4>'.$meta_box['title'].'</h4>';   
                $counter = 1;   
                foreach( $meta_box['buttons'] as $radiobutton ) {   
                    $checked ="";   
                    if(isset($meta_box['std']) && $meta_box['std'] == $counter) {   
                        $checked = 'checked = "checked"';   
                    }   
                    echo '<input '.$checked.' type="radio" class="kcheck" value="'.$counter.'" name="'.$meta_box['name'].'_value"/>'.$radiobutton;   
                    $counter++;   
                }   
                break;   
            case 'checkbox':   
                echo'<h4>'.$meta_box['title'].'</h4>';   
                if( isset($meta_box['std']) && $meta_box['std'] == 'true' )   
                    $checked = 'checked = "checked"';   
                else  
                    $checked  = '';    
                echo '<input type="checkbox" id="'.$meta_box['name'].'_value"  class="metabox-checkbox" name="'.$meta_box['name'].'_value" value="true"  '.$checked.' /><div class="checkbox-wrapper"><label for="'.$meta_box['name'].'_value" class="metaboxcheckbox-label" ></label></div>';   


            break;   
            
               
        }             
    }      
} 

function create_meta_box() {
    global $theme_name;

    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'new-meta-boxes', 'SEO扩展', 'new_meta_boxes_page', 'page', 'normal', 'high' );
    }
}

function save_postdata_page( $post_id ) {   
    global $post, $new_meta_boxes_page;   
  
    foreach($new_meta_boxes_page as $meta_box) {   
        if ( @!wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {   
            return $post_id;   
        }   
  
        if ( 'page' == $_POST['post_type'] ) {   
            if ( !current_user_can( 'edit_page', $post_id ))   
                return $post_id;   
        }    
        else {   
            if ( !current_user_can( 'edit_post', $post_id ))   
                return $post_id;   
        }   
  
        $data = $_POST[$meta_box['name'].'_value'];   
  
        if(get_post_meta($post_id, $meta_box['name'].'_value') == "")   
            add_post_meta($post_id, $meta_box['name'].'_value', $data, true);   
        elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))   
            update_post_meta($post_id, $meta_box['name'].'_value', $data);   
        elseif($data == "")   
            delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_key,$meta_box['name'].'_value', true));   
    }   
}  

add_action('admin_menu', 'create_meta_box');   
add_action('save_post', 'save_postdata_page');  




//分类term
add_action( 'category_add_form_fields', 'suxing_new_term_seo_title_field' );
add_action( 'post_tag_add_form_fields', 'suxing_new_term_seo_title_field' );

function suxing_new_term_seo_title_field() {
    wp_nonce_field( basename( __FILE__ ), 'suxing_term_seo_nonce' ); ?>
    <div>
        <p>自定义SEO</p>
        <div class="form-field suxing-term-seo-wrap">
            <label for="suxing-term-title">自定义标题</label>
            <input type="text" name="suxing_term_title" id="suxing-term-title" />
        </div>
        <div class="form-field suxing-term-seo-wrap">
            <label for="suxing-term-keywords">自定义关键词</label>
            <input type="text" name="suxing_term_keywords" id="suxing-term-keywords"  />
        </div>
        <div class="form-field suxing-term-seo-wrap">
            <label for="suxing-term-description">自定义描述</label>
            <textarea name="suxing_term_description" id="suxing-term-keywords" rows="5" cols="40"></textarea>
        </div>
    </div>
<?php }

add_action( 'category_edit_form_fields', 'suxing_edit_term_seo_field' );

function suxing_edit_term_seo_field( $term ) {

    $title   = get_term_meta( $term->term_id, 'suxing_term_title', true );
    $keywords   = get_term_meta( $term->term_id, 'suxing_term_keywords', true );
    $description   = get_term_meta( $term->term_id, 'suxing_term_description', true );

    ?>

    <tr class="form-field suxing-term-seo-wrap">
        <th scope="row"><label for="suxing-term-title">自定义标题</label></th>
        <td>
            <input type="text" name="suxing_term_title" id="suxing-term-title" value="<?php echo esc_attr( $title ); ?>" />
        </td>
    </tr>

    <tr class="form-field suxing-term-seo-wrap">
        <th scope="row"><label for="suxing-term-keywords">自定义关键词</label></th>
        <td>
            <input type="text" name="suxing_term_keywords" id="suxing-term-keywords" value="<?php echo esc_attr( $keywords ); ?>" />
        </td>
    </tr>

    <tr class="form-field suxing-term-seo-wrap">
        <th scope="row"><label for="suxing-term-description">自定义描述</label></th>
        <td>
            <textarea name="suxing_term_description" id="suxing-term-description"><?php echo esc_attr( $description ); ?></textarea>
        </td>
    </tr>

    <?php echo wp_nonce_field( basename( __FILE__ ), 'suxing_term_seo_nonce' );
}

add_action( 'create_category', 'suxing_save_term_seo' );
add_action( 'edit_category',   'suxing_save_term_seo' );

function suxing_save_term_seo( $term_id ) {
    if ( ! isset( $_POST['suxing_term_seo_nonce'] ) || ! wp_verify_nonce( $_POST['suxing_term_seo_nonce'], basename( __FILE__ ) ) )
        return;

    $title = isset( $_POST['suxing_term_title'] ) ? $_POST['suxing_term_title'] : '';
    $keywords = isset( $_POST['suxing_term_keywords'] ) ? $_POST['suxing_term_keywords'] : '';
    $description = isset( $_POST['suxing_term_description'] ) ? $_POST['suxing_term_description'] : '';

    if ( '' === $title ) {
        delete_term_meta( $term_id, 'suxing_term_title' );
    } else {
        update_term_meta( $term_id, 'suxing_term_title', $title );
    }
    if ( '' === $keywords ) {
        delete_term_meta( $term_id, 'suxing_term_keywords' );
    } else {
        update_term_meta( $term_id, 'suxing_term_keywords', $keywords );
    }
    if ( '' === $description ) {
        delete_term_meta( $term_id, 'suxing_term_description' );
    } else {
        update_term_meta( $term_id, 'suxing_term_description', $description );
    }
}
