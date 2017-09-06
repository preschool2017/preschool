<?php 
/*
Template Name: 专家课堂(模板-01)
*/
get_header();
?>
<style>
    @media (min-width: 20px) {

    }
    @media (min-width: 500px){

    }
    @media (min-width: 1200px){
        .ss{margin: 0px;margin-top: 15px;}
    }
    .accc a{background:#a054a4;border-radius: 0px;border:0px;outline:none; text-align: center;}
    .accc a:hover{background:#a054a4;outline:none;}
    .accc a:focus{background:#a054a4;outline:none;}
</style>



<?php 
/*
if(have_posts()): while(have_posts()):the_post();  


$linkcat = get_post_meta($post->ID, 'linkcat_value', true);

$link_cat_ids = explode(",",$linkcat);

foreach ( $link_cat_ids as $key => $value) {

}
*/
?>
<div class="page-single page-nav"  >
<input type="hidden" value="isvideolist" id="id_isvideolist" />
    <div>
        <!--<div class="container">-->
            <!-- <h3 class="title"> -->
                
            <!-- </h3> -->
            <div class="page-dec">
                <div class="container">
    <div class="page-container" style="margin: 0 auto; text-decoration: center; overflow: hidden;">
        <!-- Page content -->
        <div class="page-content" style="padding:0px;">
            <!-- Main sidebar -->
            <!-- /main sidebar -->
            <!-- Main content -->
            <div class="content-wrapper" style=" padding:0px;">
                <!-- Page header -->
                <!-- /page header -->
                <!-- Content area -->
                <div class="content">
                    <div class="row">
                        <div class="col-md-9" style="padding:0px;">
                            <div class="ss post-nav row">                               
                                <!-- <div class="col-md-12"> -->
                               <?php
                               global $wpdb;
                              $sql_mp4_cats =  "SELECT t2.`term_taxonomy_id`,t3.name FROM `wp_posts` t1,`wp_term_relationships` t2,`wp_terms` t3
                                            WHERE t1.post_type='attachment' AND t1.post_mime_type='video/mp4'
                                            AND t1.`ID` = t2.object_id  AND t2.`term_taxonomy_id`=t3.`term_id`
                                            GROUP BY t2.`term_taxonomy_id`";
                               $rs_mp4_cats = $wpdb->get_results($sql_mp4_cats);
                               if(!empty($rs_mp4_cats))
                               {
                                  foreach($rs_mp4_cats as $cat )
                                  {
                                      $query_args = array(
                                          "posts_per_page" => get_option('posts_per_page'),
                                          "post_mime_type" => "video/mp4",
                                          "post_status" => "inherit",
                                          "cat" => intval($cat->term_taxonomy_id),
                                          "post_type" => "attachment",
                                          "ignore_sticky_posts" => 1
                                      );
                                      $video_posts = new WP_Query($query_args);
                                      $video_total = $video_posts->max_num_pages;
                                      ?>
                                      <span id="id_videoTab" class="video-post " data-category="<?php echo $cat->term_taxonomy_id;?>" data-paged="1" data-home="true" data-action="fa_load_postlist"  data-total="<?php echo $video_total;?>"><?php echo $cat->name;?></span>
                                <?php
                                  }
                               }
							?>

                               <!-- </div>   -->

	                                               <?php
                            // echo "page=".$paged;
							$query_args = array(
									"posts_per_page" => get_option('posts_per_page'),
									"post_mime_type" => "video/mp42",
									"post_status" => "inherit",
									"post_type" => "attachment",
									"paged" => $paged,
									"ignore_sticky_posts" => 1
								);
                            
                            $the_query = new WP_Query( $query_args );

                            if ( true/*$the_query->have_posts()*/ ) : ?>
                                <div class="ajax-load-box posts-con">
                                    <?php while ( $the_query->have_posts() ) : $the_query->the_post();
                                        include( get_template_directory().'/includes/excerpt.php' );
                                    endwhile; ?>
                                </div>

                                <div class="clearfix"></div>
                                <?php if( suxingme('suxingme_ajax_posts',true) ) { ?>
                                    <div id="ajax-load-posts">
                                        <?php
                    // echo "pages:".$the_query->max_num_pages;
                                        // if (2 > $the_query->max_num_pages) {
                                        //     echo "已经是最下面啦！没有啦";//nothing
                                        // } else {
                                            $button = '<button id="fa-loadmore" class="button button-more" data-wow-delay="0.3s"';
                                            $button .= ' data-category="' . get_query_var('cat') . '"';
                                            $button .= ' data-paged="2" data-action="fa_load_postlist" data-total="' . $GLOBALS["wp_query"]->max_num_pages . '">加载更多</button>';
                                            echo $button;
                                        // }
                                        ?>
                                    </div>

                                <?php  }else {
                                    the_posts_pagination( array(
                                        'prev_text'=>'上页',
                                        'next_text'=>'下页',
                                        'screen_reader_text' =>'',
                                        'mid_size' => 1,
                                    ) ); } ?>
                            <?php   else :
                                get_template_part( 'content', 'none' );
                            endif;?>
                        
                        
							   
                            </div>



                        

                        </div>
    
                    </div>
                </div>
                <!-- /content area -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
</div>
            </div>
        <!--</div>-->
    </div>
    
</div>
<!--
<script type="text/javascript" src="/wp-content/themes/Grace/g-procurement/js/angular/angular.1.4.6.min.js"></script>
<script type="text/javascript" src="/wp-content/themes/Grace/g-procurement/js/core/jquery.min.js"></script>
-->
<script type="text/javascript">
    if( jQuery(".video-post").length)
    {
        //jQuery('#fa-loadmore').click();
        jQuery(".video-post").eq(0).click();
    }

</script>


<?php 
//endwhile; endif; 
?>  
<?php get_footer(); ?>
