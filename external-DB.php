<?php
/*
 *Plugin Name: Db-Delta External DB
 *Plugin URI: https://github.com/Db-Delta/Db-Delta
 *Description: Db-Delta External DB
 *Version: 1.0.0
 *Author: Arafat
 *Author URI: https://github.com/arafat-zip 
 */
class Db_Delta_External_DB {
    function __construct() {
        register_activation_hook(__FILE__,[$this, 'activate'] );
        add_action('plugin_loaded',[$this, 'init_plugin'] );
        add_action( 'admin_enqueue_scripts' , [$this, 'enqueue_assets'] );
        add_action( 'wp_ajax_db_delta_ajax_form', [$this, 'db_delta_ajax_form'] );
    }

    function enqueue_assets( $hook ) {
        if ( $hook !== 'toplevel_page_db_delta' ) {
            return;
        }
        wp_enqueue_style( 'db-delta-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css',[] , time() );
        wp_enqueue_script( 'db-delta-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js',['jquery'], time() );
        $data = [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'db_delta_ajax' ),
        ];
        wp_localize_script( 'db-delta-script', 'dbDelta', $data );
    }
    function activate() {
        if (! function_exists( 'dbDelta' )) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }
        global $wpdb;
        $table = $wpdb->prefix . 'db_delta';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(55) NOT NULL,
            phone INT(55) NOT NULL,
            email varchar(55) NOT NULL,
            PRIMARY KEY  (id),
            create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta( $sql );
    }
    function init_plugin() {
        add_action( 'admin_menu', [ $this, 'add_menu_items' ] );
    }
    function add_menu_items() {
        add_menu_page(
            'DB-Delta',
            'DB-Delta',
            'manage_options',
            'db_delta',
            [$this, 'db_delta_page'],
            'dashicons-admin-generic',
            100
        );
    }
    function db_delta_page() {
        $tab = isset( $_GET['tab']) ? $_GET['tab'] : 'list';
        $id = isset( $_GET['id']) ? $_GET['id'] : 0;
        switch( $tab ){
            case 'add':
                $this->add_contact();
                break;
                case 'edit':
                $this->edit_contact();
                break;
                default:
                $this->list_contact();
                break;
        }
    }
    function list_contact() {
        include 'templates/list_contact.php';
    }
    function add_contact() {
        include 'templates/add_contact.php';
    }
    function edit_contact() {
        include 'templates/edit_contact.php';
    }
    function db_delta_ajax_form() {
        check_ajax_referer('db_delta_ajax');
        $name = sanitize_text_field( $_POST['name'] );
        $phone = sanitize_text_field( $_POST['phone'] );
        $email = sanitize_text_field( $_POST['email'] );

        global $wpdb;
        $table = $wpdb->prefix . 'db_delta';
        $wpdb->insert( $table, [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
        ] );
        echo json_encode( 'success' );
    }
}
new Db_Delta_External_DB();