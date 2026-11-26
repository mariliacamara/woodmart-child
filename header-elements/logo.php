<?php
/**
 * Header logo element.
 *
 * Cópia de woodmart/header-elements/logo.php (WoodMart 8.6.7). Única
 * diferença: na home, o logo do cabeçalho do telemóvel fica dentro do <h1>
 * da página, com o texto do H1 no alt da imagem (pedido de SEO, 25/11/2026).
 * O Google indexa a versão de telemóvel; o logo do computador fica igual,
 * para haver um só H1 na página. Ao atualizar o WoodMart, comparar com o
 * logo.php do tema pai.
 *
 * @package woodmart
 */

$zincomed_logo_h1 = function_exists( 'zincomed_logo_h1_text' ) ? zincomed_logo_h1_text( $id ) : '';

$logo_url            = WOODMART_IMAGES . '/wood-logo-dark.svg';
$has_sticky_logo     = ( isset( $params['sticky_image']['url'] ) && ! empty( $params['sticky_image']['url'] ) );
$width_height_needed = isset( $params['width_height'] ) && $params['width_height'];

if ( isset( $params['image']['url'] ) && $params['image']['url'] ) {
	$logo_url = $params['image']['url'];
}

$width        = isset( $params['width'] ) ? (int) $params['width'] : 150;
$sticky_width = isset( $params['sticky_width'] ) ? (int) $params['sticky_width'] : 150;
$logo_attrs   = array(
	'src'   => $logo_url,
	'alt'   => $zincomed_logo_h1 ? $zincomed_logo_h1 : get_bloginfo( 'name' ),
	'style' => 'max-width: ' . esc_attr( $width ) . 'px;',
);

if ( $width_height_needed ) {
	$logo_attrs['width']  = 369;
	$logo_attrs['height'] = 53;
}

if ( ! woodmart_get_opt( 'disable_wordpress_lazy_loading' ) ) {
	$logo_attrs['loading'] = 'lazy';
}

$logo = '<img ' . implode(
	' ',
	array_map(
		function ( $key, $value ) {
			return $key . '="' . esc_attr( $value ) . '"';
		},
		array_keys( $logo_attrs ),
		$logo_attrs
	)
) . ' />';

if ( isset( $params['image']['id'] ) && $params['image']['id'] && $width_height_needed ) {
	woodmart_lazy_loading_deinit( true );
	$logo_img_attrs = array( 'style' => 'max-width:' . $width . 'px;' );
	if ( $zincomed_logo_h1 ) {
		$logo_img_attrs['alt'] = $zincomed_logo_h1;
	}
	$logo = wp_get_attachment_image( $params['image']['id'], 'full', false, $logo_img_attrs );
	woodmart_lazy_loading_init();
}

$logo_classes = ' whb-' . $id;

if ( $has_sticky_logo ) {
	$logo_classes .= ' wd-switch-logo';
}

?>
<div class="site-logo<?php echo esc_attr( $logo_classes ); ?>">
	<?php if ( $zincomed_logo_h1 ) : ?>
	<h1 class="zincomed-logo-h1">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wd-logo wd-main-logo" rel="home">
		<?php echo $logo; // phpcs:ignore ?>
	</a>
	</h1>
	<?php else : ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wd-logo wd-main-logo" rel="home" aria-label="<?php esc_html_e( 'Site logo', 'woodmart' ); ?>">
		<?php echo $logo; // phpcs:ignore ?>
	</a>
	<?php endif; ?>
	<?php if ( $has_sticky_logo ) : ?>
		<?php
		woodmart_enqueue_inline_style( 'header-logo-opt-swap' );

		$logo_sticky = '<img src="' . esc_url( $params['sticky_image']['url'] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" style="max-width: ' . esc_attr( $sticky_width ) . 'px;" />';

		if ( isset( $params['sticky_image']['id'] ) && $params['sticky_image']['id'] && $width_height_needed ) {
			woodmart_lazy_loading_deinit( true );
			$logo_sticky = wp_get_attachment_image( $params['sticky_image']['id'], 'full', false, array( 'style' => 'max-width:' . $sticky_width . 'px;' ) );
			woodmart_lazy_loading_init();
		}
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wd-logo wd-sticky-logo" rel="home">
			<?php echo $logo_sticky; // phpcs:ignore ?>
		</a>
	<?php endif ?>
</div>
