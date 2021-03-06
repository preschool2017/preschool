<?php  
function dwqa_auto_change_question_status( $answer_id ){
	if( !is_wp_error( $answer_id ) ) {
    	$question_id = get_post_meta( $answer_id, '_question', true );
    	$answer = get_post( $answer_id );
    	if( $question_id && $answer->post_author ) {
    		$question_status = get_post_meta( $question_id, '_dwqa_status', true );
    		if( ! user_can( $answer->post_author, 'edit_posts' ) ) {
    			if( $question_status == 'resolved' ) {
                    update_post_meta( $question_id, '_dwqa_status', 're-open' );
                }
    		} else {
                if( $question_status == 're-open' ) {
                    update_post_meta( $question_id, '_dwqa_status', 'open' );
                }
            }
    	}
	}
}
add_action( 'dwqa_add_answer', 'dwqa_auto_change_question_status' );

//Update question status when have new comment
function dwqa_reopen_question_have_new_comment($comment_ID){
    $comment = get_comment( $comment_ID );
    $comment_post_type = get_post_type( $comment->comment_post_ID );
    $question = false;
    if( 'dwqa-answer' ==  $comment_post_type ) {
        $question = get_post_meta( $comment->comment_post_ID, '_question', true );
    } elseif ( 'dwqa-question' == $comment_post_type) {
        $question = $comment->comment_post_ID;
    }

    if( $question ) {
        $question_status = get_post_meta( $question, '_dwqa_status', true );
        if( ! user_can( $comment->user_id, 'edit_posts' ) ) {
            if( 'resolved' == $question_status ) {
                update_post_meta( $question, '_dwqa_status', 're-open' );
            }
        }
    }
}
add_action( 'wp_insert_comment', 'dwqa_reopen_question_have_new_comment' );

//Auto close question when question was resolved longtime
function dwqa_schedule_events() {
    if ( !wp_next_scheduled( 'dwqa_hourly_event' ) ) {
        wp_schedule_event( time(), 'hourly', 'dwqa_hourly_event');
    }
}
add_action('wp', 'dwqa_schedule_events');

function dwqa_do_this_hourly() {
    $questions = get_posts(  array(
        'numberposts'        =>    -1,
        'meta_query' => array(
               array(
                   'key'        => '_dwqa_status',
                   'value'      => 'closed',
                   'compare'    => '=',
               )
           ),
        'post_type'             => 'dwqa-question',
        'post_status'           => 'publish'
    ) );
    if( count($questions) > 0 ) {
        foreach ( $questions as $q ) {
            $resolved_time = get_post_meta( $q->ID, '_dwqa_resolved_time', true );
            if ( dwqa_is_resolved($q->ID) && ( time() - $resolved_time > (3 * 24 * 60 * 60) ) ) {
                update_post_meta( $q->ID, '_dwqa_status', 'resolved' );
            }
        }
    } 
}
add_action('dwqa_hourly_event', 'dwqa_do_this_hourly');


?>