<?php
/*
Plugin Name: Hooks Assistant
Plugin URI: http://themeisle.com
Description: A brief description of the Plugin.
Version: 1.0.0
Author: ThemeIsle
Author URI: http://themeisle.com
License: A "Slug" license name e.g. GPL2
*/

define( 'HOOKS_ASSISTANT_VERSION', '1.0.0' );

/**
 * @TODO: add button in admin bar
 */
function custom_button_example($wp_admin_bar){
	$args = array(
		'id' => 'hooks-assistant',
		'title' => 'Hooks Assistant',
		'href' => 'http://example.com/',
		'meta' => array()
	);
	$wp_admin_bar->add_node($args);
}

add_action( 'admin_bar_menu', 'custom_button_example', 90 );

/**
 * @TODO add markup for each hook
 */



/**
 * @TODO: enqueue styles and scripts
 */
function hooks_assistant_enqueue_scripts() {
	wp_enqueue_style( 'hooks-assistant-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), HOOKS_ASSISTANT_VERSION );
	wp_enqueue_script( 'hooks-assistant-scripts', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ), HOOKS_ASSISTANT_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'hooks_assistant_enqueue_scripts' );

/**
 * @TODO: class for saving code using options
 */