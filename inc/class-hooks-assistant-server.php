<?php

/**
 * Class Hooks_Assistant_Rest_Server
 *
 * Example rest server that allows for CRUD operations on the wp_options table
 *
 */
class Hooks_Assistant_Rest_Server extends WP_Rest_Controller {

	public $namespace = 'hooks-assistant/';
	public $version = 'v1';

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$namespace = $this->namespace . $this->version;

		register_rest_route( $namespace, '/get_hook_value', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_hook_value' ),
				'permission_callback' => array( $this, 'get_options_permission' )
			),
		) );

		register_rest_route( $namespace, '/set_hook_value', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'set_hook_value' ),
				'permission_callback' => array( $this, 'get_options_permission' )
			),
		) );

	}

	public function get_hook_value( WP_REST_Request $request ){

		if ( ! empty( $request['hook'] ) ) {
			return rest_ensure_response( 'No hook' );
		}

		$hook = esc_attr( $request['hook'] );

		get_option( 'ha_value_for_' .$hook );

	}

	public function set_hook_value( WP_REST_Request $request ){

		if ( empty( $request['hook'] ) ) {
			return rest_ensure_response( 'No hook' );
		}

		$hook = esc_attr( $request['hook'] );

		if ( ! isset( $request['value'] ) ) {
			return rest_ensure_response( 'No value' );
		}

		$value = $request['value'];

		$result = update_option( 'ha_value_for_' . $hook, $value );

		wp_send_json( $value );

		if ( $result ) {
			return rest_ensure_response( 'done' );
		}
		return rest_ensure_response( 'error' );
	}

	public function get_options_permission() {
		if ( ! current_user_can( 'install_themes' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to manage options.', 'textdomain' ), array( 'status' => 401 ) );
		}

		return true;
	}


}