<?php
/**
 * Plugin Name:       Medialog Alias
 * Description:       Alias /Link Content type for Wordpress
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Espen Moe-Nilssen
 * License:           GPL-2.0-or-later
 * Author URI:        https://medialog.no
 * Text Domain:       medialog-alias
 *
 * @package           medialog-alias
 *
 */




 // function medialog_link_admin_menu() {
 //     add_menu_page(
 //         'Lenker',
 //         'Lenker',
 //         'read',
 //         'alias-menu',
 //         '', // Callback, leave empty
 //         'dashicons-globe',
 //         1 // Position
 //     );
 // }
 //
 // add_action( 'admin_menu', 'medialog_link_admin_menu' );




/* Custom Post Type Start */
function create_posttype() {
register_post_type( 'alias',
// CPT Options
array(
  'supports' => array(
  'title', // post title
  'author', // post author
  'thumbnail', // featured images
  'revisions', // post revisions
  'post-formats', // post formats
  'excerpt' // read more Text
  ),
  //'show_in_menu' => 'edit.php?post_type=page',
  'taxonomies' => array('category', 'post_tag'), // support tags and category
  'menu_icon' => 'dashicons-admin-links',
  'menu_position' => 20,
  'labels' => array(
  'name' => __('link', 'medialog-alias'),
  'singular_name' => __('Link', 'medialog-alias'),
  'menu_name' => __('Link', 'medialog-alias'),
  'name_admin_bar' => __('Links', 'medialog-alias'),
  'add_new' => __('Add New Link', 'medialog-alias'),
  'add_new_item' => __('Add New Link', 'medialog-alias'),
  'new_item' => __('New Links', 'medialog-alias'),
  'edit_item' => __('Edit Link', 'medialog-alias'),
  'view_item' => __('View Link', 'medialog-alias'),
  'all_items' => __('All Links', 'medialog-alias'),
  'search_items' => __('Search for Links', 'medialog-alias'),
  'not_found' => __('No Links found.', 'medialog-alias'),
  ),
  'public' => true,
  'has_archive' => false,
  'rewrite' => array('slug' => 'alias'),
 )
);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );
/* Custom Post Type End */


function external_url_meta_box() {

    add_meta_box(
        'external_url',
        __( 'External URL', 'medialog-alias' ),
        'external_url_meta_box_callback',
        'alias'
    );
}

add_action( 'add_meta_boxes', 'external_url_meta_box' );

function external_url_meta_box_callback( $post ) {
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'external_url_nonce', 'external_url_nonce' );
    $value = get_post_meta( $post->ID, '_external_url', true );
    echo '<input type="url" style="width:100%" id="external_url" value="'  . esc_attr( $value ) . '" name="external_url"></input>';
}


function save_external_url_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['external_url_nonce'] ) ) {
        return;
    }
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['external_url_nonce'], 'external_url_nonce' ) ) {
        return;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
    else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */
    // Make sure that it is set.
    if ( ! isset( $_POST['external_url'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['external_url'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_external_url', $my_data );
}

add_action( 'save_post', 'save_external_url_meta_box_data' );



add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {
    if ( get_post_type() == 'alias' ) {
        if ( is_single() ) {
              $template_path = plugin_dir_path( __FILE__ ) . '/single-alias.php';
        }
    }
    return $template_path;
}



?>
