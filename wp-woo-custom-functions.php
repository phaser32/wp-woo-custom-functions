<?php

/**
 * Plugin Name: WP Woo Custom Functions
 * Plugin URI: phaserdesign.net
 * Description: This simple plugin adds custom functions to Your wordpress/woocommerce installation.
 * Version: 1.0
 * Author: Phaser Design Ltd
 * Author URI: phaserdesign.net
 * License: GPL2
 */

//start nc_settings_link()
add_filter( 'plugin_action_links_wp-woo-custom-functions/wp-woo-custom-functions.php', 'nc_settings_link' );
function nc_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url(
		get_admin_url() . 'plugin-editor.php?file=wp-woo-custom-functions%2Fwp-woo-custom-functions.php&plugin=wp-woo-custom-functions%2Fwp-woo-custom-functions.php'
	);
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
}
//end nc_settings_link()

//start settings sidebar link
function add_menu_url ($page_title, $menu_title, $capability, $dest_url, $function = '', $icon_url = '', $position = NULL) {
    global $menu;

    $temp_slug = uniqid('my_random_temp_slug_');

    add_menu_page($page_title, $menu_title, $capability, $temp_slug, $function, $icon_url, $position);

    foreach ($menu as $position => &$page) {
        if ($page[2] === $temp_slug) {
            $page[2] = $dest_url;
            break;
        }
    }
}

add_action('admin_menu', function () {
    add_menu_url('linked_url', 'WPWoo CF', 'read', '/wp-admin/plugin-editor.php?file=wp-woo-custom-functions%2Fwp-woo-custom-functions.php&plugin=wp-woo-custom-functions%2Fwp-woo-custom-functions.php', '', get_bloginfo('url') . '/wp-content/plugins/wp-woo-custom-functions/images/phaserdesign.png', 92);
});
//end settings sidebar link



//  ___       _   _   _ ___         ___ 
//   |  |\/| |_) / \ |_) |  /\  |\ | |  
//  _|_ |  | |   \_/ | \ | /--\ | \| |  
//  
//  ******** START WRITING YOUR CODE FROM HERE DON'T EDIT NOTHING BEFORE *******




