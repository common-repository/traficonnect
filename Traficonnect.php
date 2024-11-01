<?php
/**
 * Plugin Name: Traficonnect
 * Description: Adds custom SEO meta fields including focus keywords to the default WordPress REST API response for posts.
 * Version: 1.1
 * Author: Traficode
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Hook into the REST API initialization
add_action('rest_api_init', 'traficonnect_register_seo_meta_fields');

function traficonnect_register_seo_meta_fields() {
    // Register SEO meta fields to the post endpoint
    register_rest_field('post', 'traficonnect_seo_meta', [
        'get_callback'    => 'traficonnect_get_seo_meta_fields',
        'update_callback' => 'traficonnect_update_seo_meta_fields',
        'schema'          => null, // Optional, can define structure for the field
    ]);
}

/**
 * Fetch SEO meta fields (Yoast, Rank Math) for the REST API only if the fields are available
 */
function traficonnect_get_seo_meta_fields($object, $field_name, $request) {
    $post_id = absint($object['id']); // Ensure post ID is an integer
    $seo_meta = [];

    // Check if Yoast SEO fields are available
    $yoast_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
    $yoast_description = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
    $yoast_focus_keyword = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
    if (!empty($yoast_title) || !empty($yoast_description) || !empty($yoast_focus_keyword)) {
        $yoast_meta = [
            'title'        => esc_html($yoast_title),        // Escape output
            'description'  => esc_html($yoast_description),  // Escape output
            'focus_keyword' => esc_html($yoast_focus_keyword) // Escape output
        ];
        $seo_meta['yoast'] = $yoast_meta;
    }

    // Check if Rank Math SEO fields are available
    $rank_math_title = get_post_meta($post_id, 'rank_math_title', true);
    $rank_math_description = get_post_meta($post_id, 'rank_math_description', true);
    $rank_math_focus_keyword = get_post_meta($post_id, 'rank_math_focus_keyword', true);
    if (!empty($rank_math_title) || !empty($rank_math_description) || !empty($rank_math_focus_keyword)) {
        $rank_math_meta = [
            'title'        => esc_html($rank_math_title),        // Escape output
            'description'  => esc_html($rank_math_description),  // Escape output
            'focus_keyword' => esc_html($rank_math_focus_keyword) // Escape output
        ];
        $seo_meta['rankmath'] = $rank_math_meta;
    }

    return $seo_meta;
}

/**
 * Update SEO meta fields (Yoast, Rank Math) via the REST API only if the fields are provided
 */
function traficonnect_update_seo_meta_fields($value, $object, $field_name) {
    if (!current_user_can('edit_post', $object->ID)) {
        return new WP_Error(
            'rest_forbidden',
            esc_html__('You are not allowed to modify SEO meta fields for this post.', 'traficonnect'),
            array('status' => 403)
        );
    }

    $post_id = absint($object->ID); // Ensure post ID is an integer

    // Validate input before updating Yoast SEO meta fields
    if (isset($value['yoast'])) {
        if (!empty($value['yoast']['title'])) {
            update_post_meta($post_id, '_yoast_wpseo_title', sanitize_text_field($value['yoast']['title']));
        }
        if (!empty($value['yoast']['description'])) {
            update_post_meta($post_id, '_yoast_wpseo_metadesc', sanitize_text_field($value['yoast']['description']));
        }
        if (!empty($value['yoast']['focus_keyword'])) {
            update_post_meta($post_id, '_yoast_wpseo_focuskw', sanitize_text_field($value['yoast']['focus_keyword']));
        }
    }

    // Validate input before updating Rank Math SEO meta fields
    if (isset($value['rankmath'])) {
        if (!empty($value['rankmath']['title'])) {
            update_post_meta($post_id, 'rank_math_title', sanitize_text_field($value['rankmath']['title']));
        }
        if (!empty($value['rankmath']['description'])) {
            update_post_meta($post_id, 'rank_math_description', sanitize_text_field($value['rankmath']['description']));
        }
        if (!empty($value['rankmath']['focus_keyword'])) {
            update_post_meta($post_id, 'rank_math_focus_keyword', sanitize_text_field($value['rankmath']['focus_keyword']));
        }
    }

    return true;
}
