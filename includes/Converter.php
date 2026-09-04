<?php
/**
 * Rewrites well-known page URLs to their embeddable form.
 *
 * Mirrored in src/utils/convert.js — keep the two in sync.
 *
 * @package BIFRM
 */

namespace BIFRM;

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( __NAMESPACE__ . '\Converter' ) ) {
	class Converter {

		/**
		 * Providers whose embeds need extra iframe permissions.
		 *
		 * @param string $src Iframe src (after conversion).
		 * @return array{allow:string,referrerpolicy:string}
		 */
		public static function permissions( $src ) {
			$host = strtolower( (string) wp_parse_url( $src, PHP_URL_HOST ) );

			$media = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
			$map   = [
				'youtube.com'          => $media,
				'youtube-nocookie.com' => $media,
				'player.vimeo.com'     => 'autoplay; fullscreen; picture-in-picture; clipboard-write',
				'dailymotion.com'      => $media,
				'open.spotify.com'     => 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture',
				'loom.com'             => 'autoplay; fullscreen',
				'figma.com'            => 'fullscreen',
			];

			$allow = '';
			foreach ( $map as $needle => $value ) {
				if ( false !== strpos( $host, $needle ) ) {
					$allow = $value;
					break;
				}
			}

			return [
				'allow'          => $allow,
				'referrerpolicy' => 'strict-origin-when-cross-origin',
			];
		}

		/**
		 * Converts a page URL into its embeddable equivalent.
		 *
		 * Unrecognised URLs are returned unchanged.
		 *
		 * @param string $url Any URL.
		 * @return string
		 */
		public static function convert( $url ) {
			$url = trim( (string) $url );
			if ( '' === $url || ! preg_match( '#^https?://#i', $url ) ) {
				return $url;
			}

			$parts = wp_parse_url( $url );
			if ( empty( $parts['host'] ) ) {
				return $url;
			}

			$host = strtolower( preg_replace( '/^www\./', '', $parts['host'] ) );
			$path = isset( $parts['path'] ) ? $parts['path'] : '/';
			parse_str( isset( $parts['query'] ) ? $parts['query'] : '', $query );

			// YouTube: watch, shorts, live, youtu.be. Already-embed URLs pass through.
			if ( 'youtube.com' === $host || 'm.youtube.com' === $host || 'youtube-nocookie.com' === $host ) {
				if ( 0 === strpos( $path, '/embed/' ) ) {
					return $url;
				}
				$id = '';
				if ( '/watch' === $path && ! empty( $query['v'] ) ) {
					$id = $query['v'];
				} elseif ( preg_match( '#^/(shorts|live)/([\w-]{6,})#', $path, $m ) ) {
					$id = $m[2];
				}
				if ( $id ) {
					$suffix = ! empty( $query['t'] ) ? '?start=' . absint( $query['t'] ) : '';
					return 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $id ) . $suffix;
				}
				return $url;
			}
			if ( 'youtu.be' === $host && preg_match( '#^/([\w-]{6,})#', $path, $m ) ) {
				return 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $m[1] );
			}

			// Vimeo page -> player.
			if ( 'vimeo.com' === $host && preg_match( '#^/(\d+)(?:/([0-9a-f]+))?$#', $path, $m ) ) {
				return 'https://player.vimeo.com/video/' . $m[1] . ( isset( $m[2] ) ? '?h=' . $m[2] : '' );
			}

			// Dailymotion.
			if ( ( 'dailymotion.com' === $host ) && preg_match( '#^/video/([\w]+)#', $path, $m ) ) {
				return 'https://www.dailymotion.com/embed/video/' . $m[1];
			}
			if ( 'dai.ly' === $host && preg_match( '#^/([\w]+)#', $path, $m ) ) {
				return 'https://www.dailymotion.com/embed/video/' . $m[1];
			}

			// Spotify: open.spotify.com/{type}/{id} -> /embed/{type}/{id}.
			if ( 'open.spotify.com' === $host && preg_match( '#^/(track|album|playlist|episode|show|artist)/([\w]+)#', $path, $m ) ) {
				return 'https://open.spotify.com/embed/' . $m[1] . '/' . $m[2];
			}

			// Loom share -> embed.
			if ( 'loom.com' === $host && preg_match( '#^/share/([0-9a-f]+)#', $path, $m ) ) {
				return 'https://www.loom.com/embed/' . $m[1];
			}

			// Figma file/design/proto -> embed wrapper.
			if ( 'figma.com' === $host && preg_match( '#^/(file|design|proto|board)/#', $path ) ) {
				return 'https://www.figma.com/embed?embed_host=b-iframe&url=' . rawurlencode( $url );
			}

			// Google Maps: place/search links -> output=embed form. Real embed URLs pass through.
			if ( 'google.com' === $host && 0 === strpos( $path, '/maps' ) ) {
				if ( 0 === strpos( $path, '/maps/embed' ) ) {
					return $url;
				}
				return 'https://www.google.com/maps?q=' . rawurlencode( self::maps_query( $path, $query ) ) . '&output=embed';
			}

			return $url;
		}

		/**
		 * Best query for a Google Maps page URL.
		 *
		 * @param string $path  URL path.
		 * @param array  $query Parsed query args.
		 * @return string
		 */
		private static function maps_query( $path, $query ) {
			if ( preg_match( '#/maps/place/([^/]+)#', $path, $m ) ) {
				return rawurldecode( str_replace( '+', ' ', $m[1] ) );
			}
			if ( preg_match( '#/maps/@(-?[\d.]+),(-?[\d.]+)#', $path, $m ) ) {
				return $m[1] . ',' . $m[2];
			}
			if ( ! empty( $query['q'] ) ) {
				return $query['q'];
			}
			return '';
		}
	}
}
