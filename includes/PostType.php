<?php
/**
 * ShortCode Generator: a "b-iframe" post type whose posts hold one iframe
 * block each, embeddable anywhere with [b-iframe id="123"].
 *
 * @package BIFRM
 */

namespace BIFRM;

if ( !defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists( __NAMESPACE__ . '\PostType' ) ) {
	class PostType {

		const POST_TYPE = 'b-iframe';

		function __construct() {
			add_action( 'init', [ $this, 'registerPostType' ] );
			add_action( 'admin_menu', [ $this, 'addSubmenu' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
			add_shortcode( 'b-iframe', [ $this, 'shortcodeHandler' ] );
			add_action( 'wp_ajax_bifrmSaveUninstallOption', [ $this, 'saveUninstallOption' ] );
			add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', [ $this, 'manageColumns' ] );
			add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', [ $this, 'manageCustomColumns' ], 10, 2 );
		}

		function registerPostType() {
			$menuIcon = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='#fff' stroke-width='2'><rect x='2' y='3' width='20' height='18' rx='1'/><path d='M2 8h20'/><path d='M8 13l-2.5 2.5L8 18'/><path d='M16 13l2.5 2.5L16 18'/></svg>";

			register_post_type( self::POST_TYPE, [
				'label'         => __( 'iFrame', 'b-iframe' ),
				'labels'        => [
					'name'           => __( 'iFrame', 'b-iframe' ),
					'singular_name'  => __( 'iFrame', 'b-iframe' ),
					'menu_name'      => __( 'iFrame', 'b-iframe' ),
					'all_items'      => __( 'ShortCode Generator', 'b-iframe' ),
					'add_new'        => __( 'Add New ShortCode', 'b-iframe' ),
					'add_new_item'   => __( 'Add New ShortCode', 'b-iframe' ),
					'edit_item'      => __( 'Edit Iframe', 'b-iframe' ),
					'not_found'      => __( 'No iframes yet — add one.', 'b-iframe' ),
					'item_published' => __( 'Published', 'b-iframe' ),
					'item_updated'   => __( 'Updated', 'b-iframe' ),
				],
				'public'        => false,
				'show_ui'       => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'data:image/svg+xml;base64,' . base64_encode( $menuIcon ),
				'template'      => [ [ 'bifrm/iframe' ] ],
				'template_lock' => 'all',
			] );
		}

		function manageColumns( $defaults ) {
			unset( $defaults['date'] );
			$defaults['shortcode'] = __( 'ShortCode', 'b-iframe' );
			$defaults['date']      = __( 'Date', 'b-iframe' );
			return $defaults;
		}

		function manageCustomColumns( $column_name, $post_ID ) {
			if ( 'shortcode' === $column_name ) {
				echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr( $post_ID ) . '">
					<input value="[b-iframe id=' . esc_attr( $post_ID ) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr( $post_ID ) . '\')" readonly>
					<span class="tooltip">' . esc_html__( 'Copy To Clipboard', 'b-iframe' ) . '</span>
				</div>';
			}
		}

		function shortcodeHandler( $atts ) {
			$post_id = isset( $atts['id'] ) ? absint( $atts['id'] ) : 0;
			$post    = $post_id ? get_post( $post_id ) : null;

			if ( !$post || self::POST_TYPE !== $post->post_type ) {
				return '';
			}
			if ( post_password_required( $post ) ) {
				return get_the_password_form( $post );
			}

			switch ( $post->post_status ) {
				case 'publish':
					return $this->displayContent( $post );
				case 'private':
					return current_user_can( 'read_private_posts' ) ? $this->displayContent( $post ) : '';
				case 'draft':
				case 'pending':
				case 'future':
					return current_user_can( 'edit_post', $post_id ) ? $this->displayContent( $post ) : '';
				default:
					return '';
			}
		}

		function displayContent( $post ) {
			$blocks = parse_blocks( $post->post_content );
			return isset( $blocks[0] ) ? render_block( $blocks[0] ) : '';
		}

		function addSubmenu() {
			add_submenu_page(
				'edit.php?post_type=' . self::POST_TYPE,
				__( 'Demo Page', 'b-iframe' ),
				__( 'Help & Demos', 'b-iframe' ),
				'manage_options',
				'bifrm_demo_page',
				[ $this, 'renderDemoPage' ]
			);
		}

		function adminEnqueueScripts() {
			$screen = get_current_screen();
			if ( isset( $screen->post_type ) && self::POST_TYPE === $screen->post_type ) {
				wp_enqueue_style( 'bifrm-admin-shortcode', BIFRM_DIR_URL . 'assets/admin-shortcode.css', [], BIFRM_VERSION );
				wp_enqueue_script( 'bifrm-admin-shortcode', BIFRM_DIR_URL . 'assets/admin-shortcode.js', [], BIFRM_VERSION, true );

				if ( 'post' === $screen->base ) {
					wp_enqueue_script( 'bifrm-editor-shortcode', BIFRM_DIR_URL . 'assets/editor-shortcode.js', [ 'wp-data', 'wp-dom-ready' ], BIFRM_VERSION, true );
				}
			}

			if ( isset( $screen->base ) && self::POST_TYPE . '_page_bifrm_demo_page' === $screen->base ) {
				$asset = file_exists( BIFRM_DIR_PATH . 'build/dashboard.asset.php' )
					? include BIFRM_DIR_PATH . 'build/dashboard.asset.php'
					: [ 'dependencies' => [ 'react', 'react-dom', 'wp-components', 'wp-i18n', 'wp-util' ], 'version' => BIFRM_VERSION ];

				wp_enqueue_style( 'bifrm-dashboard', BIFRM_DIR_URL . 'build/dashboard.css', [ 'wp-components' ], $asset['version'] );
				wp_enqueue_script( 'bifrm-dashboard', BIFRM_DIR_URL . 'build/dashboard.js', array_merge( $asset['dependencies'], [ 'wp-util' ] ), $asset['version'], true );
				wp_set_script_translations( 'bifrm-dashboard', 'b-iframe', BIFRM_DIR_PATH . 'languages' );
			}
		}

		// Persist the dashboard "delete data on uninstall" toggle.
		// Contract matches bpl-tools/Admin/Settings: reads $_POST['nonce'] and $_POST['enabled'].
		function saveUninstallOption() {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );

			if ( ! wp_verify_nonce( $nonce, 'bifrm_save_uninstall_option' ) ) {
				wp_send_json_error( [ 'message' => __( 'Invalid security token.', 'b-iframe' ) ], 403 );
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( [ 'message' => __( 'You do not have permission to perform this action.', 'b-iframe' ) ], 403 );
			}

			$raw_enabled = isset( $_POST['enabled'] ) ? sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) : '';
			$enabled     = ( 'true' === $raw_enabled || '1' === $raw_enabled );

			update_option( 'bifrmDeleteDataOnUninstall', $enabled );

			wp_send_json_success( [
				'enabled' => $enabled,
				'message' => $enabled
					? __( 'Data deletion enabled.', 'b-iframe' )
					: __( 'Data will be preserved on uninstall.', 'b-iframe' ),
			] );
		}

		function renderDemoPage() {
			?>
			<div
				id="bifrmDashboard"
				data-info="<?php
			echo esc_attr( wp_json_encode( [
				'version'               => BIFRM_VERSION,
				'adminUrl'              => admin_url(),
				'pluginUrl'             => BIFRM_DIR_URL,
				'deleteDataOnUninstall' => (bool) get_option( 'bifrmDeleteDataOnUninstall', false ),
				'uninstallNonce'        => wp_create_nonce( 'bifrm_save_uninstall_option' ),
			] ) );
			?>"
			>
			</div>
			<?php
		}
	}
	new PostType();
}
