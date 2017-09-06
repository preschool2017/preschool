<?php 
/*
Template Name: 政府采购(模板-02)
*/
get_header();
?>
<style>
    @media (min-width: 20px) {

    }
    @media (min-width: 500px){

    }
    @media (min-width: 1200px){

    }
    .accc a{background:#a054a4;border-radius: 0px;border:0px;outline:none; text-align: center;}
    .accc a:hover{background:#a054a4;outline:none;}
    .accc a:focus{background:#a054a4;outline:none;}
    .post-nav{
        height: 210px;
    }
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
                            <div class="col-md-6" style="padding: 0px;">
                                <a href="http://wp.yoursclass.com/2017/05/24/%E4%B8%AD%E5%9B%BD%E5%AD%A6%E5%89%8D%E6%95%99%E8%82%B2%E7%BD%91%E6%89%BF%E6%8B%85%E6%94%BF%E5%BA%9C%E9%87%87%E8%B4%AD%E7%9A%84%E6%B8%8A%E6%BA%90%E8%AF%B4%E6%98%8E/" style="display:inline-block;"><img src="/wp-content/themes/Grace/g-procurement/images/img1.jpg" class="col-md-12  col-xs-12"></a>
                            </div>
                            <div class="col-md-6" style="padding: 0px;">
                                <a href="http://wp.yoursclass.com/2017/05/24/%E5%B9%BC%E5%84%BF%E5%9B%AD%E6%94%BF%E5%BA%9C%E9%87%87%E8%B4%AD%E7%94%B3%E8%AF%B7%E5%8F%82%E4%B8%8E%E5%85%AC%E7%9B%8A%E9%A1%B9%E7%9B%AE%E6%B5%81%E7%A8%8B/" style="display:inline-block;"><img src="/wp-content/themes/Grace/g-procurement/images/img2.jpg" class="col-md-12  col-xs-12"></a>
                            </div>
                            <div class="post-nav">
                                <?php
                                $thisCat_29 = get_category('29');
                                $total_29 = ceil($thisCat_29->count / get_option('posts_per_page'));                               
                                $thisCat_28 = get_category('28');
                                $total_28 = ceil($thisCat_28->count / get_option('posts_per_page'));
                                ?>
                                <span style="top:25px;left:-420px;" class="cat-post current" data-paged="1" data-category="29" data-action="fa_load_postlist"  data-total="<?php echo $total_29;?>">商品列表</span>
                                <span style="top:25px;left:-420px;" class="cat-post " data-paged="1" data-category="28" data-action="fa_load_postlist"  data-total="<?php echo $total_28;?>">厂家列表</span>
                            </div>


                            <?php
                            //echo "page=".$paged;
                            $query_args = array(
                                "posts_per_page" => get_option('posts_per_page'),
                                "cat" => "29",
                                "post_status" => "publish",
                                "post_type" => "post",
                                "paged" => $paged,
                                "ignore_sticky_posts" => 1
                            );
                            $the_query = new WP_Query( $query_args );

                            if ( $the_query->have_posts() ) : ?>
                                <div class="ajax-load-box posts-con">
                                    <?php while ( $the_query->have_posts() ) : $the_query->the_post();
                                        include( get_template_directory().'/includes/excerpt.php' );
                                    endwhile; ?>
                                </div>
                                <div class="clearfix"></div>
                                <?php if( suxingme('suxingme_ajax_posts',true) ) { ?>
                                    <div id="ajax-load-posts">
                                        <?php
					//echo "pages:".$the_query->max_num_pages;
                                        if (2 > $the_query->max_num_pages) {
                                            echo "";//nothing
                                        } else {
                                            $button = '<button id="fa-loadmore" class="button button-more" data-wow-delay="0.3s"';
                                            $button .= ' data-category="' . get_query_var('cat') . '"';
                                            $button .= ' data-paged="2" data-action="fa_load_postlist" data-total="' . $GLOBALS["wp_query"]->max_num_pages . '">加载更多</button>';
                                            echo $button;
                                        }
                                        ?>
                                    </div>

                                <?php  }else {
                                    the_posts_pagination( array(
                                        'prev_text'=>'上页',
                                        'next_text'=>'下页',
                                        'screen_reader_text' =>'',
                                        'mid_size' => 1,
                                    ) ); } ?>
                            <?php 	else :
                                get_template_part( 'content', 'none' );
                            endif;?>
                        </div>

                        <div class="accc col-md-3" style="padding: 0px;">
                            <div class="row hidden-md hidden-lg " style="text-align: center; padding: 0px;">
                                <div class="col-md-12" style="padding: 0px;">
                                    <a style="display:inline-block;" href="http://ap.yoursclass.com/activity/index.html?id=258&st=1"><img src="/wp-content/themes/Grace/g-procurement/images/zrye.png" alt=""></a>
                                </div>
                                <div class="col-md-12" style="text-align: center; margin-top: -45px;">    
                                    <a href="http://ap.yoursclass.com/activity/index.html?id=258&st=1" class="btn btn-primary col-md-5">活动报名</a>
                                </div>
                            </div>
                            <div class="row hidden-xs hidden-sm " style="text-align: center; padding: 0px;">
                                <div class="col-md-12" style="padding: 0px;">
                                    <a href="http://ap.yoursclass.com/activity/index.html?id=258&st=1"><img src="/wp-content/themes/Grace/g-procurement/images/zrye.png" alt=""></a>
                                </div>
                                <div class="col-md-12" style="text-align: center; margin-top: -45px; margin-left:82px;">    
                                    <a href="http://ap.yoursclass.com/activity/index.html?id=258&st=1" class="btn btn-primary col-md-5">活动报名</a>
                                </div>
                            </div>
                            <div class="row hidden-md hidden-lg"  style="padding:0px;text-align: center; margin-top: 15px;margin-bottom: 25px;">
                                <div class="col-md-12" style="padding: 0px;">
                                    <a href="#"><img src="/wp-content/themes/Grace/g-procurement/images/csrz.png" alt="" style="border:0px;"></a>
                                </div>
                                <div class="col-md-12" style="text-align: center; margin-top: -50px;">
                                    <a href="http://ap.yoursclass.com/activity/index.html?id=259&st=2" class="btn btn-primary col-md-5" style="margin-right:10px;">厂商入驻</a>
                                    <a href="http://ap.yoursclass.com/activity/index.html?id=257&st=3" class="btn btn-primary col-md-5">买家入驻</a>
                                </div>
                            </div>
                            <div class="row hidden-xs hidden-sm"  style="padding:0px;text-align: center; margin-top: 15px;margin-bottom: 25px;">
                                <div class="col-md-12" style="padding: 0px;">
                                    <a href="#"><img src="/wp-content/themes/Grace/g-procurement/images/csrz.png" alt="" style="border:0px;"></a>
                                </div>
                                <div class="col-md-12" style="text-align: center; margin-top: -50px;  margin-left: 18px; ">
                                    <a href="http://ap.yoursclass.com/activity/index.html?id=259&st=2" class="btn btn-primary col-md-5" style="margin-right:10px;">厂商入驻</a>
                                    <a href="http://ap.yoursclass.com/activity/index.html?id=257&st=3" class="btn btn-primary col-md-5">买家入驻</a>
                                </div>
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
<script type="text/javascript" src="/wp-content/themes/Grace/g-procurement/js/angular/angular.1.4.6.min.js"></script>
<script type="text/javascript" src="/wp-content/themes/Grace/g-procurement/js/core/jquery.min.js"></script>
<?php 
//endwhile; endif; 
?>	
<?php get_footer(); ?>
