<?php
/**
 * Removes plugin data on uninstall — only when the user enabled
 * "Delete data on uninstall" on the Help & Demos → Settings page.
 *
 * @package BIFRM
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( get_option( 'bifrmDeleteDataOnUninstall', false ) ) {
	// Reusable iframes from the ShortCode Generator.
	$bifrm_posts = get_posts( [
		'post_type'   => 'b-iframe',
		'numberposts' => -1,
		'post_status' => 'any',
		'fields'      => 'ids',
	] );
	foreach ( $bifrm_posts as $bifrm_post_id ) {
		wp_delete_post( $bifrm_post_id, true );
	}

	delete_option( 'bifrmDeleteDataOnUninstall' );
}
