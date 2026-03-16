<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Funzione principale che renderizza la pagina Admin
 */
function wpwoo_cf_admin_page_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpwoocustomfunct';

    // 1. Recupero dati dal Database (per popolare l'editor)
    $db_data = $wpdb->get_var("SELECT options FROM $table_name WHERE id = 1");
    
    // Se il DB è vuoto, proviamo a leggere dal file custom.php (per non perdere dati esistenti)
    if ( empty($db_data) ) {
        $file_path = WPWOO_CF_PATH . 'custom.php';
        $db_data = file_exists($file_path) ? file_get_contents($file_path) : "<?php\n\n// Scrivi qui le tue funzioni...";
    }

    // Carichiamo l'editor nativo di WP (CodeMirror)
    wp_enqueue_code_editor( array( 'type' => 'text/x-php' ) );
    wp_enqueue_script( 'wpwoo-cf-admin-js', WPWOO_CF_URL . 'admin/admin-scripts.js', array('jquery'), '1.0', true );
    wp_localize_script( 'wpwoo-cf-admin-js', 'wpwoo_ajax', array( 'ajax_url' => admin_url('admin-ajax.php') ) );
    ?>

    <div class="wrap">
        <h1>WP Woo Custom Functions</h1>
        <p>Inserisci qui le tue funzioni personalizzate. Il sistema verificherà la stabilità prima di salvare.</p>

        <form id="wpwoo-cf-form" method="post">
            <textarea id="wpwoo_editor" name="wpwoo_editor"><?php echo esc_textarea($db_data); ?></textarea>
            
            <p class="submit">
                <button type="submit" id="btn-save" class="button button-primary">SALVA</button>
                <button type="button" id="btn-import" class="button button-secondary">IMPORTA DA DB (Emergenza)</button>
                <span class="spinner" style="float:none;"></span>
            </p>
        </form>
        <div id="wpwoo-feedback" style="margin-top:20px; padding:10px; display:none; border-radius:4px;"></div>
    </div>

    <script>
        // Inizializzazione CodeMirror
        jQuery(document).ready(function($) {
            var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
            editorSettings.codemirror = _.extend(
                {},
                editorSettings.codemirror,
                {
                    indentUnit: 4,
                    tabSize: 4,
                    mode: 'text/x-php',
                    autoCloseBrackets: true,
                    matchBrackets: true,
                    lineNumbers: true
                }
            );
            var editor = wp.codeEditor.initialize($('#wpwoo_editor'), editorSettings);
            
            // Passiamo l'istanza dell'editor globalmente per usarla negli script esterni
            window.wpwoo_cm_editor = editor.codemirror;
        });
    </script>

    <style>
        .CodeMirror { border: 1px solid #ddd; min-height: 75vh; border-radius: 10px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
    <?php
}

/**
 * Logica AJAX per il salvataggio
 */
add_action( 'wp_ajax_wpwoo_save_action', 'wpwoo_save_ajax_handler' );
function wpwoo_save_ajax_handler() {
    if ( ! current_user_can('manage_options') ) wp_send_json_error('Permessi insufficienti');

    $code = isset($_POST['code']) ? stripslashes($_POST['code']) : '';
    $temp_file = WPWOO_CF_PATH . 'custom-temp.php';
    $final_file = WPWOO_CF_PATH . 'custom.php';

    // 1. Scrittura temporanea
    file_put_contents($temp_file, $code);

    // 2. Loopback Test (chiamiamo il sito con il parametro di test)
    $test_url = add_query_arg( 'wpwoo_test_mode', '1', get_site_url() . '/' );
    $response = wp_remote_get( $test_url, array('timeout' => 15, 'sslverify' => false) );

    if ( is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200 ) {
        unlink($temp_file);
        wp_send_json_error('Errore Fatale Rilevato! Il sito è andato in crash durante il test. Modifica annullata.');
    }

    // 3. Tutto OK: rendiamo definitivo
    rename($temp_file, $final_file);
    
    global $wpdb;
    $wpdb->update(
        $wpdb->prefix . 'wpwoocustomfunct',
        array('options' => $code),
        array('id' => 1)
    );

    wp_send_json_success('Codice salvato correttamente su File e Database!');
}