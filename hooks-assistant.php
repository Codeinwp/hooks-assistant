<?php
/*
Plugin Name: Hooks Assistant
Plugin URI: http://themeisle.com
Description: A brief description of the Plugin.
Version: 1.0
Author: ThemeIsle
Author URI: http://themeisle.com
License: A "Slug" license name e.g. GPL2
*/

/**
 * @TODO: add button in admin bar
 */

/**
 * @TODO add markup for each hook
 */

/**
 * @TODO: enqueue styles and scripts
 */

/**
 * @TODO: class for saving code using options
 */

add_action( 'init', function(){
	require_once __DIR__ . '/inc/class-hooks-assistant-server.php';
	$wprb_rest_server = new Hooks_Assistant_Rest_Server();
	$wprb_rest_server->init();
});