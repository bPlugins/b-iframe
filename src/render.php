<?php
/**
 * Server-side render: the iframe is real HTML, visible to crawlers and
 * no-JS visitors. view.js only adds the advanced style + fullscreen button.
 *
 * @package BIFRM
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$attributes = $attributes ?? []; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Provided by WordPress core to block render templates.
$bifrm_id   = wp_unique_id( 'bIframe-' );

$bifrm_src = \BIFRM\Converter::convert( $attributes['src'] ?? '' );
$bifrm_ttl = $attributes['title'] ?? '';
$bifrm_ld  = in_array( $attributes['loading'] ?? 'auto', [ 'lazy', 'eager' ], true ) ? $attributes['loading'] : '';

$bifrm_sizing = ( 'ratio' === ( $attributes['sizing'] ?? 'fixed' ) ) ? 'ratio' : 'fixed';
$bifrm_ratio  = str_replace( ':', '/', (string) ( $attributes['ratio'] ?? '16/9' ) );
if ( ! preg_match( '#^\d{1,3}(\.\d+)?/\d{1,3}(\.\d+)?$#', $bifrm_ratio ) ) {
	$bifrm_ratio = '16/9';
}

$bifrm_align = $attributes['layout']['alignment'] ?? 'center';
$bifrm_dim   = $attributes['advanced']['dimension'] ?? [];
$bifrm_w     = $bifrm_dim['width']['width']['desktop'] ?? '100%';
$bifrm_h     = $bifrm_dim['height']['height']['desktop'] ?? '673px';

$bifrm_perm = \BIFRM\Converter::permissions( $bifrm_src );
$bifrm_full = ! isset( $attributes['elements']['fullscreen'] ) || ! empty( $attributes['elements']['fullscreen'] );

// Keep the converted src in the payload so view.js and the markup agree.
$bifrm_payload        = $attributes;
$bifrm_payload['src'] = $bifrm_src;

$bifrm_css = "#{$bifrm_id}{text-align:" . ( in_array( $bifrm_align, [ 'left', 'center', 'right' ], true ) ? $bifrm_align : 'center' ) . ';}';
if ( 'ratio' === $bifrm_sizing ) {
	$bifrm_css .= "#{$bifrm_id}>div.bIframe{width:" . $bifrm_w . ";max-width:100%;aspect-ratio:{$bifrm_ratio} !important;height:auto !important;}";
} else {
	$bifrm_css .= "#{$bifrm_id}>div.bIframe{width:" . $bifrm_w . ';height:' . $bifrm_h . ';max-width:100%;}';
}
?>
<div
	<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() is properly escaped ?>
	<?php echo get_block_wrapper_attributes(); ?>
	id='<?php echo esc_attr( $bifrm_id ); ?>'
	data-attributes='<?php echo esc_attr( wp_json_encode( $bifrm_payload ) ); ?>'
>
	<?php if ( $bifrm_src ) : ?>
	<div class='bIframe'>
		<iframe
			src='<?php echo esc_url( $bifrm_src ); ?>'
			title='<?php echo esc_attr( $bifrm_ttl ); ?>'
			width='100%'
			height='100%'
			<?php if ( $bifrm_ld ) : ?>loading='<?php echo esc_attr( $bifrm_ld ); ?>'<?php endif; ?>
			<?php if ( $bifrm_perm['allow'] ) : ?>allow='<?php echo esc_attr( $bifrm_perm['allow'] ); ?>'<?php endif; ?>
			referrerpolicy='<?php echo esc_attr( $bifrm_perm['referrerpolicy'] ); ?>'
			<?php echo $bifrm_full ? 'allowfullscreen' : ''; ?>
		></iframe>
	</div>
	<?php endif; ?>
	<style><?php echo wp_strip_all_tags( $bifrm_css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS built above from validated values. ?></style>
</div>
