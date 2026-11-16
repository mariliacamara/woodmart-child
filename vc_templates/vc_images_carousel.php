<?php
/**
 * WPBakery "Images Carousel" rendered with Woodmart's own carousel.
 *
 * Woodmart 7.3 shipped an Owl Carousel template for this element. Woodmart
 * 8.x dropped both that template and Owl, and WPBakery's default template
 * depends on vc_carousel styles that Woodmart does not load, so the element
 * rendered as an empty box. The home page hero (desktop and mobile versions)
 * uses this element, so it is mapped here to Woodmart's Images gallery in
 * carousel view (Swiper), the same carousel the product sliders use.
 *
 * WPBakery passes the shortcode attributes in $atts.
 *
 * @package Zincomed_Child
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'woodmart_images_gallery_shortcode' ) ) {
	return;
}

$zc_atts = shortcode_atts(
	array(
		'images'                  => '',
		'img_size'                => 'full',
		'onclick'                 => 'link_image',
		'custom_links'            => '',
		'custom_links_target'     => '',
		'slides_per_view'         => '1',
		'wrap'                    => '',
		'autoplay'                => '',
		'hide_pagination_control' => '',
		'hide_prev_next_buttons'  => '',
		'speed'                   => '5000',
		'css_animation'           => 'none',
		'el_class'                => '',
		'css'                     => '',
	),
	isset( $atts ) ? (array) $atts : array()
);

$zc_on_click = 'none';
$zc_links    = array();

if ( 'custom_link' === $zc_atts['onclick'] ) {
	$zc_on_click = 'links';
	$zc_links    = array_map( 'trim', explode( ',', (string) $zc_atts['custom_links'] ) );
} elseif ( 'link_image' === $zc_atts['onclick'] ) {
	$zc_on_click = 'lightbox';
}

$zc_per_view  = max( 1, absint( $zc_atts['slides_per_view'] ) );
$zc_no_dots   = 'yes' === $zc_atts['hide_pagination_control'] ? 'yes' : 'no';
$zc_no_arrows = 'yes' === $zc_atts['hide_prev_next_buttons'] ? 'yes' : '';

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Woodmart escapes its own markup.
echo woodmart_images_gallery_shortcode(
	array(
		'images'                         => $zc_atts['images'],
		'img_size'                       => $zc_atts['img_size'],
		'view'                           => 'carousel',
		'on_click'                       => $zc_on_click,
		// Woodmart splits this comma-separated string itself.
		'custom_links'                   => implode( ',', $zc_links ),
		'target_blank'                   => '_blank' === $zc_atts['custom_links_target'],
		'slides_per_view'                => $zc_per_view,
		'slides_per_view_tablet'         => $zc_per_view,
		'slides_per_view_mobile'         => $zc_per_view,
		'spacing'                        => 0,
		'spacing_tablet'                 => 0,
		'spacing_mobile'                 => 0,
		'autoplay'                       => 'yes' === $zc_atts['autoplay'] ? 'yes' : 'no',
		'speed'                          => (string) max( 1000, absint( $zc_atts['speed'] ) ),
		'wrap'                           => 'yes' === $zc_atts['wrap'] ? 'yes' : '',
		'scroll_per_page'                => 'yes',
		'hide_pagination_control'        => $zc_no_dots,
		'hide_pagination_control_tablet' => $zc_no_dots,
		'hide_pagination_control_mobile' => $zc_no_dots,
		'hide_prev_next_buttons'         => $zc_no_arrows,
		'hide_prev_next_buttons_tablet'  => $zc_no_arrows,
		'hide_prev_next_buttons_mobile'  => $zc_no_arrows,
		'css_animation'                  => $zc_atts['css_animation'],
		'el_class'                       => trim( 'wpb_images_carousel zc-images-carousel ' . $zc_atts['el_class'] ),
		'css'                            => $zc_atts['css'],
	)
);
