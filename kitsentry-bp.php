<?php

/**
 * These functions are only loaded if BP is enabled, via the bp_include filter.
 * This prevents Wordpress from breaking if the BP plugin is ever disabled for
 * some reason.
 */

/**
 * Include a KitSentry menu item in the admin bar at the top of the screen,
 * displayed when Users are logged in.
 */
function kitsentry_bp_admin_bar_add() {
  global $wp_admin_bar, $bp;

  $user_domain = bp_loggedin_user_domain();
  $item_link = trailingslashit($user_domain . 'kitsentry');

  $title = __('  KitSentry', 'buddypress');

  $wp_admin_bar->add_menu(array(
		'parent'  => $bp->my_account_menu_id,
		'id'      => 'my-account-kitsentry',
		'title'   => $title,
		'href'    => trailingslashit($item_link)
	) );
}
add_action('bp_setup_admin_bar', 'kitsentry_bp_admin_bar_add', 300);

/**
 * Add a KitSentry tab to the user profile page.
 */
function kitsentry_profile_tab() {
	global $bp;

	bp_core_new_nav_item( array(
			'name' => 'KitSentry',
			'slug' => 'kitsentry',
			'screen_function' => 'kitsentry_screen',
			'position' => 40,
			'parent_url'	=> bp_displayed_user_domain()  . '/kitsentry/',
			'parent_slug'	=> $bp->profile->slug,
			'default_subnav_slug' => 'kitsentry'
	) );
}

/**
 * The template called for a single KitSentry item.
 */
function kitsentry_screen() {
	//add title and content here - last is to call the members plugin.php template
	add_action('bp_template_title', 'kitsentry_title');
	add_action('bp_template_content', 'kitsentry_content');
	bp_core_load_template('buddypress/members/single/plugins');
}

function kitsentry_title() {
	echo 'KitSentry';
}

add_action('bp_setup_nav', 'kitsentry_profile_tab');
?>
