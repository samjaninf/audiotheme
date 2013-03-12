<?php
/**
 * Plugin Name: AudioTheme Framework
 * Plugin URI: http://audiotheme.com/
 * Description: AudioTheme framework plugin.
 * Version: 1.0.0
 * Author: AudioTheme
 * Author URI: http://audiotheme.com
 * Requires at least: 3.4.2
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc., 59
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package AudioTheme_Framework
 * @version 1.0.0
 * @author AudioTheme
 * @link http://audiotheme.com/
 * @copyright Copyright 2012 AudioTheme
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
*/

/**
 * The AudioTheme version.
 */
define( 'AUDIOTHEME_VERSION', '1.0.0' );

/**
 * Framework path and URL.
 *
 * If the framework is loaded from a different location, maybe a plugin or a
 * child theme, these constants will need to be defined beforehand.
 */
if ( ! defined( 'AUDIOTHEME_DIR' ) )
    define( 'AUDIOTHEME_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'AUDIOTHEME_URI' ) )
    define( 'AUDIOTHEME_URI', plugin_dir_url( __FILE__ ) );

/**
 * Begin setting up the framework during the after_setup_theme action.
 *
 * Ideally all functionality should be loaded via hooks so it can be disabled
 * or replaced by a theme or plugin if necessary.
 */
add_action( 'after_setup_theme', 'audiotheme_load', 5 );
add_action( 'after_setup_theme', 'audiotheme_load_admin', 5 );

/**
 * Load additional helper functions and libraries.
 */
require( AUDIOTHEME_DIR . 'includes/archives.php' );
require( AUDIOTHEME_DIR . 'includes/default-filters.php' );
require( AUDIOTHEME_DIR . 'includes/functions.php' );
require( AUDIOTHEME_DIR . 'includes/general-template.php' );
require( AUDIOTHEME_DIR . 'includes/load-p2p.php' );
require( AUDIOTHEME_DIR . 'includes/media.php' );
require( AUDIOTHEME_DIR . 'includes/options.php' );
require( AUDIOTHEME_DIR . 'widgets/widgets.php' );


/**
 * Load AudioTheme CPTs and corresponding functionality.
 */
require( AUDIOTHEME_DIR . 'discography/discography.php' );
require( AUDIOTHEME_DIR . 'gigs/gigs.php' );
require( AUDIOTHEME_DIR . 'videos/videos.php' );


/**
 * AudioTheme setup.
 *
 * @since 1.0.0
 */
function audiotheme_load() {
	// Default filters.
	add_filter( 'nav_menu_css_class', 'audiotheme_nav_menu_name_class', 1, 2 );
	add_filter( 'get_pages', 'audiotheme_page_list' );
	add_filter( 'page_css_class', 'audiotheme_page_list_classes', 10, 2 );
	add_filter( 'dynamic_sidebar_params', 'audiotheme_widget_count_class' );

	if ( ! is_admin() ) {
		add_filter( 'wp_get_nav_menu_items', 'audiotheme_nav_menu_classes', 1, 3 );
	}

	// Media filters.
	add_action( 'init', 'audiotheme_add_default_oembed_providers' );
	add_filter( 'embed_oembed_html', 'audiotheme_oembed_html', 10, 4 );
	add_filter( 'embed_handler_html', 'audiotheme_oembed_html', 10, 4 );

	add_action( 'init', 'audiotheme_register_scripts' );
	add_action( 'widgets_init', 'audiotheme_widgets_init' );

	// Archive filters.
	add_action( 'init', 'register_audiotheme_archives' );
	add_filter( 'post_type_link', 'audiotheme_archives_post_type_link', 10, 3 );
	add_filter( 'post_type_archive_link', 'audiotheme_archives_post_type_archive_link', 10, 2 );
	add_filter( 'post_type_archive_title', 'audiotheme_archives_post_type_archive_title' );

	add_action( 'post_updated', 'audiotheme_archives_post_updated', 10, 3 );
	add_action( 'delete_post', 'audiotheme_archives_deleted_post' );

	// Prevent the audiotheme_archive post type rules from being registered.
	add_filter( 'audiotheme_archive_rewrite_rules', '__return_empty_array' );
}

/**
 * Load admin-specific functions and libraries.
 *
 * Has to be loaded after the Theme Customizer in order to determine if the
 * Settings API should be included while customizing a theme.
 *
 * @since 1.0.0
 */
function audiotheme_load_admin() {
	global $wp_customize;

	if ( is_admin() || ( $wp_customize && $wp_customize->is_preview() ) ) {
		require( AUDIOTHEME_DIR . 'admin/admin.php' );
		audiotheme_admin_setup();
	}
}

/**
 * Register frontend scripts and styles for enqueuing when needed.
 *
 * @since 1.0.0
 * @link http://core.trac.wordpress.org/ticket/18909
 */
function audiotheme_register_scripts() {
	wp_register_script( 'jquery-fitvids', AUDIOTHEME_URI . 'includes/js/jquery.fitvids.js', array( 'jquery' ), '1.0', true );
	wp_register_script( 'jquery-jplayer', AUDIOTHEME_URI . 'includes/js/jquery.jplayer.min.js', array( 'jquery' ), '2.2.0', true );
	wp_register_script( 'jquery-jplayer-playlist', AUDIOTHEME_URI . 'includes/js/jquery.jplayer.playlist.min.js', array( 'jquery-jplayer' ), '2.1.0', true );
	wp_register_script( 'jquery-placeholder', AUDIOTHEME_URI . 'includes/js/jquery.placeholder.min.js', array( 'jquery' ), '2.0.7', true );
	wp_register_script( 'jquery-timepicker', AUDIOTHEME_URI . 'includes/js/jquery.timepicker.min.js', array( 'jquery' ), true );

	wp_localize_script( 'jquery-jplayer', 'AudiothemeJplayer', array(
		'swfPath' => AUDIOTHEME_URI . 'includes/js'
	) );
}
