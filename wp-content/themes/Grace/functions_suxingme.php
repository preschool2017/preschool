<?php

add_action( 'wp_enqueue_scripts', 'SuStatic' );

function SuStatic() {

    wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), THEME_VERSION, true ); 
  	wp_register_script( 'resizeEnd', get_template_directory_uri() . '/js/jQuery.resizeEnd.js', array('jquery'), THEME_VERSION, true ); 
    wp_register_script( 'mcustomscrollbar', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.concat.min.js', array('jquery'), THEME_VERSION, true ); 
    wp_register_script( 'suxingme', get_template_directory_uri() . '/js/suxingme.js', array('jquery'), THEME_VERSION, true ); 

    wp_register_script( 'owl', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), THEME_VERSION, true );  
    wp_register_script( 'autohidingnavbar', get_template_directory_uri() . '/js/jquery.bootstrap-autohidingnavbar.min.js', array('bootstrap'), THEME_VERSION, true ); 
    wp_register_script( 'comments', get_template_directory_uri() . '/ajax-comment/ajax-comment.js', array('jquery'), THEME_VERSION, true ); 
    wp_register_script( 'baidushare', get_template_directory_uri() . '/js/baidushare.js', array('jquery'), THEME_VERSION, true ); 
    wp_register_script( 'fancybox', get_template_directory_uri() . '/js/fancybox.js', array('jquery'), THEME_VERSION, true ); 
    wp_register_script( 'lazyload', get_template_directory_uri() . '/js/jquery.lazyload.min.js', array('jquery'), THEME_VERSION, true );
    wp_register_script( 'sticky-kit', get_template_directory_uri() . '/js/jquery.sticky-kit.min.js', array('jquery'), THEME_VERSION, true );
    wp_register_script( 'Grace-html5', get_template_directory_uri() . '/js/html5shiv.js', array('jquery'), THEME_VERSION, false );
    wp_register_script( 'Grace-respond', get_template_directory_uri() . '/js/respond.min.js', array('jquery'), THEME_VERSION, false );
    wp_register_script( 'wow', get_template_directory_uri() . '/js/wow.min.js', array('jquery'), THEME_VERSION, true );
    wp_register_script( 'form', get_template_directory_uri() . '/js/jquery.form.js', array('jquery'), THEME_VERSION, true );

    
    
    wp_register_style( 'fontello', get_template_directory_uri() . '/includes/font-awesome/css/fontello.css', array(), THEME_VERSION, 'all' );
    wp_register_style( 'carousel', get_template_directory_uri() . '/includes/css/owl.carousel.css', array(), THEME_VERSION, 'all' );
    wp_register_style( 'mcustomscrollbar', get_template_directory_uri() . '/includes/css/jquery.mCustomScrollbar.min.css', array(), THEME_VERSION, 'all' );
    wp_register_style( 'Grace-style', get_stylesheet_uri(), array(), THEME_VERSION, 'all' );
    wp_register_style( 'bootstrap', get_template_directory_uri() . '/includes/css/bootstrap.min.css', array(), THEME_VERSION, 'all' );

    wp_register_style( 'animate', get_template_directory_uri() . '/includes/css/animate.css', array(), THEME_VERSION, 'all' );
    

	

    if ( !is_admin() ) {

        if( is_home() && suxingme('sideroll_index_s') ){
            wp_enqueue_script( 'sticky-kit' );
            $roll = true;
        }else if( (is_category() || is_tag() || is_search()) && suxingme('sideroll_list_s') ){
            wp_enqueue_script( 'sticky-kit' );
            $roll = true;
        }else if( is_single() && suxingme('sideroll_post_s') ){
            wp_enqueue_script( 'sticky-kit' );
            $roll = true;
        }else if( is_page() && suxingme('sideroll_page_s') ){
            wp_enqueue_script( 'sticky-kit' );
            $roll = true;
        } else{
            $roll = false;
        }
        
        wp_enqueue_script( 'jquery' ); 
        wp_enqueue_script( 'bootstrap' ); 
        wp_enqueue_script( 'mcustomscrollbar' ); 
        
        
        if(is_singular() ){ 
	       wp_enqueue_script( 'resizeEnd' );
	    }
        
        wp_enqueue_script( 'suxingme' );

        if( is_home() && ! is_paged() && suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_1'){
            $index_slide_style = 'index_slide_sytle_1';
        } elseif( is_home() && ! is_paged() && suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_2' ){
            $index_slide_style = 'index_slide_sytle_2';
        } elseif( is_home() && ! is_paged() && suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_3' ){
            $index_slide_style = 'index_slide_sytle_3';
        }
         elseif( is_home() && ! is_paged() && suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_4' ){
            $index_slide_style = 'index_slide_sytle_4';
        } else {
            $index_slide_style = 'index_no_slide';
        }

        if( suxingme('suxingme_head_fixed', true) ){
            wp_enqueue_script( 'autohidingnavbar' );
        }
        
        if( suxingme('suxingme_timthumb_lazyload',true ) ) {         
            wp_enqueue_script( 'lazyload' );
        }

        if ( !is_admin() && is_home() && suxingme('suxing_slide_img_button') != 'index_no_slide' ){ 
            wp_enqueue_script( 'owl' );
            wp_enqueue_style( 'carousel' );
        }
        
        wp_enqueue_style( 'mcustomscrollbar' );
        if( suxingme('suxing_wow_index_slide',false) || suxingme('suxing_wow_index_3cat',false) || suxingme('suxing_wow_single_list',true) || suxingme('suxing_wow_loadmore_btn',false) || suxingme('suxing_wow_single_embed_post',false) || suxingme('suxing_wow_single_related',true) || suxingme('suxing_wow_sidebar',false) ){
            wp_enqueue_style( 'animate' );
            wp_enqueue_script( 'wow' ); 
            $wow =  true;
        } else {
            $wow = false;
        }

        wp_enqueue_style( 'fontello' );
        wp_enqueue_style( 'bootstrap' );
        wp_enqueue_style( 'Grace-style' );


        
        
        wp_enqueue_script( 'Grace-html5' );
        wp_script_add_data( 'Grace-html5', 'conditional', 'lt IE 9' );
        wp_enqueue_script( 'Grace-respond' );
        wp_script_add_data( 'Grace-respond', 'conditional', 'lt IE 9' );    
    }

    if ( !is_admin() && is_singular() ){ 
        wp_enqueue_script( 'comments' );

    }

    if ( !is_admin() && is_single() && suxingme('suxingme_baidushare') ){ 
        wp_enqueue_script( 'baidushare' );
    }

    if ( !is_admin() && is_singular() && suxingme('suxingme_fancybox',false ) ) { 
        wp_enqueue_script( 'fancybox' );
    }

    if( is_page() && get_queried_object_id() == suxingme('contribute_page_id') ){
        wp_enqueue_script( 'form' );
        wp_enqueue_media();
    }

    wp_localize_script( 'suxingme', 'suxingme_url', 
        array(
            "url_ajax"      => admin_url("admin-ajax.php"),
            "url_theme"     => get_template_directory_uri(),
            "roll"          => $roll,
            "headfixed"     => suxingme("suxingme_head_fixed", true ),
            "slidestyle"    => $index_slide_style,
            "wow"           => $wow
        ) 
    ); 
}  


add_action('wp_head', 'suxing_wp_head');
function suxing_wp_head() { 
    if( suxingme('headcode') ) echo "<!--ADD_CODE_HEADER_START-->\n".suxingme('headcode')."\n<!--ADD_CODE_HEADER_END-->\n";
}

add_action('wp_footer', 'suxing_wp_footer');
function suxing_wp_footer() { 
    if( suxingme('footcode') ) echo "<!--ADD_CODE_FOOTER_START-->\n".suxingme('footcode')."\n<!--ADD_CODE_FOOTER_END-->\n";
    if ( is_singular() && suxingme('suxingme_fancybox',true) ) {
    echo'<script type="text/javascript">jQuery(document).ready(function($) {$(".fancybox").fancybox()});</script>';
    }
}

// 屏蔽WordPress默认小工具
add_action( 'widgets_init', 'my_unregister_widgets' );   
function my_unregister_widgets() {   
 
    unregister_widget( 'WP_Widget_Archives' );   
    unregister_widget( 'WP_Widget_Calendar' );   
    unregister_widget( 'WP_Widget_Categories' );   
    unregister_widget( 'WP_Widget_Links' );   
    unregister_widget( 'WP_Widget_Meta' );   
    unregister_widget( 'WP_Widget_Pages' );   
    unregister_widget( 'WP_Widget_Recent_Comments' );   
    unregister_widget( 'WP_Widget_Recent_Posts' );   
    unregister_widget( 'WP_Widget_RSS' );   
    unregister_widget( 'WP_Widget_Search' );   
    unregister_widget( 'WP_Widget_Tag_Cloud' );   
    unregister_widget( 'WP_Nav_Menu_Widget' );   
    
}

//自动修改Wordpress文章、评论、缩略图片的IMG属性
function add_image_placeholders( $content ) {
    // Don't lazyload for feeds, previews, mobile
    if( is_feed() || is_preview() || ( function_exists( 'is_mobile' ) && is_mobile() ) )
        return $content;
    // Don't lazy-load if the content has already been run through previously
    if ( false !== strpos( $content, 'data-original' ) )
        return $content;
    // In case you want to change the placeholder image
    $placeholder_image = apply_filters( 'lazyload_images_placeholder_image', get_template_directory_uri() . '/img/lazy.png' );
    // This is a pretty simple regex, but it works
    $content = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf( '<img${1}src="%s" data-original="${2}"${3}><noscript><img${1}src="${2}"${3}></noscript>', $placeholder_image ), $content );
    return $content;
}

/** 修改网站标题连接符 **/
function page_sign(){
    $sign = '';
    if(suxingme('page_sign')){
        $sign .= ' ';
        $sign .=  suxingme('page_sign');
        $sign .= ' ';
    }else{
       $sign =  ' - ';
    }
    return $sign;
}

/* 评论作者链接新窗口打开 */
function specs_comment_author_link() {
    $url    = get_comment_author_url();
    $author = get_comment_author();
    if ( empty( $url ) || 'http://' == $url )
        return $author;
    else
        return "<a target='_blank' href='$url' rel='external nofollow' class='url'>$author</a>";
}
add_filter('get_comment_author_link', 'specs_comment_author_link');


/**
 * 禁用：移除 WordPress 4.2 中前台自动加载的 emoji 脚本
 * Disable the emoji's
 */
function disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );
 
/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param    array  $plugins  
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}


//自定义登录页面风格（图片轮换背景）
function uazoh_custom_login_page_imgbackground() {
    $num = suxingme('suxingme_login_bgp_num',3);
    $arr = '';
    for ($i=1; $i < $num+1 ; $i++) { 
        $arr .= '"'.suxingme('suxingme_login_bgp_'.$i).'",';
    }
    $arr = substr($arr, 0, -1);
    echo '<script type="text/javascript" src="/wp-includes/js/jquery/jquery.js?ver=1.11.1"></script>
        <script src="'.get_template_directory_uri().'/js/jquery.backstretch.min.js"></script>
        <script>
            jQuery(function(){
            var imgsrc = "'.get_template_directory_uri().'/img/login_page_bg";
            var listArr = ['.$arr.'];
            jQuery(\'.login\').backstretch(listArr, {fade: 1000,duration: 5000});});
        </script>';
}
add_action('login_head', 'uazoh_custom_login_page_imgbackground');

//自定义登录页面风格
function uazoh_custom_login_page() {
echo'<style type="text/css">
body { background: none; }
#login {width: 320px;margin: auto;background: #FFF;margin-top: 8%;padding: 20px;border-radius: 3px;box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.2);}
.login form {margin-top: 0;margin-left: 0;padding:6px 24px 10px;-webkit-box-shadow:none;box-shadow:none;}
.login form .forgetmenot{float:none}
.login .button-primary{float:none;background-color: #494949;font-weight: bold;color: #fff;width: 100%;height: 40px;border-width: 0;border-color:none}
#login form p.submit{padding: 20px 0 0;}
.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover{background:#1F1F1F}
.wp-core-ui .button.button-large{height:40px;line-height:38px;font-size:16px;}
input{outline:none!important}
</style>';
}
add_action('login_head', 'uazoh_custom_login_page');

//修复 WordPress 找回密码提示“抱歉，该key似乎无效”

function reset_password_message( $message, $key ) {
 if ( strpos($_POST['user_login'], '@') ) {
 $user_data = get_user_by('email', trim($_POST['user_login']));
 } else {
 $login = trim($_POST['user_login']);
 $user_data = get_user_by('login', $login);
 }
 $user_login = $user_data->user_login;
 $msg = __('有人要求重设如下帐号的密码：'). "\r\n\r\n";
 $msg .= network_site_url() . "\r\n\r\n";
 $msg .= sprintf(__('用户名：%s'), $user_login) . "\r\n\r\n";
 $msg .= __('若这不是您本人要求的，请忽略本邮件，一切如常。') . "\r\n\r\n";
 $msg .= __('要重置您的密码，请打开下面的链接：'). "\r\n\r\n";
 $msg .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') ;
 return $msg;
}
add_filter('retrieve_password_message', 'reset_password_message', null, 2);

/**
 * Display tags width hot tag.
 *WordPress 标签云 – 带热门标签
 * @since Pure 1.0
 */
function get_hot_tag_list( $num = null , $hot = null , $offset = null){
    $num = $num ? $num : 14;
    $hot = $hot ? $hot : 5;
    $offset = $offset ? $offset : 0;
    
    $output = '<div class="tag-items">';
    $tags = get_tags(array("number" => $num,
        "order" => "DESC",
        "offset"=>$offset,
    ));
    foreach($tags as $tag){
        $count = intval( $tag->count );
        $name = $tag->name;
        $class = ( $count > $hot ) ? 'tag-item hot' : 'tag-item';
        $fire = ( $count > $hot ) ? '<i class="icon-fire-1"></i>' : '';
        $output .= '<a href="'. esc_attr( get_tag_link( $tag->term_id ) ) .'" class="'. $class .'" title="浏览和' . $name . '有关的文章">' . $name .$fire. '</a>';

    }
    $output .= '</div>';
    return $output;

}

//留言墙
function readers_wall( $outer='1', $timer='100', $limit='200' ){
    global $wpdb;
    $items = $wpdb->get_results("select count(comment_author) as cnt, comment_author, comment_author_url, comment_author_email from (select * from $wpdb->comments left outer join $wpdb->posts on ($wpdb->posts.id=$wpdb->comments.comment_post_id) where comment_date > date_sub( now(), interval $timer month ) and user_id='0' and comment_author != '".$outer."' and post_password='' and comment_approved='1' and comment_type='') as tempcmt group by comment_author order by cnt desc limit $limit");
    $htmls = '';
    foreach ($items as $item) {
        $c_url = $item->comment_author_url;
        if (!$c_url) $c_url = 'javascript:;';
        // print_r($item);
        $htmls .= '<a target="_blank" href="'. $c_url . '" title="'.$item->comment_author.' 评论'. $item->cnt . '次">'.get_avatar( $item->comment_author_email ).'</a>';
    }
    echo $htmls;
}

//友情链接
function get_the_link_items($id = null){
    $bookmarks = get_bookmarks('orderby=date&category=' .$id );
    $output = '';
    if ( !empty($bookmarks) ) {
        $output .= '<ul class="link-items fontSmooth">';
        foreach ($bookmarks as $bookmark) 
        {
            $output .=  '<li class="link-item"><a class="link-item-inner effect-apollo" href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" rel="' . $bookmark->link_rel . '" >';
            
            if(($bookmark->link_notes)){
                $output .= get_avatar($bookmark->link_notes,64);
            }else if($bookmark->link_image){
                
                $output .= '<img alt="' . $bookmark->link_description . '" src="'. $bookmark->link_image .'" title="' . $bookmark->link_description . '">';
                
            }
            else{
                
                $output .= '<img alt="' . $bookmark->link_description . '" src="'.get_template_directory_uri().'/img/avatar.png" title="' . $bookmark->link_description . '">';
                
            }
            
            $output .= '<span class="sitename">'. $bookmark->link_name .'</span></a></li>';
        }
        $output .= '</ul><div class="clearfix"></div>';
    }
    return $output;
}

function get_link_items(){
    $linkcats = get_terms( 'link_category' );
    if ( !empty($linkcats) ) {
        foreach( $linkcats as $linkcat){ 
                if( $linkcat->description ) $linkdes .= '- <span class="link-description">' . $linkcat->description . '</span>';           
            $result .=  '<div class="link-title"><span>'.$linkcat->name.'</span>'.$linkdes.'</div>';
           
            $result .=  get_the_link_items($linkcat->term_id);
        }
    } else {
        $result = get_the_link_items();
    }
    return $result;
}

function shortcode_link(){
    return get_link_items();
}
add_shortcode('bigfalink', 'shortcode_link');

function links_banner_pic(){
    if(suxingme('links_banner_pic')){
        $links_banner_pic = suxingme('links_banner_pic');
    }else{
        $links_banner_pic = get_template_directory_uri().'/img/page_bg.jpg';
    }
    return $links_banner_pic;   
}

function pagenav_banner_pic(){
    if(suxingme('pagenav_banner_pic')){
        $pagenav_banner_pic = suxingme('pagenav_banner_pic');
    }else{
        $pagenav_banner_pic = get_template_directory_uri().'/img/page_bg.jpg';
    }
    return $pagenav_banner_pic;   
}

function contri_banner_pic(){
    if(suxingme('pagecon_banner_pic')){
        $pagecon_banner_pic = suxingme('pagecon_banner_pic');
    }else{

        $pagecon_banner_pic = get_template_directory_uri().'/img/page_bg.jpg';
    }
    return $pagecon_banner_pic;   
}

function tags_banner_pic(){
    if(suxingme('tags_banner_pic')){
        $tags_banner_pic = suxingme('tags_banner_pic');
    }else{

        $tags_banner_pic = get_template_directory_uri().'/img/page_bg.jpg';
    }
    return $tags_banner_pic;   
}

function readers_banner_pic(){
    if(suxingme('readers_banner_pic')){
        $readers_banner_pic = suxingme('readers_banner_pic');
    }else{

        $readers_banner_pic = get_template_directory_uri().'/img/page_bg.jpg';
    }
    return $readers_banner_pic;   
}

function archives_banner_pic(){
    if(suxingme('archives_banner_pic')){
        $archives_banner_pic = suxingme('archives_banner_pic');
    }else{

        $archives_banner_pic = get_template_directory_uri().'/img/page_bg.jpg';
    }
    return $archives_banner_pic;   
}

function like_banner_pic(){
    if(suxingme('like_banner_pic')){
        $like_banner_pic = suxingme('like_banner_pic');
    }else{

        $like_banner_pic = get_template_directory_uri().'/img/page_bg.jpg';
    }
    return $like_banner_pic;   
}

function single_pc_top_ad_pic(){
    $ad_content_pc = suxingme('suxing_single_top_ad_content_pc_url');
    if( suxingme('suxing_single_top_ad_content_pc',true)){ 
        $single_ad_pc='<div class="posts-top-cjtz clearfix">'.$ad_content_pc.'</div>';
    }else{  
        $single_ad_pc='';   
    }
    return $single_ad_pc; 
}

function single_mini_top_ad_pic(){
    $ad_content_pc = suxingme('suxing_top_ad_content_mini_url');
    if( suxingme('suxing_top_ad_content_mini',true)){ 
        $single_ad_pc='<div class="posts-top-cjtz  content-top-cjtz-mini clearfix">'.$ad_content_pc.'</div>';
    }else{  
        $single_ad_pc='';   
    }
    return $single_ad_pc; 
}
   

function single_pc_ad_pic(){
    $ad_content_pc = suxingme('suxing_ad_content_pc_url');
    if( suxingme('suxing_ad_content_pc',true)){ 
        $single_ad_pc='<div class="posts-cjtz content-cjtz clearfix">'.$ad_content_pc.'</div>';
    }else{  
        $single_ad_pc='';   
    }
    return $single_ad_pc; 
}
function single_mini_ad_pic(){
    $ad_content_pc = suxingme('suxing_ad_content_mini_url');
    if( suxingme('suxing_ad_content_mini',true)){ 
        $single_ad_pc='<div class="posts-cjtz content-cjtz-mini clearfix">'.$ad_content_pc.'</div>';
    }else{  
        $single_ad_pc='';   
    }
    return $single_ad_pc; 
}
                
//自动给修改网站登陆页面logo
function customize_login_logo(){      
    if( suxingme('suxingme_login_logo') ) {
        echo '<style type="text/css">
       .login h1 a { background-image:url('.suxingme('suxingme_login_logo') .');background-size: 100%;width: 280px;height: 84px;margin: 20px auto 15px; }
        </style>';      
    }else{
    echo '<style type="text/css">
        .login h1 a { background-image:url('.get_template_directory_uri() .'/img/logo.png); width: 280px; max-height: 100px;margin: 20px auto 15px; background-size: contain;background-repeat: no-repeat;background-position: center center;}
        </style>';   
    }    
}      
add_action('login_head', 'customize_login_logo');   
add_filter('login_headerurl', create_function(false,"return get_option('home');"));

add_action('optionsframework_after','show_category', 100);
function show_category() {
    global $wpdb;
    $request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
    $request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
    $request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
    $request .= " ORDER BY term_id asc";
    $categorys = $wpdb->get_results($request);
    echo '<div class="uk-panel uk-panel-box" style="margin-bottom: 20px;"><h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; line-height: 24px; font-weight: 400; text-transform: none; color: #666;">可能会用到的分类ID</h3>';
    echo "<ul>";
    foreach ($categorys as $category) { 
        echo  '<li style="margin-right: 10px;float:left;">'.$category->name."（<code>".$category->term_id.'</code>）</li>';
    }
    echo "</ul></div>";
}

function suxingme_get_thumbnail() {  
    global $post;
    $html = '';

    $content = $post->post_content;  
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);  
    $images = $strResult[1];

    $counter = count($strResult[1]);
    if( get_post_meta($post->ID,"img2_value",true) && wp_is_mobile() ){
        $cleft = 'class="left"';
        $cright = 'class="right"';
    } else {
        $cleft = $cright = '';
    }

    if( !$counter ){
        if( suxingme('suxingme_timthumb') && suxingme('suxingme_timthumb_lazyload', true) ) { 
            $image ='<img class="lazy thumbnail" data-original="'. get_template_directory_uri() .'/timthumb.php?src='.post_thumbnail_src().'&h=173.98&w=231.98&zc=1" src="'.constant("THUMB_SMALL_DEFAULT").'" alt="'. get_the_title().'" />';   
        }
        if ( suxingme('suxingme_timthumb') && !suxingme('suxingme_timthumb_lazyload', true) ) {

            $image ='<img class="thumbnail" src="'.get_template_directory_uri().'/timthumb.php?src='.post_thumbnail_src().'&h=173.98&w=231.98&zc=1" alt="' . get_the_title() . '" />';

        }
        if( suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb') ){
           $image ='<img src="'.constant("THUMB_SMALL_DEFAULT").'" data-original="'.post_thumbnail_src().'" alt="'. get_the_title().'" class="lazy thumbnail" />';
        } 
        if( !suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb')){
            $image ='<img src="'.post_thumbnail_src().'" alt="'. get_the_title().'" class="thumbnail" />';
        }

        return '<li '.$cleft.'>
                    <div class="image-item">
                        <a href="'.get_permalink(get_the_ID()).'">
                            <div class="overlay"></div>
                            '.$image.'
                        </a>
                    </div>
                </li>
                <li '.$cright.'>
                    <div class="image-item">
                        <a href="'.get_permalink(get_the_ID()).'">
                            <div class="overlay"></div>
                            '.$image.'
                        </a>
                    </div>
                </li>
                <li '.$cright.'>
                    <div class="image-item">
                        <a href="'.get_permalink(get_the_ID()).'">
                            <div class="overlay"></div>
                            '.$image.'
                        </a>
                    </div>
                </li>';
    }

    $full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
    $ts_thum = $full_image_url[0];
    if($ts_thum){

        if( suxingme('suxingme_timthumb') && suxingme('suxingme_timthumb_lazyload', true) ) { 
            $image_ts ='<img class="lazy thumbnail" data-original="'. get_template_directory_uri() .'/timthumb.php?src='.$ts_thum.'&h=173.98&w=231.98&zc=1" src="'.constant("THUMB_SMALL_DEFAULT").'" alt="'. get_the_title().'" />';   
        }
        if ( suxingme('suxingme_timthumb') && !suxingme('suxingme_timthumb_lazyload', true) ) {

            $image_ts ='<img class="thumbnail" src="'.get_template_directory_uri().'/timthumb.php?src='.$ts_thum.'&h=173.98&w=231.98&zc=1" alt="' . get_the_title() . '" />';

        } 
        if( suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb') ){
           $image_ts ='<img src="'.constant("THUMB_SMALL_DEFAULT").'" data-original="'.$ts_thum.'" alt="'. get_the_title().'" class="lazy thumbnail" />';
        } 
        if( !suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb')){
            $image_ts ='<img src="'.$ts_thum.'" alt="'. get_the_title().'" class="thumbnail" />';
        }

        $num = 2;
        $html .= '<li '.$cleft.'>
                    <div class="image-item">
                        <a href="'.get_permalink(get_the_ID()).'">
                            <div class="overlay"></div>
                            '.$image_ts.'
                        </a>
                    </div>
                </li>';
    }
    else{
        $num = 3;
    }

    $i = 0;
    foreach($images as $key=>$src){
        $i++;
        $src2 = wp_get_attachment_image_src(suxingme_get_attachment_id_from_src($src), 'full');
        $src2 = $src2[0];
        if( !$src2 && true ){
            $src = $src;
        }else{
            $src = $src2;
        }

        if( suxingme('suxingme_timthumb') && suxingme('suxingme_timthumb_lazyload', true) ) { 
            $image_item ='<img class="lazy thumbnail" data-original="'. get_template_directory_uri() .'/timthumb.php?src='.$src.'&h=173.98&w=231.98&zc=1" src="'.constant("THUMB_SMALL_DEFAULT").'" alt="'. get_the_title().'" />';   
        }
        if ( suxingme('suxingme_timthumb') && !suxingme('suxingme_timthumb_lazyload', true) ) {

            $image_item ='<img class="thumbnail" src="'.get_template_directory_uri().'/timthumb.php?src='.$src.'&h=173.98&w=231.98&zc=1" alt="' . get_the_title() . '" />';

        } 
        if( suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb') ){
           $image_item ='<img src="'.constant("THUMB_SMALL_DEFAULT").'" data-original="'.$src.'" alt="'. get_the_title().'" class="lazy thumbnail" />';
        } 
        if( !suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb')){
            $image_item ='<img src="'.$src.'" alt="'. get_the_title().'" class="thumbnail" />';
        }
        if( $cright == '' && $cleft == ''){
            $cright2 = '';
        } else {
            if( $i==1 && !$ts_thum ){
                $cright2 = 'class="left"';
            } else {
                $cright2 = 'class="right"';
            }
        }


        $html .= '<li '.$cright2.'>
                    <div class="image-item">
                        <a href="'.get_permalink(get_the_ID()).'">
                            <div class="overlay"></div>
                            '.$image_item.'
                        </a>
                    </div>
                </li>';
        if( $counter >= $num && $i >= $num )
        {
            break; 
        }
    }

    if(count($images) < 3 && $ts_thum ){

        if( suxingme('suxingme_timthumb') && suxingme('suxingme_timthumb_lazyload', true) ) { 
            $image_html ='<img class="lazy thumbnail" data-original="'. get_template_directory_uri() .'/timthumb.php?src='.get_template_directory_uri().'/img/default_thumb.png&h=h=173.98&w=231.98&zc=1" src="'.constant("THUMB_SMALL_DEFAULT").'" alt="'. get_the_title().'" />';   
        }
        if ( suxingme('suxingme_timthumb') && !suxingme('suxingme_timthumb_lazyload', true) ) {

            $image_html ='<img class="thumbnail" src="'.get_template_directory_uri().'/timthumb.php?src='.get_template_directory_uri().'/img/default_thumb.png&h=h=173.98&w=231.98&zc=1" alt="' . get_the_title() . '" />';

        }
        if( suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb') ){
           $image_html ='<img src="'.constant("THUMB_SMALL_DEFAULT").'" data-original="'.get_template_directory_uri().'/img/default_thumb.png" alt="'. get_the_title().'" class="lazy thumbnail" />';
        } 
        if( !suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb')){
            $image_html ='<img src="'.get_template_directory_uri().'/img/default_thumb.png" alt="'. get_the_title().'" class="thumbnail" />';
        }

        $j = 3 - count($images) - 1;
        for ($k=0; $k < $j ; $k++) {
        $html .= '<li '.$cright.'>
                    <div class="image-item">
                        <a href="'.get_permalink(get_the_ID()).'">
                            <div class="overlay"></div>
                            '.$image_html.'
                        </a>
                    </div>
             </li>';
        }
    }
    if(count($images) < 3 && !$ts_thum ){

        if( suxingme('suxingme_timthumb') && suxingme('suxingme_timthumb_lazyload', true) ) { 
            $image_html_t ='<img class="lazy thumbnail" data-original="'. get_template_directory_uri() .'/timthumb.php?src='.get_template_directory_uri().'/img/default_thumb.png&h=173.98&w=231.98&zc=1" src="'.constant("THUMB_SMALL_DEFAULT").'" alt="'. get_the_title().'" />';   
        }
        if ( suxingme('suxingme_timthumb') && !suxingme('suxingme_timthumb_lazyload', true) ) {

            $image_html_t ='<img class="thumbnail" src="'.get_template_directory_uri().'/timthumb.php?src='.get_template_directory_uri().'/img/default_thumb.png&h=173.98&w=231.98&zc=1" alt="' . get_the_title() . '" />';

        }
        if( suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb') ){
           $image_html_t ='<img src="'.constant("THUMB_SMALL_DEFAULT").'" data-original="'.get_template_directory_uri().'/img/default_thumb.png" alt="'. get_the_title().'" class="lazy thumbnail" />';
        } 
        if( !suxingme('suxingme_timthumb_lazyload', true) && !suxingme('suxingme_timthumb')){
            $image_html_t ='<img src="'.get_template_directory_uri().'/img/default_thumb.png" alt="'. get_the_title().'" class="thumbnail" />';
        }
        $j = 3 - count($images);
        for ($k=0; $k < $j ; $k++) {
            $html .= '<li '.$cright.'>
                        <div class="image-item">
                            <a href="'.get_permalink(get_the_ID()).'">
                                <div class="overlay"></div>
                                '.$image_html_t.'
                            </a>
                        </div>
                    </li>';
        }
    }

    

    return $html;
}

function suxingme_get_attachment_id_from_src ($link) {
    global $wpdb;
    $link = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $link);
    return $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE guid='$link'");
}
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );
function optionsframework_custom_scripts() {
echo
<<<JS
<script type="text/javascript">
jQuery(document).ready(function() {
    if (jQuery('#tab_showhidden:checked').val() !== undefined) {
        jQuery('#section-tabfirst').show();
        jQuery('#section-tabfirsttitle').show();
        jQuery('#section-tabsecond').show();
        jQuery('#section-tabsecondtitle').show();
        jQuery('#section-tabthird').show();
        jQuery('#section-tabthirdtitle').show();
    }
});
</script>
JS;
}

//禁用所有文章类型的修订版本
if (suxingme( 'revisions_to_keep', true)){
    add_filter( 'wp_revisions_to_keep', 'specs_wp_revisions_to_keep', 10, 2 );
    function specs_wp_revisions_to_keep( $num, $post ) {
        return 0;
    }
}

//body添加额外的class
function suxingme_bodyclass(){
    $class = 'off-canvas-nav-left';
    if( (is_page() || is_single()) && suxingme('suxingme_text_indent',true) ){
        $class .= ' off-canvas-nav-left post-p-indent';
    }
    return trim(trim($class).' ');
}

//延迟加载的默认图片设置
if( suxingme('default_thumbnail') ){
    $smallimg = suxingme('default_thumbnail');
    $bigimg = suxingme('default_thumbnail_700');
    define( 'THUMB_SMALL_DEFAULT'  , $smallimg );
} else {
    define( 'THUMB_SMALL_DEFAULT'  , get_template_directory_uri().'/img/thumbnail-small.png' ); 
}

if(suxingme('default_thumbnail_700')){
    define( 'THUMB_BIG_DEFAULT'  , $bigimg );
} else {
    define( 'THUMB_BIG_DEFAULT'  , get_template_directory_uri() .'/img/thumbnail-big.png' );    
}


//添加特色缩略图支持
if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');
//输出缩略图地址
function post_thumbnail_src(){
    global $post;
    if( $values = get_post_custom_values("thumb") ) {   //输出自定义域图片地址
        $values = get_post_custom_values("thumb");
        $post_thumbnail_src = $values [0];
    } elseif( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_thumbnail_src = $thumbnail_src [0];
    } else {
        $post_thumbnail_src = '';
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if(!empty($matches[1][0])){
            $post_thumbnail_src = $matches[1][0];   //获取该图片 src
        }elseif( suxingme('suxingme_post_thumbnail') ){
            $post_thumbnail_src = suxingme('suxingme_post_thumbnail');
        }else{  
            //如果日志中没有图片，则显示随机图片
            //$random = mt_rand(1, 5);
            //$post_thumbnail_src = get_template_directory_uri().'/img/random/'.$random.'.jpg';
            //如果日志中没有图片，则显示默认图片
            $post_thumbnail_src = get_template_directory_uri().'/img/default_thumb.png';
        }
    }
    return $post_thumbnail_src;
} 

function get_post_thumbnail_url($post_id){
    $post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
    $post=get_post($post_id);
    if( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'full');
        $post_thumbnail_src = $thumbnail_src [0];
    } else {
        $post_thumbnail_src = '';
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if(!empty($matches[1][0])){
            $post_thumbnail_src = $matches[1][0];   //获取该图片 src
        }else{  
            $post_thumbnail_src = '';
        }
    }
    return $post_thumbnail_src;
}


//访问计数
function record_visitors(){
    if (is_singular()) {
        global $post;
        $post_ID = $post->ID;
        if($post_ID) 
        {
          $post_views = (int)get_post_meta($post_ID, 'views', true);
          if(!update_post_meta($post_ID, 'views', ($post_views+1))) 
          {
            add_post_meta($post_ID, 'views', 1, true);
          }
        }
    }
}
add_action('wp_head', 'record_visitors');  

function post_views($before = '(点击 ', $after = ' 次)', $echo = 1)
{
    global $post;
    $post_ID = $post->ID;
    $views = (int)get_post_meta($post_ID, 'views', true);
    if ($echo) echo $before, number_format($views), $after;
    else return $views;
};

//百度分享
function suxing_get_share(){
    $shares = array(
        array('tsina','icon-weibo'),
        array('weixin','icon-wechat'),
        array('sqq','icon-qq'),
    );
    $html = '';
    for ($i=0; $i < count($shares); $i++) { 
        switch ( $shares[$i][0] ) {
            case 'tsina':
                $text = '微博';
                break;
            case 'weixin':
                $text = '微信';
                break;
            case 'sqq':
                $text = 'QQ好友';
                break;
            default:
                $text = '微博';
                break;
        }
        if( wp_is_mobile() ){
            $text = '<span>'.$text.'</span>';
        }
        $html .= '<a class="bds_'.$shares[$i][0].' '.$shares[$i][1].'" data-cmd="'.$shares[$i][0].'">'.$text.'</a>';
        
    }
    return $html.'<a class="share-links bds_more icon-plus-1" data-cmd="more">更多</a>';
}

//根据上传时间重命名文件
if (suxingme( 'suxingme_upload_filter', true)){
add_filter('wp_handle_upload_prefilter', 'custom_upload_filter' );
    function custom_upload_filter( $file ){
        $info = pathinfo($file['name']);
        $ext = $info['extension'];
        $filedate = date('YmdHis').rand(10,99);//为了避免时间重复，再加一段2位的随机数
        $file['name'] = $filedate.'.'.$ext;
        return $file;
    }
}
    
/*编辑器添加分页按钮*/
add_filter('mce_buttons','wysiwyg_editor');
function wysiwyg_editor($mce_buttons) {
    $pos = array_search('wp_more',$mce_buttons,true);
    if ($pos !== false) {
        $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
        $tmp_buttons[] = 'wp_page';
        $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
    }
    return $mce_buttons;
}

/*激活友情链接后台*/
add_filter( 'pre_option_link_manager_enabled', '__return_true' );   

//评论时间
function timeago( $ptime ) {
    $ptime = strtotime($ptime);
    $etime = time() - 28800 - $ptime;
    if($etime < 1) return '刚刚';
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  date('Y-m-d', $ptime),
        30 * 24 * 60 * 60       =>  date('m-d', $ptime),
        7 * 24 * 60 * 60        =>  date('m-d', $ptime),
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        if( $etime - ( 24 * 60 * 60 ) > 0 ){
            return $str;
        } else {
           $d = $etime / $secs;
           if ($d >= 1) {
               $r = round($d);
               return $r . $str;
           } 
        }

    }
}


//控制摘要长度
function suxing_excerpt_length($length) {
    return 150;
}
add_filter('excerpt_length', 'suxing_excerpt_length');

function nice_exp($more){
    return '...';
}
add_filter('excerpt_more', 'nice_exp');

/*文章状态*/
function suxing_post_state_date() { 
    global $post;
    $t1 = $post->post_date;
    $t2 = date("Y-m-d H:i:s");
    $new = '<span class="state-new">最新</span>';
    $diff = (strtotime($t2)-strtotime($t1))/3600;
    if($diff<24){
        return $new;
    }else{
         return false;
    }
}

//使用昵称替换用户名，通过用户ID进行查询
add_filter( 'request', 'suxingme_request' );
function suxingme_request( $query_vars )
{
    if ( array_key_exists( 'author_name', $query_vars ) ) {
        global $wpdb;
        $author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='nickname' AND meta_value = %s", $query_vars['author_name'] ) );
        if ( $author_id ) {
            $query_vars['author'] = $author_id;
            unset( $query_vars['author_name'] );    
        }
    }
    return $query_vars;
}

//文章内分页
function suxing_link_pages($args = '') {      
    $defaults = array(      
        'before' => '<p>' . __('Pages:'), 
        'after' => '</p>',      
        'link_before' => '', 
        'link_after' => '',      
        'next_or_number' => 'number', 
        'nextpagelink' => __('下一页'),      
        'previouspagelink' => __('上一页'), 
        'pagelink' => '%',      
        'echo' => 1      
    );      
    $r = wp_parse_args( $args, $defaults );      
    $r = apply_filters( 'wp_link_pages_args', $r );      
    extract( $r, EXTR_SKIP );      
    global $page, $numpages, $multipage, $more, $pagenow;      
    $output = '';      
    if ( $multipage ) {      
        if ( 'number' == $next_or_number ) {      
            $output .= $before;      
            for ( $i = 1; $i < ($numpages+1); $i = $i + 1 ) {      
                $j = str_replace('%',$i,$pagelink);      
                $output .= ' ';      
                if ( ($i != $page) || ((!$more) && ($page==1)) ) {      
                    $output .= _wp_link_page($i);      
                    $output .= $link_before . $j . $link_after;//将原本在下面的那句移进来了      
                }else{  //加了个else语句，用来判断当前页，如果是的话输出下面的      
                    $output .= '<span class="page-numbers current">' . $j . '</span>';      
                }      
                //原本这里有一句，移到上面去了      
                if ( ($i != $page) || ((!$more) && ($page==1)) )      
                    $output .= '</a>';      
            }      
            $output .= $after;      
        } else {      
            if ( $more ) {      
                $output .= $before;      
                $i = $page - 1;      
                if ( $i && $more && $previouspagelink ) { //if里面的条件加了$previouspagelink也就是只有参数有“上一页”这几个字才显示      
                    $output .= _wp_link_page($i);      
                    $output .= $link_before. $previouspagelink . $link_after . '</a>';      
                }      
                $i = $page + 1;      
                if ( $i <= $numpages && $more && $nextpagelink ) {      
                //if里面的条件加了$nextpagelink也就是只有参数有“下一页”这几个字才显示      
                    $output .= _wp_link_page($i);      
                    $output .= $link_before. $nextpagelink . $link_after . '</a>';      
                }      
                $output .= $after;      
            }      
        }      
    }      
    if ( $echo )      
        echo $output;      
    return $output;      
}    

//搜索结果排除所有页面
function search_filter_page($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts','search_filter_page');


/*识别当前作者身份*/
function suxing_level() { 
    $user_id=get_post(get_the_ID())->post_author;   
    if(user_can($user_id,'administrator')){
        echo suxingme("suxing_site_admin_name","站长");
    }   
    elseif(user_can($user_id,'editor')){
        echo '管理编辑';}
    elseif(user_can($user_id,'author')){
        echo '作者';
    }elseif(user_can($user_id,'contributor')){
        echo '贡献者';
    }elseif(user_can($user_id,'subscriber')){
        echo '订阅者';
    }
}



//统计作者的评论数量 替换author_comment_count
function get_author_comment_count($author_id) { 
    global $wpdb;
    $comment_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = %d AND comment_type not in ('trackback','pingback')" ,$author_id) );
    return $comment_count;
}

add_filter( 'avatar_defaults', 'newgravatar' );  
function newgravatar ($avatar_defaults) {
    $myavatar = suxingme("new_avatar_pic",false );
    if( $myavatar ){
        $avatar_defaults[$myavatar] = get_option('blogname')." 默认头像";  
    } else {
        $defaultimg = get_template_directory_uri() . '/img/avatar.png';
        $avatar_defaults[$defaultimg] = get_option('blogname')." 默认头像";
    }
    
    return $avatar_defaults;  
}

function mee_insert_posts( $atts, $content = null ){
    extract( shortcode_atts( array(
        'ids' => ''
    ),$atts ) );
    global $post;
    $content = '';
    $postids =  explode(',', $ids);
    $inset_posts = get_posts(array('post__in'=>$postids));
    foreach ($inset_posts as $key => $post) {
        setup_postdata( $post );
        $content .= '<div class="warp-post-embed"><a href="' . get_permalink() . '" target="_blank" ><div class="embed-bg" style="background-image:url('.post_thumbnail_src().')"></div><div class="embed-content"><h2>'.get_the_title().'</h2><p>'. wp_trim_words(get_the_excerpt(), 60 ) .'</p></div></a></div>';
    }
    wp_reset_postdata();
    return $content;
}
add_shortcode('suxing_insert_post', 'mee_insert_posts');


//去掉图片外围标签p
function filter_ptags_on_images($content){
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '<div class="post-image">\1\2\3</div>', $content);
}
add_filter('the_content', 'filter_ptags_on_images');

//字体
function custum_fontfamily($initArray){
$initArray['font_formats'] = "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆';ClearSans='clear_sansregular',Helvetica,Arial,sans-serif;ClearSans Medium='clear_sans_mediumregula',Helvetica,Arial,sans-serif;ClearSans Light='clear_sans_lightregular',Helvetica,Arial,sans-serif;ClearSans Thin='clear_sans_thinregular',Helvetica,Arial,sans-serif";
return $initArray;
}
add_filter('tiny_mce_before_init', 'custum_fontfamily');

//选择字体
function enable_more_buttons($buttons) {
$buttons[] = 'styleselect';
$buttons[] = 'fontselect';
return $buttons;
}
add_filter("mce_buttons", "enable_more_buttons");