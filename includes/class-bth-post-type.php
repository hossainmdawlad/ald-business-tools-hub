<?php
/**
 * Tool Post Type & Taxonomy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BTH_Post_Type {

    public static function register() {
        // Custom Post Type: Tool
        register_post_type( 'bth_tool', array(
            'labels' => array(
                'name'               => __( 'Tools', 'ald-business-tools' ),
                'singular_name'      => __( 'Tool', 'ald-business-tools' ),
                'add_new'            => __( 'Add New Tool', 'ald-business-tools' ),
                'add_new_item'       => __( 'Add New Tool', 'ald-business-tools' ),
                'edit_item'          => __( 'Edit Tool', 'ald-business-tools' ),
                'new_item'           => __( 'New Tool', 'ald-business-tools' ),
                'view_item'          => __( 'View Tool', 'ald-business-tools' ),
                'search_items'       => __( 'Search Tools', 'ald-business-tools' ),
                'not_found'          => __( 'No tools found', 'ald-business-tools' ),
                'not_found_in_trash' => __( 'No tools found in trash', 'ald-business-tools' ),
                'all_items'          => __( 'All Tools', 'ald-business-tools' ),
                'menu_name'          => __( 'Tools Hub', 'ald-business-tools' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => array( 'slug' => 'tools' ),
            'menu_icon'    => 'dashicons-hammer',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'show_in_rest' => true,
            'taxonomies'   => array( 'bth_tool_category' ),
        ) );

        // Taxonomy: Tool Category
        register_taxonomy( 'bth_tool_category', 'bth_tool', array(
            'labels' => array(
                'name'              => __( 'Tool Categories', 'ald-business-tools' ),
                'singular_name'     => __( 'Category', 'ald-business-tools' ),
                'search_items'      => __( 'Search Categories', 'ald-business-tools' ),
                'all_items'         => __( 'All Categories', 'ald-business-tools' ),
                'edit_item'         => __( 'Edit Category', 'ald-business-tools' ),
                'update_item'       => __( 'Update Category', 'ald-business-tools' ),
                'add_new_item'      => __( 'Add New Category', 'ald-business-tools' ),
                'new_item_name'     => __( 'New Category Name', 'ald-business-tools' ),
                'menu_name'         => __( 'Categories', 'ald-business-tools' ),
            ),
            'hierarchical'      => true,
            'show_in_rest'      => true,
            'rewrite'           => array( 'slug' => 'tool-category' ),
        ) );

        // Default categories
        if ( ! term_exists( 'finance', 'bth_tool_category' ) ) {
            wp_insert_term( __( 'Finance & Pricing', 'ald-business-tools' ), 'bth_tool_category', array( 'slug' => 'finance' ) );
        }
        if ( ! term_exists( 'marketing', 'bth_tool_category' ) ) {
            wp_insert_term( __( 'Marketing & Social', 'ald-business-tools' ), 'bth_tool_category', array( 'slug' => 'marketing' ) );
        }
        if ( ! term_exists( 'website-seo', 'bth_tool_category' ) ) {
            wp_insert_term( __( 'Website & SEO', 'ald-business-tools' ), 'bth_tool_category', array( 'slug' => 'website-seo' ) );
        }
        if ( ! term_exists( 'image-design', 'bth_tool_category' ) ) {
            wp_insert_term( __( 'Image & Design', 'ald-business-tools' ), 'bth_tool_category', array( 'slug' => 'image-design' ) );
        }
        if ( ! term_exists( 'productivity', 'bth_tool_category' ) ) {
            wp_insert_term( __( 'Productivity', 'ald-business-tools' ), 'bth_tool_category', array( 'slug' => 'productivity' ) );
        }
        if ( ! term_exists( 'business-planning', 'bth_tool_category' ) ) {
            wp_insert_term( __( 'Business Planning', 'ald-business-tools' ), 'bth_tool_category', array( 'slug' => 'business-planning' ) );
        }
    }
}
