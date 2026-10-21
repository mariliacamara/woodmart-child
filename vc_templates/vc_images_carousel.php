<?php
/**
 * Compatibility override for WPBakery's Images Carousel.
 *
 * Some WPBakery versions return the carousel speed with the `ms` suffix.
 * Woodmart 7.3.0 prints that attribute as a raw JavaScript number, producing
 * invalid output such as `autoplayTimeout: 5000ms`.
 */

if ( isset( $atts['speed'] ) ) {
	$atts['speed'] = absint( $atts['speed'] );
}

require get_template_directory() . '/vc_templates/vc_images_carousel.php';
