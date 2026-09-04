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
			add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', [ $this, 'manageColumns' ] );
			add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', [ $this, 'manageCustomColumns' ], 10, 2 );
		}

		function registerPostType() {
			$menuIcon = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='#fff' stroke-width='2'><rect x='2' y='3' width='20' height='18' rx='1'/><path d='M2 8h20'/><path d='M8 13l-2.5 2.5L8 18'/><path d='M16 13l2.5 2.5L16 18'/></svg>";

			register_post_type( self::POST_TYPE, [
				'label'         => __( 'B Iframe', 'b-iframe' ),
				'labels'        => [
					'name'           => __( 'B Iframe', 'b-iframe' ),
					'singular_name'  => __( 'B Iframe', 'b-iframe' ),
					'menu_name'      => __( 'B Iframe', 'b-iframe' ),
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
			}
		}

		function renderDemoPage() {
			$demo = do_shortcode( '[iframe src="https://www.youtube.com/watch?v=Hfm94aHAbYQ" ratio="16:9" title="B Iframe demo"]' );
			?>
			<div class="wrap bifrmHelp">
				<h1><?php esc_html_e( 'B Iframe — Help & Demos', 'b-iframe' ); ?> <span class="bifrmHelp__version">v<?php echo esc_html( BIFRM_VERSION ); ?></span></h1>
				<p class="bifrmHelp__lede"><?php esc_html_e( 'Three ways to embed anything: the iFrame block in the editor, the [iframe] shortcode anywhere shortcodes work, and reusable iframes via the ShortCode Generator.', 'b-iframe' ); ?></p>

				<div class="bifrmHelp__grid">
					<div class="bifrmHelp__main">
						<div class="bifrmHelp__card">
							<h2><?php esc_html_e( 'Live demo', 'b-iframe' ); ?></h2>
							<p class="description"><?php esc_html_e( 'This is the [iframe] shortcode rendering a YouTube page link — the URL is converted to the privacy-enhanced player automatically and keeps a 16:9 ratio at any width.', 'b-iframe' ); ?></p>
							<code class="bifrmHelp__code">[iframe src="https://www.youtube.com/watch?v=Hfm94aHAbYQ" ratio="16:9"]</code>
							<?php echo $demo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode output escaped in the handler. ?>
						</div>

						<div class="bifrmHelp__card">
							<h2><?php esc_html_e( 'The [iframe] shortcode', 'b-iframe' ); ?></h2>
							<table class="widefat striped">
								<thead><tr><th><?php esc_html_e( 'Attribute', 'b-iframe' ); ?></th><th><?php esc_html_e( 'Default', 'b-iframe' ); ?></th><th><?php esc_html_e( 'What it does', 'b-iframe' ); ?></th></tr></thead>
								<tbody>
									<tr><td><code>src</code></td><td>—</td><td><?php esc_html_e( 'Any URL. Page links from YouTube, Vimeo, Dailymotion, Google Maps, Spotify, Loom and Figma are converted to their embeddable form.', 'b-iframe' ); ?></td></tr>
									<tr><td><code>ratio</code></td><td><?php esc_html_e( '(empty)', 'b-iframe' ); ?></td><td><?php esc_html_e( 'Keep an aspect ratio, e.g. 16:9, 4:3, 1:1 — overrides height.', 'b-iframe' ); ?></td></tr>
									<tr><td><code>width</code> / <code>height</code></td><td>100% / 673px</td><td><?php esc_html_e( 'Fixed size when no ratio is set.', 'b-iframe' ); ?></td></tr>
									<tr><td><code>title</code></td><td><?php esc_html_e( '(empty)', 'b-iframe' ); ?></td><td><?php esc_html_e( 'Accessible title for the iframe.', 'b-iframe' ); ?></td></tr>
									<tr><td><code>loading</code></td><td>auto</td><td><?php esc_html_e( 'auto, lazy or eager.', 'b-iframe' ); ?></td></tr>
									<tr><td><code>fullscreen</code></td><td>true</td><td><?php esc_html_e( 'Allow fullscreen inside the frame.', 'b-iframe' ); ?></td></tr>
									<tr><td><code>border_width</code>, <code>border_style</code>, <code>border_color</code>, <code>border_radius</code></td><td>0px, solid, transparent, 0px</td><td><?php esc_html_e( 'Simple border styling.', 'b-iframe' ); ?></td></tr>
								</tbody>
							</table>
						</div>

						<div class="bifrmHelp__card">
							<h2><?php esc_html_e( 'Reusable iframes: [b-iframe id="…"]', 'b-iframe' ); ?></h2>
							<ol>
								<li><?php esc_html_e( 'Go to B Iframe → ShortCode Generator → Add New ShortCode.', 'b-iframe' ); ?></li>
								<li><?php esc_html_e( 'Configure the iframe with the full block editor (ratio, conversion, borders, shadows) and publish.', 'b-iframe' ); ?></li>
								<li><?php esc_html_e( 'Copy its shortcode from the list — for example', 'b-iframe' ); ?> <code>[b-iframe id=123]</code> — <?php esc_html_e( 'and paste it into any post, widget or page builder. Edit the iframe once, and it updates everywhere.', 'b-iframe' ); ?></li>
							</ol>
							<a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . self::POST_TYPE ) ); ?>"><?php esc_html_e( 'Add New ShortCode', 'b-iframe' ); ?></a>
						</div>
					</div>

					<div class="bifrmHelp__side">
						<div class="bifrmHelp__card">
							<h2><?php esc_html_e( 'The iFrame block', 'b-iframe' ); ?></h2>
							<p><?php esc_html_e( 'In the editor, add the "iFrame" block, paste a URL, then pick a sizing mode: Aspect ratio scales with the page, Fixed height stays put. The editor warns you when a site refuses to be embedded.', 'b-iframe' ); ?></p>
						</div>
						<div class="bifrmHelp__card">
							<h2><?php esc_html_e( 'Supported conversions', 'b-iframe' ); ?></h2>
							<ul class="bifrmHelp__list">
								<li>YouTube (watch / shorts / youtu.be) → <?php esc_html_e( 'privacy-enhanced player', 'b-iframe' ); ?></li>
								<li>Vimeo</li><li>Dailymotion</li><li>Google Maps</li><li>Spotify</li><li>Loom</li><li>Figma</li>
							</ul>
						</div>
						<div class="bifrmHelp__card">
							<h2><?php esc_html_e( 'Need help?', 'b-iframe' ); ?></h2>
							<p><a href="https://wordpress.org/support/plugin/b-iframe/" target="_blank" rel="noopener"><?php esc_html_e( 'Support forum', 'b-iframe' ); ?></a> · <a href="mailto:support@bplugins.com"><?php esc_html_e( 'Email support', 'b-iframe' ); ?></a> · <a href="https://bplugins.com" target="_blank" rel="noopener">bplugins.com</a></p>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	new PostType();
}
