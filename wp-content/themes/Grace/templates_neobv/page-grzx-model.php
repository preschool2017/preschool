<?php 
/*
Template Name: 个人资料(模板 - 无头部&&底部)
*/
// get_header();
wp_head();
?>

<?php if(have_posts()): while(have_posts()):the_post();  


$linkcat = get_post_meta($post->ID, 'linkcat_value', true);

$link_cat_ids = explode(",",$linkcat);

foreach ( $link_cat_ids as $key => $value) {

}

?>
<!-- <div class="page-single page-nav" style="height:0px;"> -->
			<div class="page-dec">
				<?php the_content();?>
			</div>
<!-- </div> -->
<?php endwhile; endif; ?>
<?php wp_footer();?>