<?php
/**
 * Plugin Name:       ALD Business Tools Hub
 * Plugin URI:        https://github.com/hossainmdawlad/ald-business-tools-hub
 * Description:       A collection of free online business tools for visitors — currency converter, profit calculator, QR generator, meta tags, invoice generator, image tools, and more.
 * Version:           1.0.0
 * Author:            AWLAD
 * Author URI:        https://github.com/hossainmdawlad
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ald-business-tools
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'BTH_VERSION', '1.0.0' );
define( 'BTH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BTH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BTH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main plugin class.
 */
final class ALD_Business_Tools_Hub {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once BTH_PLUGIN_DIR . 'includes/class-bth-post-type.php';
        require_once BTH_PLUGIN_DIR . 'includes/class-bth-settings.php';
        require_once BTH_PLUGIN_DIR . 'includes/class-bth-shortcodes.php';
        require_once BTH_PLUGIN_DIR . 'includes/class-bth-ajax.php';
    }

    private function init_hooks() {
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
        add_action( 'init', array( 'BTH_Post_Type', 'register' ) );
        add_action( 'init', array( 'BTH_Shortcodes', 'register' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_bth_tool', array( $this, 'save_meta' ) );
        add_action( 'wp_ajax_bth_currency_convert', array( 'BTH_Ajax', 'currency_convert' ) );
        add_action( 'wp_ajax_nopriv_bth_currency_convert', array( 'BTH_Ajax', 'currency_convert' ) );
        add_filter( 'single_template', array( $this, 'load_single_template' ) );
        add_filter( 'archive_template', array( $this, 'load_archive_template' ) );
        add_action( 'wp_head', array( $this, 'output_button_color_css' ), 20 );
        add_action( 'pre_get_posts', array( $this, 'set_tools_per_page' ) );
    }

    /**
     * Set tools per page on archive pages
     */
    public function set_tools_per_page( $query ) {
        if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'bth_tool' ) || is_tax( 'bth_tool_category' ) ) ) {
            $per_page = get_option( 'bth_tools_per_page', 12 );
            $query->set( 'posts_per_page', absint( $per_page ) );
        }
    }

    /**
     * Output dynamic button color CSS from settings.
     */
    public function output_button_color_css() {
        $primary   = get_option( 'bth_btn_primary_color', '#D60000' );
        $primary_h = get_option( 'bth_btn_primary_hover', '#b80000' );
        $secondary = get_option( 'bth_btn_secondary_color', '#f5f5f5' );
        $secondary_h = get_option( 'bth_btn_secondary_hover', '#e5e5e5' );

        echo '<style id="bth-button-colors">';
        echo ':root{--bth-primary:' . esc_attr( $primary ) . ';}';
        echo '.bth-btn-primary{background:' . esc_attr( $primary ) . ' !important;}';
        echo '.bth-btn-primary:hover{background:' . esc_attr( $primary_h ) . ' !important;}';
        echo '.bth-btn-secondary{background:' . esc_attr( $secondary ) . ' !important;border-color:' . esc_attr( $secondary ) . ' !important;}';
        echo '.bth-btn-secondary:hover{background:' . esc_attr( $secondary_h ) . ' !important;border-color:' . esc_attr( $secondary_h ) . ' !important;}';
        echo '</style>' . "\n";
    }

    /**
     * Load custom single template for bth_tool CPT.
     */
    public function load_single_template( $template ) {
        global $post;
        if ( $post && $post->post_type === 'bth_tool' ) {
            $plugin_template = BTH_PLUGIN_DIR . 'templates/single-bth_tool.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Load custom archive template for bth_tool CPT.
     */
    public function load_archive_template( $template ) {
        if ( is_post_type_archive( 'bth_tool' ) || is_tax( 'bth_tool_category' ) ) {
            $plugin_template = BTH_PLUGIN_DIR . 'templates/archive-tool.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }

    public function add_meta_boxes() {
        add_meta_box( 'bth_tool_settings', __( 'Tool Settings', 'ald-business-tools' ), array( $this, 'render_meta_box' ), 'bth_tool', 'side', 'high' );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'bth_save_meta', 'bth_meta_nonce' );
        $tool_type = get_post_meta( $post->ID, '_bth_tool_type', true );
        $tool_icon = get_post_meta( $post->ID, '_bth_tool_icon', true );
        if ( ! $tool_icon ) $tool_icon = '🔧';

        $tool_types = array(
            'custom'              => __( 'Custom (use content editor)', 'ald-business-tools' ),
            'currency-converter'  => __( 'Currency Converter', 'ald-business-tools' ),
            'profit-calculator'   => __( 'Profit Margin Calculator', 'ald-business-tools' ),
            'qr-generator'        => __( 'QR Code Generator', 'ald-business-tools' ),
            'meta-tag-generator'  => __( 'Meta Tag Generator', 'ald-business-tools' ),
            'invoice-generator'   => __( 'Invoice Generator', 'ald-business-tools' ),
            'color-palette'       => __( 'Color Palette Generator', 'ald-business-tools' ),
            'image-compressor'    => __( 'Image Compressor', 'ald-business-tools' ),
            'image-resizer'       => __( 'Social Media Image Resizer', 'ald-business-tools' ),
            'image-converter'     => __( 'Image Converter', 'ald-business-tools' ),
            'background-remover'  => __( 'Background Remover', 'ald-business-tools' ),
            'utm-builder'         => __( 'UTM Link Builder', 'ald-business-tools' ),
        );
        ?>
        <div class="bth-tool-type-field">
            <label for="bth_tool_type"><strong><?php esc_html_e( 'Tool Type', 'ald-business-tools' ); ?></strong></label>
            <select id="bth_tool_type" name="bth_tool_type" style="width:100%;margin-top:4px;">
                <?php foreach ( $tool_types as $val => $label ) : ?>
                    <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $tool_type, $val ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php esc_html_e( 'Select the tool type to auto-load the correct template.', 'ald-business-tools' ); ?></p>
        </div>
        <div class="bth-tool-type-field">
            <label for="bth_tool_icon"><strong><?php esc_html_e( 'Tool Icon (emoji)', 'ald-business-tools' ); ?></strong></label>
            <input type="text" id="bth_tool_icon" name="bth_tool_icon" value="<?php echo esc_attr( $tool_icon ); ?>" style="width:100%;margin-top:4px;">
            <p class="description"><?php esc_html_e( 'Enter an emoji, e.g. 💰 🔧 📊', 'ald-business-tools' ); ?></p>
        </div>
        <?php
    }

    public function save_meta( $post_id ) {
        if ( ! isset( $_POST['bth_meta_nonce'] ) || ! wp_verify_nonce( $_POST['bth_meta_nonce'], 'bth_save_meta' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        if ( isset( $_POST['bth_tool_type'] ) ) {
            update_post_meta( $post_id, '_bth_tool_type', sanitize_text_field( $_POST['bth_tool_type'] ) );
        }
        if ( isset( $_POST['bth_tool_icon'] ) ) {
            update_post_meta( $post_id, '_bth_tool_icon', sanitize_text_field( $_POST['bth_tool_icon'] ) );
        }
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'ald-business-tools', false, dirname( BTH_PLUGIN_BASENAME ) . '/languages' );
    }

    public function enqueue_frontend() {
        wp_enqueue_style( 'bth-frontend', BTH_PLUGIN_URL . 'assets/css/frontend.css', array(), BTH_VERSION );
        wp_enqueue_script( 'bth-frontend', BTH_PLUGIN_URL . 'assets/js/frontend.js', array(), BTH_VERSION, true );
        wp_localize_script( 'bth-frontend', 'bthData', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'bth_nonce' ),
            'pluginUrl' => BTH_PLUGIN_URL,
        ) );

        // Load QR Code library locally (used by QR tool shortcode on any page)
        wp_enqueue_script( 'bth-qrcode', BTH_PLUGIN_URL . 'assets/js/vendor/qrcode.min.js', array(), '1.0.0', true );

        // Load PDF generation libraries locally (used by invoice generator)
        wp_enqueue_script( 'bth-html2canvas', BTH_PLUGIN_URL . 'assets/js/vendor/html2canvas.min.js', array(), '1.4.1', true );
        wp_enqueue_script( 'bth-jspdf', BTH_PLUGIN_URL . 'assets/js/vendor/jspdf.umd.min.js', array(), '2.5.1', true );
    }

    public function enqueue_admin( $hook ) {
        $screen = get_current_screen();
        if ( $screen && ( $screen->post_type === 'bth_tool' || $screen->id === 'bth_tool' ) ) {
            wp_enqueue_style( 'bth-admin', BTH_PLUGIN_URL . 'assets/css/admin.css', array(), BTH_VERSION );
            wp_enqueue_script( 'bth-admin', BTH_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), BTH_VERSION, true );
        }
    }
}

/**
 * Auto-create default tool posts on plugin activation
 */
function bth_activate_plugin() {
    // Ensure post type is registered first
    BTH_Post_Type::register();
    flush_rewrite_rules();

    $default_tools = array(
        'currency-converter'  => array( 'title' => 'Currency Converter',  'icon' => '💱', 'cat' => 'finance' ),
        'profit-calculator'   => array( 'title' => 'Profit Calculator',   'icon' => '📊', 'cat' => 'finance' ),
        'qr-generator'        => array( 'title' => 'QR Code Generator',   'icon' => '📱', 'cat' => 'marketing' ),
        'meta-tag-generator'  => array( 'title' => 'Meta Tag Generator',  'icon' => '🏷️', 'cat' => 'website-seo' ),
        'invoice-generator'   => array( 'title' => 'Invoice Generator',   'icon' => '🧾', 'cat' => 'finance' ),
        'color-palette'       => array( 'title' => 'Color Palette Generator', 'icon' => '🎨', 'cat' => 'image-design' ),
        'image-compressor'    => array( 'title' => 'Image Compressor',    'icon' => '🗜️', 'cat' => 'image-design' ),
        'image-resizer'       => array( 'title' => 'Image Resizer',       'icon' => '📐', 'cat' => 'image-design' ),
        'image-converter'     => array( 'title' => 'Image Converter',     'icon' => '🔄', 'cat' => 'image-design' ),
        'background-remover'  => array( 'title' => 'Background Remover',  'icon' => '✂️', 'cat' => 'image-design' ),
        'utm-builder'         => array( 'title' => 'UTM Link Builder',    'icon' => '🔗', 'cat' => 'marketing' ),
    );

    foreach ( $default_tools as $type => $tool ) {
        // Check if a post with this tool type already exists
        $existing = get_posts( array(
            'post_type'      => 'bth_tool',
            'post_status'    => 'any',
            'meta_key'       => '_bth_tool_type',
            'meta_value'     => $type,
            'posts_per_page' => 1,
        ) );

        if ( ! empty( $existing ) ) {
            continue; // Skip if already exists
        }

        $post_id = wp_insert_post( array(
            'post_title'   => $tool['title'],
            'post_status'  => 'publish',
            'post_type'    => 'bth_tool',
        ) );

        if ( ! is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_bth_tool_type', $type );
            update_post_meta( $post_id, '_bth_tool_icon', $tool['icon'] );

            // Assign category
            if ( taxonomy_exists( 'bth_tool_category' ) ) {
                wp_set_object_terms( $post_id, $tool['cat'], 'bth_tool_category' );
            }
        }
    }
}
register_activation_hook( __FILE__, 'bth_activate_plugin' );

ALD_Business_Tools_Hub::get_instance();
