<?php
namespace BIFRM;

if ( !defined( 'ABSPATH' ) ) { exit; }

if( !class_exists( __NAMESPACE__ . '\ShortCode' ) ){
	class ShortCode{
		function __construct(){
			add_shortcode( 'iframe', [$this, 'shortcode'] );
		}
		function shortcode( $atts ) {
			extract( shortcode_atts( [
				'src'				=> '',
				'title'				=> '',
				'width'				=> '100%',
				'height'			=> '673px',
				'ratio'				=> '',
				'loading'			=> 'auto',
				'fullscreen'		=> 'true',
				'border_width'		=> '0px',
				'border_style'		=> 'solid',
				'border_color'		=> '#0000',
				'border_radius'		=> '0px'
			], $atts ) );

			$src = Converter::convert( $src );

			// ratio="16:9" (or 16/9) beats a fixed height.
			$ratio = str_replace( ':', '/', (string) $ratio );
			if ( $ratio && ! preg_match( '#^\d{1,3}(\.\d+)?/\d{1,3}(\.\d+)?$#', $ratio ) ) {
				$ratio = '';
			}
			$size_css = $ratio
				? sprintf( 'width: %s; max-width: 100%%; aspect-ratio: %s;', esc_attr( $width ), esc_attr( $ratio ) )
				: sprintf( 'width: %s; height: %s; max-width: 100%%;', esc_attr( $width ), esc_attr( $height ) );

			$perm = Converter::permissions( $src );
			$full = ! in_array( strtolower( (string) $fullscreen ), [ 'false', '0', 'no', 'off' ], true );

			ob_start();
			if ( !empty( $src ) ){ ?>
				<div class='bIframe' style='<?php echo $size_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?> border: <?php echo esc_attr( $border_width ); ?> <?php echo esc_attr( $border_style ); ?> <?php echo esc_attr( $border_color ); ?>; border-radius: <?php echo esc_attr( $border_radius ); ?>; overflow: hidden;'>
					<iframe
						src='<?php echo esc_url( $src ); ?>'
						title='<?php echo esc_attr( $title ); ?>'
						width='100%'
						height='100%'
						<?php if ( in_array( $loading, [ 'lazy', 'eager' ], true ) ) : ?>loading='<?php echo esc_attr( $loading ); ?>'<?php endif; ?>
						<?php if ( $perm['allow'] ) : ?>allow='<?php echo esc_attr( $perm['allow'] ); ?>'<?php endif; ?>
						referrerpolicy='<?php echo esc_attr( $perm['referrerpolicy'] ); ?>'
						<?php echo $full ? 'allowfullscreen' : ''; ?>
					></iframe>
				</div>
			<?php }
			return ob_get_clean();
		}
	}
	new ShortCode;
}
