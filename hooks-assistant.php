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
 * This snippet has been updated to reflect the official supporting of options pages by CMB2
 * in version 2.2.5.
 *
 * If you are using the old version of the options-page registration,
 * it is recommended you swtich to this method.
 */
define('HK_BASE_FILE', __FILE__);

include plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
include plugin_dir_path( __FILE__ ) . 'inc/cmb_field_select2.php';

add_action('cmb2_admin_init', 'hooks_assitant_register_theme_options_metabox');
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function hooks_assitant_register_theme_options_metabox()
{

    global $wp_filter;

    $hooks_list = array_keys($wp_filter);
    $hooks_list = array_combine($hooks_list, $hooks_list);

    /**
     * Registers options page menu item and form.
     */
    $cmb_options = new_cmb2_box(array(
        'id' => 'hooks_assitant_option_metabox',
        'title' => esc_html__('Hooks Assistant', 'hooks_assitant'),
        'object_types' => array('options-page'),

        /*
         * The following parameters are specific to the options-page box
         * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
         */

        'option_key' => 'hooks_assitant_options', // The option key and admin menu page slug.
        // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        // 'menu_title'      => esc_html__( 'Options', 'hooks_assitant' ), // Falls back to 'title' (above).
        'parent_slug' => 'tools.php', // Make options page a submenu item of the themes menu.
        // 'capability'      => 'manage_options', // Cap required to view options-page.
        // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        // 'save_button'     => esc_html__( 'Save Theme Options', 'hooks_assitant' ), // The text for the options-page save button. Defaults to 'Save'.
    ));

    /*
     * Options fields ids only need
     * to be unique within this box.
     * Prefix is not needed.
     */

    $cmb_options->add_field(array(
        'name' => __('Hooks list', 'hooks_assitant'),
        'desc' => __('choose hooks to disable', ''),
        'id' => 'hk_disble',
        'type' => 'hk_multiselect',
        'default' => '',
        'options' => $hooks_list,
    ));

    $cmb_options->add_field(array(
        'name' => __('Hooks list', 'hooks_assitant'),
        'desc' => __('Choose hooks to disable', 'hooks_assitant'),
        'id' => 'hk_disble',
        'type' => 'hk_multiselect',
        'default' => '',
        'options' => $hooks_list,
    ));

    $cmb_options->add_field(array(
        'name' => __('Presets', 'hooks_assitant'),
        'desc' => __('Custom presets for routines', 'hooks_assitant'),
        'type' => 'title',
        'id' => 'hk_presets',
    ));

    $presets = hk_get_all_presets();
    foreach( $presets as $key => $preset ) {

        $cmb_options->add_field(array(
            'name' => $preset['name'],
            'desc' => $preset['desc'],
            'type' => 'checkbox',
            'id' => $key,
        ));
    }

    $cmb_options->add_field(array(
        'name' => __('Disable Comments', 'hooks_assitant'),
        'desc' => __('Disable comments and discussion for posts', 'hooks_assitant'),
        'type' => 'checkbox',
        'id' => 'hk_presets',
    ));


}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key Options array key
 * @param  mixed $default Optional default value
 * @return mixed           Option value
 */
function hooks_assitant_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('hooks_assitant_options', $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('hooks_assitant_options', $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}

add_action('wp_loaded', 'hk_get_hooks_to_remove');
function hk_get_hooks_to_remove()
{

    $hooks = hooks_assitant_get_option('hk_disble');

    if (empty($hooks)) {
        return;
    }

    foreach ($hooks as $hook) {
        remove_all_filters($hook);
    }

    $presets = hk_get_all_presets();

    foreach( $presets as $key=>$value ) {

        if( function_exists( $key ) ) {
            call_user_func( $key );
        }
    }

}


function hk_get_all_presets() {
    $presets = array(
        'hk_presets_disable_comments' => array(
            'name' => __('Disable Comments', 'hooks_assitant'),
            'desc' => __('Disable comments and discussion for posts', 'hooks_assitant')),

    );
    return $presets;
}   