<?php
/**
 * Backward-compatible entry point for older Zincomed content references.
 * The current Woodmart loop controller owns the outer product markup.
 */

defined( 'ABSPATH' ) || exit;

wc_get_template_part( 'content', 'product' );
