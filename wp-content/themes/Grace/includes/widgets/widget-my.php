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
			<option value="post_date" <?php selected('post_date', $instance['orderby']); ?>>发布时间</option>
			<option value="post_modified" <?php selected('post_modified', $instance['orderby']); ?>>修改时间</option>
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
        //SELECT * FROM wp_posts WHERE 1=1 AND wp_posts.post_type = 'dwqa-question' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private') ORDER BY wp_posts.post_date DESC LIMIT 0, 8
        $sql = "SELECT * FROM wp_posts WHERE 1=1 AND wp_posts.post_type = 'dwqa-question' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private') ORDER BY ".$orderby." DESC LIMIT 0, ".$limit;
        global $wpdb;
        $results = $wpdb->get_results($sql);
        //print_r($results);
        foreach($results as $ele) { ?>
                <li>
                    <div style="font-size:14px;height: 20px;padding-left: 30px;background: url(<?php echo get_template_directory_uri();?>/includes/images/question-ico.png) no-repeat;">
                        <span>
                            <a href="<?php echo $ele->guid; ?>" title="<?php echo $ele->post_title; ?>"><?php echo $ele->post_title; ?></a>
                        </span>
                    </div>
                </li>
            <?php }  wp_reset_query();?>
    </ul>
<?php
}
?>