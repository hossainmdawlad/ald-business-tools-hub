<?php
/**
 * Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BTH_Shortcodes {

    public static function register() {
        add_shortcode( 'bth_tool', array( __CLASS__, 'render_tool' ) );
        add_shortcode( 'bth_tools_grid', array( __CLASS__, 'render_tools_grid' ) );
    }

    /**
     * Render a single tool by slug or ID.
     * Usage: [bth_tool id="currency-converter"] or [bth_tool slug="currency-converter"]
     */
    public static function render_tool( $atts ) {
        $atts = shortcode_atts( array(
            'id'   => '',
            'slug' => '',
        ), $atts, 'bth_tool' );

        $tool = null;

        if ( $atts['id'] ) {
            $tool = get_post( absint( $atts['id'] ) );
        } elseif ( $atts['slug'] ) {
            $tools = get_posts( array(
                'post_type'  => 'bth_tool',
                'name'       => sanitize_title( $atts['slug'] ),
                'numberposts' => 1,
            ) );
            if ( $tools ) {
                $tool = $tools[0];
            }
        }

        if ( ! $tool || $tool->post_type !== 'bth_tool' ) {
            return '<p class="bth-error">' . __( 'Tool not found.', 'ald-business-tools' ) . '</p>';
        }

        $tool_type = get_post_meta( $tool->ID, '_bth_tool_type', true );
        if ( ! $tool_type ) {
            $tool_type = 'custom';
        }

        ob_start();
        include BTH_PLUGIN_DIR . 'templates/single-tool.php';
        return ob_get_clean();
    }

    /**
     * Render a grid of tools.
     * Usage: [bth_tools_grid category="finance" count="6"]
     */
    public static function render_tools_grid( $atts ) {
        $atts = shortcode_atts( array(
            'category' => '',
            'count'    => 12,
            'columns'  => 3,
        ), $atts, 'bth_tools_grid' );

        $args = array(
            'post_type'      => 'bth_tool',
            'posts_per_page' => absint( $atts['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        );

        if ( $atts['category'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'bth_tool_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                ),
            );
        }

        $tools = new WP_Query( $args );

        if ( ! $tools->have_posts() ) {
            return '<p class="bth-error">' . __( 'No tools found.', 'ald-business-tools' ) . '</p>';
        }

        ob_start();
        include BTH_PLUGIN_DIR . 'templates/archive-tool.php';
        return ob_get_clean();
    }
}
