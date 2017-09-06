<?php 
/*
Template Name: 视频列表(专家课堂)
*/

get_header();

?>
<style> 
    #zfcg{margin: 0 auto; overflow: hidden; width: 1200px; font-size: 15px;}
    .big_a{text-align: center; float: left;}
    .btcn{width: 275px; float: right; text-align: center;}
    .btcn_big_1{float: right; position: relative; text-align: center;}
    .btcn_big_2{float: right; position: relative;text-align: center; margin-top: 10px; }
    .btcn_big_1_1{width:275px;position: absolute;left: 87.5px; top:188px;text-align: center;}
    .btcn_big_1_1 a{float:left;width:100px; height:34px; line-height:34px;color: #fff; background: #3a3a3a;text-decoration: none;}
    .btcn_big_1_1 a:hover{background: #a054a4;}
    .btcn_big_2_1{width:275px;position: absolute;left: 30px; top:188px; text-align:center;}
    .btcn_big_2_1 a{float:left;width:100px; height:34px; line-height:34px;color: #fff; background: #3a3a3a;text-decoration: none;}
    .btcn_big_2_1 a:hover{background: #a054a4;}
    .btcn_big_2_1a{margin-right: 15px;}
    .title{background:url(../img/shipin_bj.png)}
</style>
<?php if(have_posts()): while(have_posts()):the_post();  


$linkcat = get_post_meta($post->ID, 'linkcat_value', true);

$link_cat_ids = explode(",",$linkcat);

foreach ( $link_cat_ids as $key => $value) {

}

?>
<div class="page-single page-nav"  >
	<div>
		<!--<div class="container">-->
			<h3 class="title">
				<?php the_title(); ?>
			</h3>
			<div class="page-dec">
				<?php the_content();?>
			</div>
		<!--</div>-->
	</div>
	
</div>
<?php endwhile; endif; ?>	
<?php get_footer(); ?>
