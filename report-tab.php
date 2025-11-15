<?php
/**
 * Plugin Name: WooCommerce Report Tab
 * Description: Adds a conditional "Report" tab for specific product categories with admin meta box.
 * Version: 1.0
 * Author: Your Name
 */

// Add "Report" tab only for a specific product category
add_filter( 'woocommerce_product_tabs', 'add_report_tab_for_specific_category' );
function add_report_tab_for_specific_category( $tabs ) {
    global $product;

    // Get the custom field content
    $report_content = get_post_meta( $product->get_id(), '_report_content', true );

    // Check if product belongs to a specific category (replace the slug below)
    if ( has_term( 'workshop', 'product_cat', $product->get_id() ) && ! empty( $report_content ) ) {
        
        // Add new "Report" tab with priority 15 (second tab)
        $tabs['report_tab'] = array(
            'title'    => __( 'report', 'woocommerce' ),
            'priority' => 15,
            'callback' => 'report_tab_content'
        );
    }
    
    return $tabs;
}

// Output the content of the "Report" tab
function report_tab_content() {
    global $post;

    $report_content = get_post_meta( $post->ID, '_report_content', true );

    if ( ! empty( $report_content ) ) {
        echo wpautop( wp_kses_post( $report_content ) );
    }
}

// Add meta box for report content in admin
add_action( 'add_meta_boxes', 'add_report_content_metabox' );
function add_report_content_metabox() {
    add_meta_box(
        'report_content_box',
        'Report Content (Product report)',
        'report_content_metabox_html',
        'product',
        'normal',
        'high'
    );
}

// Meta box editor
function report_content_metabox_html( $post ) {
    $value = get_post_meta( $post->ID, '_report_content', true );
    wp_editor( $value, 'report_content_editor', array(
        'textarea_name' => 'report_content',
        'media_buttons' => true,
        'textarea_rows' => 10,
        'teeny'         => false,
    ) );
}

// Save report content
add_action( 'save_post_product', 'save_report_content_meta' );
function save_report_content_meta( $post_id ) {
    if ( isset( $_POST['report_content'] ) ) {
        update_post_meta( $post_id, '_report_content', wp_kses_post( $_POST['report_content'] ) );
    }
}
