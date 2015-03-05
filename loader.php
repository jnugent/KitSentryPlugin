<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: Kitsentry
 * Plugin URI: http://demitas.se/kitsentry
 * Description: Kitsentry social extension demo for BuddyPress
 * Version: 0.1
 * Author: Jason Nugent
 * Author URI: http://demitas.se
 * License: GPL2
 */

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function kitsentry_init() {
	require(dirname(__FILE__) . '/kitsentry-bp.php');
}
add_action('bp_include', 'kitsentry_init');

// Register Custom Post Type for KitSentry items.
function kitsentry_custom_post_type() {

	$labels = array(
			'name'                => _x( 'KitSentry Items', 'Post Type General Name', 'text_domain' ),
			'singular_name'       => _x( 'KitSentry Item', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'           => __( 'Kitsentry Item', 'text_domain' ),
			'parent_item_colon'   => __( 'Parent KitSentry Item:', 'text_domain' ),
			'all_items'           => __( 'All KitSentry Items', 'text_domain' ),
			'view_item'           => __( 'View KitSentry Item', 'text_domain' ),
			'add_new_item'        => __( 'Add New KitSentry Item', 'text_domain' ),
			'add_new'             => __( 'Add New', 'text_domain' ),
			'edit_item'           => __( 'Edit KitSentry Item', 'text_domain' ),
			'update_item'         => __( 'Update KitSentry Item', 'text_domain' ),
			'search_items'        => __( 'Search KitSentry Item', 'text_domain' ),
			'not_found'           => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
			'label'               => __( 'kitsentry_item', 'text_domain' ),
			'description'         => __( 'A photography item within KitSentry', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'author', 'comments',),
			'taxonomies'          => array( 'category', 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
	);
	register_post_type('kitsentry_item', $args);
}

// Hook into the 'init' action
add_action('init', 'kitsentry_custom_post_type', 0);

/**
 * Provide better status messages for KitSentry items.
 * @param array $messages
 * @return array
 */
function kitsentry_updated_messages($messages) {
	global $post, $post_ID;
	$messages['kitsentry_item'] = array(
			0 => '',
			1 => sprintf( __('KitSentry Item updated. <a href="%s">View item</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('KitSentry Item updated.'),
			5 => isset($_GET['revision']) ? sprintf( __('KitSentry Item restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('KitSentry Item published. <a href="%s">View item</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('KitSentry Item saved.'),
			8 => sprintf( __('KitSentry Item submitted. <a target="_blank" href="%s">Preview item</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('KitSentry Item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview item</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('KitSentry Item draft updated. <a target="_blank" href="%s">Preview item</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'kitsentry_updated_messages' );


// Register Custom Taxonomy
function kitsentry_custom_taxonomy() {

	$labels = array(
			'name'                       => _x( 'KitSentry Item Categories', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'KitSentry Item Category', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'KitSentry Item Categories', 'text_domain' ),
			'all_items'                  => __( 'All KitSentry Categories', 'text_domain' ),
			'parent_item'                => __( 'Parent KitSentry Category', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent v:', 'text_domain' ),
			'new_item_name'              => __( 'New KitSentry Item Category', 'text_domain' ),
			'add_new_item'               => __( 'Add New KitSentry Item Category', 'text_domain' ),
			'edit_item'                  => __( 'Edit KitSentry Item Category', 'text_domain' ),
			'update_item'                => __( 'Update KitSentry Item Category', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'search_items'               => __( 'Search KitSentry Item Categories', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
	);
	register_taxonomy('kitsentry_item_category', array('kitsentry_item_category'), $args);

}

// Hook into the 'init' action
add_action('init', 'kitsentry_custom_taxonomy', 0);

function kitsentry_content() {
	$the_query = new WP_Query(
				array('author_name' => bp_displayed_user_domain(),
					'post_type' => 'kitsentry_item')
			);

	// The standard Wordpress content loop
	if ($the_query->have_posts()) {
		echo '<ul>';
		while ($the_query->have_posts()) {
			$the_query->the_post();
			echo '<li>' . get_the_title();
			echo '<ul><li>' . get_the_content() . '</li></ul>';
			echo '</li>';
		}
		echo '</ul>';
	} else {
		// no posts found
	}
	wp_reset_postdata();
}

add_action('add_meta_boxes', 'kitsentry_rfid_box');

/**
 * The custom lambda function for an RFID metadata field.
 * Probably not user editable, but shows off the fact that WordPress
 * can have customized metadata fields.
 */
function kitsentry_rfid_box() {
	add_meta_box(
			'kitsentry_rfid_box',
			__( 'RFID Tag', 'kisentry_textdomain' ),
			'kitsentry_rfid_box_content',
			'kitsentry_item',
			'side',
			'high'
	);
}

/**
 * A helper function for RFID text field generation.
 * @param WPPost $post
 */
function kitsentry_rfid_box_content($post) {
	wp_nonce_field( plugin_basename(__FILE__), 'kitsentry_rfid_box_content_nonce');
	echo '<label for="rfid"></label>';
	$rfid = get_metadata('post', $post->ID, 'rfid', true);
	echo '<input type="text" id="rfid" name="rfid" value="' . esc_attr($rfid) . '" placeholder="Enter the RFID tag" />';
}

add_action('save_post', 'kitsentry_rfid_box_save');

/**
 * Saves the RFID value.
 * @param int $post_id
 */
function kitsentry_rfid_box_save($post_id) {

	if (defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (!wp_verify_nonce($_POST['kitsentry_rfid_box_content_nonce'], plugin_basename(__FILE__)))
		return;

	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return;
	} else {
		if ( !current_user_can('edit_post', $post_id))
			return;
	}
	$rfid = $_POST['rfid'];
	update_post_meta($post_id, 'rfid', $rfid);
}

/**
 * Add methods and filter calls to extend native XMLRPC
 * interface.
 * @param array|string $args
 */
function kitsentry_getItems($args) {
	global $wp_xmlrpc_server;

	$wp_xmlrpc_server->escape($args);
	$username = $args;

	$the_query = new WP_Query(
			array(
				'author_name' => $username,
				'post_type' => 'kitsentry_item',
			)
	);

	if ( $the_query->have_posts() ) {
		while ($the_query->have_posts()) {
			return json_encode($the_query->posts);
		}
	}
}

/**
 * Hook the XMLRPC interface and add new method.
 * @param array $methods
 */
function kitsentry_new_xmlrpc_methods($methods) {
	$methods['kitsentry.getItems'] = 'kitsentry_getItems';
	return $methods;
}

add_filter('xmlrpc_methods', 'kitsentry_new_xmlrpc_methods');
