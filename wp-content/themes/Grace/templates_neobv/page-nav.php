<?php 
/*
Template Name: 导航页面-无友链(kali@neobv.com)
*/
get_header();

?>
<?php if(have_posts()): while(have_posts()):the_post();  


$linkcat = get_post_meta($post->ID, 'linkcat_value', true);

$link_cat_ids = explode(",",$linkcat);

foreach ( $link_cat_ids as $key => $value) {

}

?>
<div class="page-single page-nav"  >
	<div>
		<!--<div class="container">-->
			<!--<h3 class="title">
				<?php 
				//the_title(); 
				?>
			</h3>-->
			<div class="page-dec">
				<?php the_content();?>
			</div>
		<!--</div>-->
	</div>
	
</div>
<?php endwhile; endif; ?>	
<?php get_footer(); ?>
