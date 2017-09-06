<?php
add_action('widgets_init', create_function('', 'return register_widget("My_Widget_Class");'));
class My_Widget_Class extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'description' => '展示问答模块未解决的问题' );
		parent::__construct('my_widget_class', __('问答展示'), $widget_ops);
	}

    function widget($args, $instance) {
        extract( $args );
		$limit = $instance['limit'];
		$title = apply_filters('widget_name', $instance['title']);
		$orderby      = $instance['orderby'];
		echo $before_widget;
        echo $before_title.$title.'<a style="margin-left:120px;" href="http://www.preschool.net.cn/interaction">更多</a>'.$after_title;
        echo suxingme_widget_aqlist($orderby,$limit);
        echo $after_widget;	
    }

	function form($instance) {
		$instance['title'] = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$instance['orderby'] = ! empty( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : '';
		$instance['limit']    = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 4;
?>
<p style="clear: both;padding-top: 5px;">
	<label>显示标题：（例如：最新问题）
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</label>
</p>
<p>
	<label> 排序方式：
		<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
			<option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
			<option value="rand" <?php selected('id', $instance['orderby']); ?>>发布ID</option>
		</select>
	</label>
</p>
<p>
	<label> 显示数目：
		<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
	</label>
</p>
<?php
	}
}

function suxingme_widget_aqlist($orderby,$limit){
    ?>
    <ul class="recent-posts-widget">
        <?php
        $args = array(
            'post_status' => 'publish', // 只选公开的文章.
            'post__not_in' => array(get_the_ID()),//排除当前文章
            'ignore_sticky_posts' => 1, // 排除置頂文章.
            'orderby' =>  $orderby, // 排序方式.
            'cat'     => $cat,
            'order'   => 'DESC',
            'showposts' => $limit,
            'tax_query' => array( array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array(
                    //请根据需要保留要排除的文章形式
                    'post-format-aside',

                ),
                'operator' => 'NOT IN',
            ) ),
        );
        $query_posts = new WP_Query();
        $query_posts->query($args);
        $i=1;
        while( $query_posts->have_posts() ) { $query_posts->the_post(); ?>
            <?php if($i == 1){ ?>
                <li class="one">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <div class="overlay"></div>
                        <?php if( suxingme('suxingme_timthumb') && suxingme('suxingme_timthumb_lazyload',true) ) { ?>
                            <img class="lazy thumbnail" data-original="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=175&w=315.98&zc=1" src="<?php echo constant("THUMB_SMALL_DEFAULT");?>" alt="<?php the_title(); ?>" />
                        <?php }
                        if ( suxingme('suxingme_timthumb') && !suxingme('suxingme_timthumb_lazyload',true) ) {	?>
                            <img class="thumbnail" src="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=175&w=315.98&zc=1" alt="<?php the_title(); ?>" />
                        <?php } if( suxingme('suxingme_timthumb_lazyload',true) && !suxingme('suxingme_timthumb') ){ ?>
                            <img src="<?php echo constant("THUMB_SMALL_DEFAULT");?>" data-original="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" class="lazy thumbnail" />
                        <?php } ?>
                        <?php if( !suxingme('suxingme_timthumb_lazyload',true) && !suxingme('suxingme_timthumb')){ ?>
                            <img src="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" class="thumbnail" />
                        <?php } ?>
                        <div class="title">
                            <span><?php echo timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ); ?></span>
                            <h4><?php the_title(); ?></h4>
                        </div>
                    </a>
                </li>
            <?php }else{ ?>
                <li class="others">
                    <div class="image"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php if( suxingme('suxingme_timthumb') && suxingme('suxingme_timthumb_lazyload',true)) { ?>
                                <img class="lazy thumbnail" data-original="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=75&w=100&zc=1" src="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo constant("THUMB_SMALL_DEFAULT");?>&h=75&w=100&zc=1" alt="<?php the_title(); ?>" />
                            <?php }elseif (suxingme('suxingme_timthumb')) {	?>
                                <img src="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=75&w=100&zc=1" alt="<?php the_title(); ?>" class="thumbnail"/>
                            <?php } else { ?>
                                <img src="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" class="thumbnail" />
                            <?php } ?>

                        </a></div>
                    <div class="title">
                        <h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
                        <span><?php echo timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ); ?></span>

                    </div>
                </li>
            <?php } $i++;} wp_reset_query();?>
    </ul>
<?php
}
?>