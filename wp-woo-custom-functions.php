<?php
/**
 * Plugin Name: WP Woo Custom Functions
 * Plugin URI: phaserdesign.net
 * Description: Gestione sicura di funzioni personalizzate con salvataggio su DB e Loopback Test.
 * Version: 2.0
 * Author: Phaser Design Ltd
 * Author URI: phaserdesign.net
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Definizione costanti per comodità
define( 'WPWOO_CF_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPWOO_CF_URL', plugin_dir_url( __FILE__ ) );

/**
 * 1. ATTIVAZIONE: Creazione Tabella nel Database
 */
register_activation_hook( __FILE__, 'wpwoo_cf_install' );
function wpwoo_cf_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpwoocustomfunct';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL,
        options longtext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // Inseriamo la riga master se vuota
    $exists = $wpdb->get_var("SELECT id FROM $table_name WHERE id = 1");
    if ( ! $exists ) {
        $wpdb->insert( $table_name, array( 'id' => 1, 'options' => '' ) );
    }
}

/**
 * 2. ESECUZIONE: Inclusione del codice personalizzato
 */
add_action( 'plugins_loaded', 'wpwoo_cf_load_custom_code' );
function wpwoo_cf_load_custom_code() {
    $temp_file = WPWOO_CF_PATH . 'custom-temp.php';
    $final_file = WPWOO_CF_PATH . 'custom.php';

    // Se stiamo facendo il test di sicurezza, carichiamo il file temporaneo
    if ( isset( $_GET['wpwoo_test_mode'] ) && file_exists( $temp_file ) ) {
        include_once $temp_file;
    } 
    // Altrimenti carichiamo quello stabile
    elseif ( file_exists( $final_file ) ) {
        include_once $final_file;
    }
}

/**
 * 3. MENU: Creazione della nuova voce di menu dedicata
 */
add_action( 'admin_menu', 'wpwoo_cf_menu' );
function wpwoo_cf_menu() {
    add_menu_page(
        'WPWoo Custom Functions',
        'WPWoo CF',
        'manage_options',
        'wpwoo-cf-admin',
        'wpwoo_cf_admin_page_callback',
        WPWOO_CF_URL . 'images/phaserdesign.png',
        92
    );
}

// Includiamo la logica della pagina admin (la scriveremo nel file admin-page.php)
require_once WPWOO_CF_PATH . 'admin/admin-page.php';

/**
 * 4. LINK SETTINGS: Aggiorniamo il link nella pagina plugin
 */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpwoo_cf_settings_link' );
function wpwoo_cf_settings_link( $links ) {
    $url = admin_url( 'admin.php?page=wpwoo-cf-admin' );
    $settings_link = "<a href='$url'>" . __( 'Settings' ) . "</a>";
    array_push( $links, $settings_link );
    return $links;
}