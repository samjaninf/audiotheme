<?php
/**
 * Pulls an attachment ID from a post, if one exists
 *
 * @since 1.0
 */
function audiotheme_get_image_id( $num = 0 ) {
	global $post;

	$image_ids = array_keys( 
		get_children( 
			array( 
				'post_parent' => $post->ID,
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'orderby' => 'menu_order',
				'order' => 'ASC'
			 )
		 )
	 );

	if ( isset( $image_ids[$num] ) )
		return $image_ids[$num];

	return false;
}

/**
 * Pulls an image from the media gallery and returns it
 *
 * @since 1.0
 */
function audiotheme_get_image( $args = array() ) {
	global $post;

	$defaults = array( 
		'format' => 'html',
		'size' => 'full',
		'num' => 0,
		'attr' => ''
	);
	
	$defaults = apply_filters( 'audiotheme_get_image_default_args', $defaults );

	$args = wp_parse_args( $args, $defaults );

	// Allow child theme to short-circuit this function
	$pre = apply_filters( 'audiotheme_pre_get_image', false, $args, $post );
	if ( false !== $pre ) return $pre;

	// check for post image ( native WP )
	if ( has_post_thumbnail() && ( $args['num'] === 0 ) ) {
		$id = get_post_thumbnail_id();
		$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
		list( $url ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
	}
	// else pull the first image attachment
	else {
		$id = audiotheme_get_image_id( $args['num'] );
		$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
		list( $url ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
	}

	// source path, relative to the root
	$src = str_replace( home_url(), '', $url );

	// determine output
	if ( strtolower( $args['format'] ) == 'html' )
		$output = $html;
	elseif ( strtolower( $args['format'] ) == 'url' )
		$output = $url;
	else
		$output = $src;

	// return FALSE if $url is blank
	if ( empty( $url ) ) $output = false;

	// return FALSE if $src is invalid ( file doesn't exist )
	//if ( !file_exists( ABSPATH . $src ) ) $output = FALSE;

	// return data, filtered
	return apply_filters( 'audiotheme_get_image', $output, $args, $id, $html, $url, $src );
}

/**
 * Pulls an image from media gallery and echoes it
 *
 * @since 1.0
 */
function audiotheme_image( $args = array() ) {
	$image = audiotheme_get_image( $args );

	if ( $image )
		echo $image;
	else
		return false;
}

/**
 * Get Additional Image Sizes
 *
 * Pulls additional image sizes
 *
 * @since 1.0
 * @return array
 */
function audiotheme_get_additional_image_sizes() {
	global $_wp_additional_image_sizes;

	if ( $_wp_additional_image_sizes )
		return $_wp_additional_image_sizes;

	return array();
}

/**
 * Get Image Sizes
 *
 * Pulls all image sizes
 *
 * @since 1.0.2
 * @return array
 */
function audiotheme_get_image_sizes() {
	$builtin_sizes = array( 
		'large'		=> array( 
			'width' => get_option( 'large_size_w' ),
			'height' => get_option( 'large_size_h' )
		 ),
		'medium'	=> array( 
			'width' => get_option( 'medium_size_w' ),
			'height' => get_option( 'medium_size_h' )
		 ),
		'thumbnail'	=> array( 
			'width' => get_option( 'thumbnail_size_w' ),
			'height' => get_option( 'thumbnail_size_h' )
		 )
	 );

	$additional_sizes = audiotheme_get_additional_image_sizes();

	return array_merge( $builtin_sizes, $additional_sizes );
}