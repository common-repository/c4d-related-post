<?php
/*
Plugin Name: C4D Related Post
Plugin URI: http://coffee4dev.com/
Description: List related posts. Please install C4D Plugin Manager and Redux Framework to enable all features.
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-related-post
Version: 2.0.3
*/

define('C4DRELATEDPOST_PLUGIN_URI', plugins_url('', __FILE__));

add_shortcode('c4d-related-post', 'c4d_related_post');
add_filter( 'plugin_row_meta', 'c4d_related_post_plugin_row_meta', 10, 2 );

function c4d_related_post_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'forum' => '<a href="http://coffee4dev.com/forums/">Forum</<a>',
            'redux' => '<a href="https://wordpress.org/plugins/redux-framework/">Redux Framework</<a>',
            'c4dpluginmanager' => '<a href="https://wordpress.org/plugins/c4d-plugin-manager/">C4D Plugin Manager</a>'
        );
        
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}

function c4d_related_post_safely_add_stylesheet_to_frontsite() {
	if(!defined('C4DPLUGINMANAGER_OFF_JS_CSS')) {
		wp_enqueue_style( 'c4d-related-post-frontsite-style', C4DRELATEDPOST_PLUGIN_URI.'/assets/default.css' );
		wp_enqueue_script( 'c4d-related-post-frontsite-plugin-js', C4DRELATEDPOST_PLUGIN_URI.'/assets/default.js', array( 'jquery' ), false, true ); 
	}
    wp_localize_script( 'jquery', 'c4d_related_post',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

function c4d_related_post($params) {
	$html = '';
	if (!is_array($params)) $params = array();
	$default = array(
		'image_size' => 'thumbnail',
		'cols' => 4
	);
	$params = array_merge($default, $params);

	global $post;
	$tags = wp_get_post_tags($post->ID);
	$tag_ids = array();

	if ($tags) {
		foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
		
		$args=array(
			'post__not_in' 		=> array($post->ID),
			'posts_per_page'	=> isset($params['count']) ? $params['count'] : 4,
			'ignore_sticky_posts'	=> true,
			'post_status'       => 'publish',
			'post_type' 		=> 'post',
	        'orderby'   		=> 'date',
        	'order'     		=> 'desc'
	    );

		if (count($tag_ids) > 0) {
			$args['tag__in'] = $tag_ids;
		}

		$q = new WP_Query( $args );

		ob_start();
		$template = get_template_directory() .'c4d-related-post/templates/default.php';
		if ($template && file_exists($template)) {
			require $template;
		} else {
			require dirname(__FILE__). '/templates/default.php';
		}
		$html = ob_get_contents();
		$html = do_shortcode($html);
		ob_end_clean();
	}

	wp_reset_postdata();
	wp_reset_query();
	
	return $html;
}