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

global $ha_render_flag;
$ha_render_flag = false;

function hooks_assistant_enable_button( $wp_admin_bar ) {
	$args = array(
		'id'    => 'hooks-assistant',
		'title' => 'Hooks Assistant',
		'href'  => 'http://example.com/',
		'meta'  => array()
	);
	$wp_admin_bar->add_node( $args );
}


/**
 * @TODO add markup for each hook
 */
function hooks_assistant_render_hooks_editor( $hook ) {
	global $wp_actions, $ha_render_flag;

	if ( $hook === 'template_redirect' ) {
		$ha_render_flag = true;
	} elseif ( $hook === 'shutdown' ) {
		$ha_render_flag = false;
	}

	if ( ! $ha_render_flag ) {
		return;
	}

	if ( isset( $wp_actions[ $hook ] ) ) { ?>
        <div class="ha-element">
            <div class="ha-toggle"><span><?php echo $hook; ?></span></div>
            <div class="ha-editor-wrapper">
                <div class="ha-editor">
                    <textarea><?php $val = get_option('ha_value_for_'.$hook); echo $val ?></textarea>
                </div>
                <span class="ha-save-btn" data-hook="<?php echo $hook; ?>">Save changes</span>
            </div>
        </div>

		<?php
	}
}

function hooks_assistant_enqueue_scripts() {
	wp_enqueue_style( 'hooks-assistant-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), HOOKS_ASSISTANT_VERSION );
	wp_enqueue_script( 'hooks-assistant-scripts', plugin_dir_url( __FILE__ ) . 'js/script.js', array(
		'jquery',
		'code-editor',
		'wp-api'
	), HOOKS_ASSISTANT_VERSION, true );
}

function hooks_assistant_add_action_effects( $hook ) {
	global $wp_actions, $ha_render_flag;

	if ( $ha_render_flag && isset( $wp_actions[ $hook ] ) ) {
       $option = get_option( 'ha_value_for_' . $hook );


       add_action( $hook, function () use ( $option ) {
           eval( $option );
       });
	}

}

/**
 *
 */
function run_ha_plugin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	add_action( 'admin_bar_menu', 'hooks_assistant_enable_button', 90 );
	add_filter( 'all', 'hooks_assistant_render_hooks_editor' );
	add_filter( 'all', 'hooks_assistant_add_action_effects' );

	add_action( 'wp_enqueue_scripts', 'hooks_assistant_enqueue_scripts' );
}

add_action( 'init', 'run_ha_plugin' );


add_action( 'init', function () {
	require_once __DIR__ . '/inc/class-hooks-assistant-server.php';
	$wprb_rest_server = new Hooks_Assistant_Rest_Server();
	$wprb_rest_server->init();
} );
