<?php get_header();?>
<div id="page-content" <?php if( suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_3' ) { echo 'class="page-content-110"';}?>>
	<input id="id-indexpage" type="hidden" name="name-indexpage" value="dummy"> 
	<?php 
		if( suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_3' && ! is_paged() ) { 
	    	include(get_template_directory().'/includes/topslide-three-style.php' );
	    }else if( suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_2' && ! is_paged() ) {
			include( get_template_directory().'/includes/topslide-two-style.php' ); 
	    }
	    else if( suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_1' && ! is_paged() ) {
			include( get_template_directory().'/includes/topslide-one-style.php' ); 
	    }
        else if( suxingme('suxing_slide_img_button','index_slide_sytle_1') == 'index_slide_sytle_4' && ! is_paged() ) {
    		include( get_template_directory().'/includes/topslide-four-style.php' ); 
        }
	    else{
	    	echo "";
	    }
	?>
	<?php if( is_home() && ! is_paged() && suxingme('suxingme_get_index3') != 'three'){  ?>	
		<div class="recommend-content">
			<div class="container">
				<div class="row">
					
						<div class="cat">
							<div class="thumbnail-cat">
								<?php 
									if( suxingme('suxingme_get_index3') == 'one' ) :
										$categories=explode(",",suxingme('suxing_cat_index'));
										if( $categories[0] != '' ) :
											foreach ($categories as $cat=>$catid ) { ?>
												<div class="image">
													<div class="index-cat-box" style="background-image:url(<?php if (function_exists('z_taxonomy_image_url')) echo z_taxonomy_image_url($catid); ?>)">
														<a  class="iscat" href="<?php echo get_category_link($catid);?>"></a>
														<div class="promo-overlay"><h4><span><?php $cat = get_category($catid);echo $cat->name; ?></span></h4></div>
													</div>
												</div>
											<?php }
										else :
											echo '<h4>请在主题选项【CMS设置】-【首页3个区块-显示的分类】填写分类ID，并按照要求上传分类封面图。</h4>';
										endif;
									else: 
										$top_args = array(
											'showposts'=> '3',
											'post__in' => get_option( 'sticky_posts' ),
											'ignore_sticky_posts' => 1,
											);
										$top_posts = new WP_Query($top_args);
										if($top_posts->have_posts()):
											while($top_posts->have_posts()):$top_posts->the_post();
									?>
										<div class="image">
											<div class="index-cat-box" <?php if( suxingme('suxingme_post_target')) { echo 'target="_blank"';}?> style="background-image:url(<?php echo post_thumbnail_src(); ?>)">
												<a class="istop" href="<?php the_permalink();?>">
													<div class="overlay"></div>
													<div class="title"><span><?php $catarr=get_the_category(); echo $catarr[0]->cat_name; ?></span><h3><?php the_title();?></h3></div>
												</a>
											</div>
										</div>
									<?php endwhile; else : echo '<li>暂无文章</li>'; endif; endif;?>
							</div>
						</div>			
				
				
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if(suxingme('suxingme_new_post',true)) { ?>
		<div class="main-content">
			<div class="container">
				<div class="row">
					<div class="article col-xs-12 col-sm-8 col-md-8">
					<?php
						if( suxingme('suxing_index_custom_cat_tab',false) && suxingme('suxingme_ajax_posts',false) ){
							$count_posts = wp_count_posts();
							$published_posts = $count_posts->publish;
							$new_post_total = ceil($published_posts / get_option('posts_per_page'));
					?>
						<div class="post-nav">
							<span class="new-post current" data-paged="1" data-action="fa_load_postlist" data-home="true" data-total="<?php echo $new_post_total;?>">最新文章</span>
							<?php
								$str = suxingme('suxing_index_custom_cat_tab_id');
								$cat_arr = explode(",",$str);
								for ($i=0; $i < count($cat_arr); $i++) {
									$thisCat = get_category($cat_arr[$i]);
									$total = ceil($thisCat->count / get_option('posts_per_page')); 
									echo '<span class="cat-post" data-category="'.$thisCat->cat_ID.'" data-paged="1" data-action="fa_load_postlist" data-total="'.$total.'">'.$thisCat->cat_name.'</span>';
								}
							?>
							<?php
								$query_args = array(
									"posts_per_page" => get_option('posts_per_page'),
									"post_mime_type" => "video/mp4",
									"post_status" => "inherit",
									"post_type" => "attachment",
									"ignore_sticky_posts" => 1
								);
								$video_posts = new WP_Query($query_args);
								$video_total = $video_posts->max_num_pages;
							?>
							<span id="id_videoTab" class="video-post" data-paged="1" data-action="fa_load_postlist" data-home="true" data-total="<?php echo $video_total;?>">视频</span>
						</div>
					<?php } ?>

					<?php
						$args = array(
							'ignore_sticky_posts'=> 1,
							'paged' => $paged
						);
						if( suxingme('notinhome') ){
							$pool = array();
							foreach (suxingme('notinhome') as $key => $value) {
								if( $value ) $pool[] = $key;
							}
							$args['cat'] = '-'.implode($pool, ',-');
						}		
						query_posts($args);
						if ( have_posts() ) : ?>
							<div class="ajax-load-box posts-con">
								<?php while ( have_posts() ) : the_post(); 
									include( get_template_directory().'/includes/excerpt.php' );
								endwhile; ?>
							</div>
							<div class="clearfix"></div>
							<?php if( suxingme('suxingme_ajax_posts',true) ) { ?>
								<div id="ajax-load-posts">
									<?php echo fa_load_postlist_button();?>
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
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<?php get_footer(); ?>