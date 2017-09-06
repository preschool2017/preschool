<?php 
/*
Template Name: 投稿页面
*/


$contribute_cat_id = explode(',',suxingme('contribute_cat_id'));

get_header();
if(have_posts()): while(have_posts()):the_post();  ?>
<div class="page-single" >
	<div class="page-title" style="background-image:url(<?php echo contri_banner_pic(); ?>);">
		<div class="container">
			<h1 class="title">
				<?php the_title(); ?>
			</h1>
		</div>
	</div>
	<div class="container">
		<div class="page-contribute">
			<div class="contribute-item contribute-content">
				<div class="form-group">
	              	<input type="text" name="title" id="title" class="form-control" placeholder="请输入标题">
	              	<div class="form-control-border"></div>
	          	</div>

	          	<div class="form-group">
	          		<div class="form-upimg">
	          			<span><i class="icon-picture"></i>添加图片</span>
		          		<input id="upimg" type="file" name="image">
		          		
		          	</div>
	          		<?php 
	          		wp_editor( 
	          			'', 
	          			'post_content', 
	          			array(
	          				'teeny' => false,
	          				'media_buttons'	=> false,
	          				'quicktags'=> false,
	          				'editor_css' =>'<style></style>',
	          				'dfw'	=> true,
	          				'editor_height' => 300,
	          			)
	          		); ?>
	          		<div class="form-control-border"></div>
	          	</div>
			</div>
			<div class="contribute-item contribute-meta">
				<h3><span>文章分类</span></h3>
				<p>选择本文的分类，可多选</p>
				<ul class="contribute-cat" id="contribute-cat">
					<?php
						for ($i=0; $i < count($contribute_cat_id) ; $i++) { 
							echo '<li data-id="'.$contribute_cat_id[$i].'">'.get_category($contribute_cat_id[$i])->name.'</li>';
						}
					?>
				</ul>
			</div>
			<div class="contribute-item contribute-copyright">
				<h3><span>版权说明</span></h3>
                <div class="suxing-radio">
                    <input type="radio" name="radio1" id="radio1" value="option1" checked>
                    <label for="radio1" class="radio" >
                        授权本站及本站有合作关系的第三方平台发布您的原创稿件<em>请您放心，授权会严格满足转载规范，标明您的姓名及来源等信息</em>
                    </label>
                    <div class="copy-meta">
	                        <div class="form-group">
				              	<input type="text" name="name" id="name" class="form-control" placeholder="请输入姓名">
				              	<div class="form-control-border"></div>
				          	</div>
				          	<div class="form-group">
				              	<input type="text" name="source" id="source" class="form-control" placeholder="请输入来源地址">
				              	<div class="form-control-border"></div>
				          	</div>
				          	<div class="form-group">
				              	<input type="text" name="email" id="email" class="form-control" placeholder="请输入邮箱地址（您的投稿状态将通过邮件通知您）">
				              	<div class="form-control-border"></div>
				          	</div>
			          </div>
                </div>
                <div class="suxing-radio">
                    <input type="radio" name="radio1" id="radio2" value="option2">
                    <label for="radio2">
                        匿名投稿
                    </label>
                </div>
			</div>
			<input id="nonce" type="hidden" value="<?php echo wp_create_nonce('do-contribute'); ?>">
			<button class="btn-contribute" id="do-contribute">提交稿件</button>
		</div>
	</div>
</div>
<?php endwhile; endif; ?>	
<?php get_footer(); ?>
