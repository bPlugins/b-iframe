<?php
/**
 * Editor-only REST endpoint: can this URL be shown in an iframe?
 *
 * Checks X-Frame-Options / CSP frame-ancestors server-side so the editor can
 * warn before the user publishes a blank box.
 *
 * @package BIFRM
 */

namespace BIFRM;

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( __NAMESPACE__ . '\FrameCheck' ) ) {
	class FrameCheck {

		function __construct() {
			add_action( 'rest_api_init', [ $this, 'register' ] );
		}

		public function register() {
			register_rest_route( 'bifrm/v1', '/frame-check', [
				'methods'             => 'GET',
				'callback'            => [ $this, 'check' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => [
					'url' => [
						'required'          => true,
						'sanitize_callback' => 'esc_url_raw',
					],
				],
			] );
		}

		public function check( $request ) {
			$url = $request->get_param( 'url' );

			if ( ! preg_match( '#^https?://#i', $url ) ) {
				return [ 'embeddable' => false, 'reason' => 'invalid-url' ];
			}

			$cache_key = 'bifrm_fc_' . md5( $url );
			$cached    = get_transient( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}

			// Many sites (Google included) refuse non-browser clients before
			// sending any headers, so ask as a browser and read only 2 KB.
			$args = [
				'timeout'             => 6,
				'redirection'         => 3,
				'limit_response_size' => 2048,
				'user-agent'          => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0 Safari/537.36',
			];
			$response = wp_safe_remote_get( $url, $args );

			if ( is_wp_error( $response ) ) {
				$result = [ 'embeddable' => true, 'reason' => 'unreachable' ]; // Unknown — don't cry wolf.
			} else {
				$xfo = strtolower( (string) wp_remote_retrieve_header( $response, 'x-frame-options' ) );
				$csp = strtolower( (string) wp_remote_retrieve_header( $response, 'content-security-policy' ) );

				$blocked = ( false !== strpos( $xfo, 'deny' ) || false !== strpos( $xfo, 'sameorigin' ) );
				if ( ! $blocked && false !== strpos( $csp, 'frame-ancestors' ) ) {
					// frame-ancestors that isn't a wildcard blocks third-party embedding.
					$blocked = ! preg_match( '/frame-ancestors[^;]*\*/', $csp );
				}

				$result = [
					'embeddable' => ! $blocked,
					'reason'     => $blocked ? 'frame-blocked' : 'ok',
				];
			}

			set_transient( $cache_key, $result, 15 * MINUTE_IN_SECONDS );
			return $result;
		}
	}
	new FrameCheck();
}
