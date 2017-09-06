<title><?php

	global $page, $paged,$post;
	$site_description = get_option('blogdescription');
	$other_title = get_post_meta($post->ID,"seo_title_value",true );
	$category_title = get_term_meta( get_query_var('cat'), 'suxing_term_title', true );
 	if ($site_description && ( is_home() || is_front_page() )) {
		bloginfo('name');
		if ( $paged >= 2 || $page >= 2 ){
			echo page_sign() . sprintf( __( '第%s页' ), max( $paged, $page ) );
		}
		echo page_sign();
		echo " $site_description";
	} else {
		
		if( is_category() ){
			echo !empty( $category_title  ) ? $category_title : trim(wp_title('',0));
		} else {
			if( $other_title ) {
				echo $other_title;
			} else {
				echo trim(wp_title('',0));
			}
		}		

		if ( $paged >= 2 || $page >= 2 ){
			echo page_sign() . sprintf( __( '第%s页' ), max( $paged, $page ) );
		}
		echo page_sign();
		bloginfo('name');
	}
?></title>
<?php
	global $s, $post;

	$description= '';
	$keywords = '';
	$blog_name = get_bloginfo('name');

	if ( is_single() ) {
		if( get_post_meta($post->ID,"seo_key_value",true ) ){
			$keywords = get_post_meta($post->ID,"seo_key_value",true );
		} else {
			if ( get_the_tags( $post->ID ) ) {
			  foreach ( get_the_tags( $post->ID ) as $tag ) $keywords .= $tag->name . ', ';
			}
			foreach ( get_the_category( $post->ID ) as $category ) $keywords .= $category->cat_name . ', ';
			$keywords = substr_replace( $keywords , '' , -2);
		}
	} elseif ( is_home () )    { $keywords = suxingme('suxingme_keywords');
	} elseif ( is_tag() )      { $keywords = single_tag_title('', false);
	} elseif ( is_category() ) { $keywords = get_term_meta( get_query_var('cat'), 'suxing_term_keywords', true );
	} elseif ( is_search() )   { $keywords = esc_html( $s, 1 );
	} elseif ( is_page () )    { $keywords = get_post_meta($post->ID,"seo_key_value",true );
	} else { $keywords = trim( wp_title('', false) );
	}
	if ( $keywords ) {
		echo "<meta name=\"keywords\" content=\"$keywords\">\n";
	}
	
	if ( is_singular() ) {
		if( get_post_meta($post->ID,"seo_description_value",true ) ){
			$description = get_post_meta($post->ID,"seo_description_value",true );
		} else {
			if( !empty( $post->post_excerpt ) ) {
			  $text = $post->post_excerpt;
			} else {
			  $text = $post->post_content;
			}
			$description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $text ) ) ) );
			if ( !( $description ) ) $description = $blog_name . "-" . trim( wp_title('', false) );
		}
	} elseif ( is_home () )    { $description = suxingme('suxingme_description'); 
	} elseif ( is_tag() )      { $description = $blog_name . "'" . single_tag_title('', false) . "'";
	} elseif ( is_category() ) { $description = get_term_meta( get_query_var('cat'), 'suxing_term_description', true ) ? get_term_meta( get_query_var('cat'), 'suxing_term_description', true ) : trim(strip_tags(category_description()));
	} elseif ( is_archive() )  { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
	} elseif ( is_search() )   { $description = $blog_name . ": '" . esc_html( $s, 1 ) . "' 的搜索結果";
	} elseif ( is_page () )    { $description = get_post_meta($post->ID,"seo_key_description",true );
	} else { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
	}
	$description = mb_substr( $description, 0, 220, 'utf-8' );
	echo "<meta name=\"description\" content=\"$description\">\n";
?>