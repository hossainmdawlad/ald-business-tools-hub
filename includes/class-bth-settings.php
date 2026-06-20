<?php
/**
 * Plugin Settings Page
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BTH_Settings {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=bth_tool',
            __( 'Tools Hub Settings', 'ald-business-tools' ),
            __( 'Settings', 'ald-business-tools' ),
            'manage_options',
            'bth-settings',
            array( $this, 'render_page' )
        );
    }

    public function register_settings() {
        register_setting( 'bth_settings_group', 'bth_currency_api_key', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ) );
        register_setting( 'bth_settings_group', 'bth_default_currency_from', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'USD',
        ) );
        register_setting( 'bth_settings_group', 'bth_default_currency_to', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'BDT',
        ) );
        register_setting( 'bth_settings_group', 'bth_invoice_prefix', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'INV-',
        ) );
        register_setting( 'bth_settings_group', 'bth_tools_per_page', array(
            'sanitize_callback' => 'absint',
            'default'           => 12,
        ) );

        // General section
        add_settings_section( 'bth_general_section', __( 'General Settings', 'ald-business-tools' ), null, 'bth-settings' );
        add_settings_field( 'bth_tools_per_page', __( 'Tools Per Page', 'ald-business-tools' ), array( $this, 'render_number_field' ), 'bth-settings', 'bth_general_section', array( 'name' => 'bth_tools_per_page' ) );

        // Currency section
        add_settings_section( 'bth_currency_section', __( 'Currency Converter', 'ald-business-tools' ), function() {
            echo '<p>' . __( 'Free API key from https://www.exchangerate-api.com/ (optional, fallback to free tier)', 'ald-business-tools' ) . '</p>';
        }, 'bth-settings' );
        add_settings_field( 'bth_currency_api_key', __( 'API Key', 'ald-business-tools' ), array( $this, 'render_text_field' ), 'bth-settings', 'bth_currency_section', array( 'name' => 'bth_currency_api_key' ) );
        add_settings_field( 'bth_default_currency_from', __( 'Default From', 'ald-business-tools' ), array( $this, 'render_text_field' ), 'bth-settings', 'bth_currency_section', array( 'name' => 'bth_default_currency_from' ) );
        add_settings_field( 'bth_default_currency_to', __( 'Default To', 'ald-business-tools' ), array( $this, 'render_text_field' ), 'bth-settings', 'bth_currency_section', array( 'name' => 'bth_default_currency_to' ) );

        // Invoice section
        add_settings_section( 'bth_invoice_section', __( 'Invoice Generator', 'ald-business-tools' ), null, 'bth-settings' );
        add_settings_field( 'bth_invoice_prefix', __( 'Invoice Prefix', 'ald-business-tools' ), array( $this, 'render_text_field' ), 'bth-settings', 'bth_invoice_section', array( 'name' => 'bth_invoice_prefix' ) );

        // Button Colors section
        add_settings_section( 'bth_button_section', __( 'Button Colors', 'ald-business-tools' ), function() {
            echo '<p>' . __( 'Customize the primary and secondary button colors across all tools.', 'ald-business-tools' ) . '</p>';
        }, 'bth-settings' );
        add_settings_field( 'bth_btn_primary_color', __( 'Primary Button Color', 'ald-business-tools' ), array( $this, 'render_color_field' ), 'bth-settings', 'bth_button_section', array( 'name' => 'bth_btn_primary_color', 'default' => '#D60000' ) );
        add_settings_field( 'bth_btn_primary_hover', __( 'Primary Button Hover', 'ald-business-tools' ), array( $this, 'render_color_field' ), 'bth-settings', 'bth_button_section', array( 'name' => 'bth_btn_primary_hover', 'default' => '#b80000' ) );
        add_settings_field( 'bth_btn_secondary_color', __( 'Secondary Button Color', 'ald-business-tools' ), array( $this, 'render_color_field' ), 'bth-settings', 'bth_button_section', array( 'name' => 'bth_btn_secondary_color', 'default' => '#f5f5f5' ) );
        add_settings_field( 'bth_btn_secondary_hover', __( 'Secondary Button Hover', 'ald-business-tools' ), array( $this, 'render_color_field' ), 'bth-settings', 'bth_button_section', array( 'name' => 'bth_btn_secondary_hover', 'default' => '#e5e5e5' ) );
    }

    public function render_text_field( $args ) {
        $value = get_option( $args['name'], '' );
        echo '<input type="text" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $value ) . '" class="regular-text">';
    }

    public function render_number_field( $args ) {
        $value = get_option( $args['name'], 12 );
        echo '<input type="number" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $value ) . '" min="1" max="100" class="small-text">';
    }

    public function render_color_field( $args ) {
        $default = isset( $args['default'] ) ? $args['default'] : '#000000';
        $value   = get_option( $args['name'], $default );
        echo '<input type="color" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $value ) . '" style="width:60px;height:34px;padding:0;border:1px solid #8c8f94;border-radius:4px;cursor:pointer;">';
        echo ' <code style="font-size:13px;color:#666;">' . esc_html( $value ) . '</code>';
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'ALD Business Tools Hub — Settings', 'ald-business-tools' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'bth_settings_group' );
                do_settings_sections( 'bth-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

if ( is_admin() ) {
    new BTH_Settings();
}
