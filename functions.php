<?php
/**
 * Zincomed child theme compatibility layer for Woodmart 8.5.x.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ZINCOMED_CHILD_VERSION' ) ) {
  define( 'ZINCOMED_CHILD_VERSION', wp_get_theme()->get( 'Version' ) ?: '2.0.13' );
}

add_action( 'after_setup_theme', function () {
  load_child_theme_textdomain( 'zincomed-child', get_stylesheet_directory() . '/languages' );
} );

add_action( 'wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'zincomed-child',
    get_stylesheet_uri(),
    array( 'woodmart-style' ),
    ZINCOMED_CHILD_VERSION
  );
}, 10002 );

/* Unregistered Widgets */
if ( ! function_exists( 'zincomed_child_unregister_widget' ) ) {
	add_action( 'widgets_init', 'zincomed_child_unregister_widget' );

	function zincomed_child_unregister_widget() {
		unregister_widget( '0000000000001db50000000000000000' );
	}
}

// Keep the WPBakery carousel speed numeric without overriding a removed
// Woodmart template file.
add_filter( 'shortcode_atts_vc_images_carousel', function( $out ) {
  if ( isset( $out['speed'] ) ) {
    $out['speed'] = absint( $out['speed'] );
  }

  return $out;
}, 10, 1 );

/**
 * Keep Woodmart's native 8.5.x product loop controller and replace only the
 * inner visual card. This preserves its grid, slider, wishlist and AJAX state.
 */
add_filter( 'wc_get_template_part', function( $template, $slug, $name ) {
  $product_card_templates = array(
    'product-alt',
    'product-base',
    'product-button',
    'product-buttons-on-hover',
    'product-custom',
    'product-fw-button',
    'product-icons',
    'product-info',
    'product-info-alt',
    'product-list',
    'product-quick',
    'product-standard',
    'product-tiled',
  );

  if ( 'content' !== $slug || ! in_array( $name, $product_card_templates, true ) ) {
    return $template;
  }

  $custom = get_stylesheet_directory() . '/woocommerce/content-product-zincomed.php';

  return file_exists( $custom ) ? $custom : $template;
}, 20, 3 );

add_filter( 'woocommerce_post_class', function( $classes, $product ) {
  if ( $product instanceof WC_Product && ! $product->is_in_stock() ) {
    $classes[] = 'is-outofstock';
  }

  return $classes;
}, 10, 2 );

function zincomed_child_scripts() {
	wp_enqueue_script(
		'zincomed-cat-toggle',
		get_stylesheet_directory_uri() . '/js/categories-fix.js',
		array( 'jquery' ),
		ZINCOMED_CHILD_VERSION,
		true
	);

	wp_enqueue_script(
		'zincomed-platform-classes',
		get_stylesheet_directory_uri() . '/assets/js/platform-classes.js',
		array(),
		ZINCOMED_CHILD_VERSION,
		false
	);
}
add_action( 'wp_enqueue_scripts', 'zincomed_child_scripts', 9999 );

// Esconde o campo de quantidade quando só há 1 unidade disponível
add_filter( 'woocommerce_quantity_input_args', function( $args, $product ) {
  if ( $product && $product->managing_stock() && $product->get_stock_quantity() <= 1 ) {
    $args['min_value'] = 1;
    $args['max_value'] = 1;
    $args['input_value'] = 1;
  }
  return $args;
}, 10, 2 );

add_filter( 'woocommerce_is_sold_individually', function( $sold_individually, $product ) {
  if ( $product && $product->managing_stock() && $product->get_stock_quantity() <= 1 ) {
    return true;
  }
  return $sold_individually;
}, 10, 2 );

add_filter( 'woocommerce_product_add_to_cart_text', function () {
  return __( 'COMPRAR', 'zincomed-child' );
} );

add_filter( 'woocommerce_product_single_add_to_cart_text', function () {
  return __( 'COMPRAR', 'zincomed-child' );
} );

add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
  if ( $product && $product->is_type( 'variable' ) ) {
    return __( 'Ver opções', 'zincomed-child' );
  }
  return $text;
}, 20, 2 );

add_action( 'wp_enqueue_scripts', function () {
  wp_enqueue_script(
    'woodmart-autoupdate-cart',
    get_stylesheet_directory_uri() . '/assets/js/autoupdate-cart.js',
    array(), 
    ZINCOMED_CHILD_VERSION,
    true
  );
}, 20 );

add_filter( 'woocommerce_my_account_my_orders_actions', function ( $actions, $order ) {

	if ( isset( $actions['pay'] ) ) {
		$actions['pay']['name'] = __( 'Mudar forma de pagamento', 'woocommerce' );
	}

	return $actions;

}, 10, 2 );

add_action( 'wp_enqueue_scripts', function () {
  if ( is_admin() ) {
    return;
  }

  // Script para loop de produtos (não carrega no carrinho/checkout)
  if ( ! is_cart() && ! is_checkout() ) {
    wp_enqueue_script(
      'woodmart-child-loop-qty',
      get_stylesheet_directory_uri() . '/assets/js/loop-qty.js',
      array( 'jquery' ),
      ZINCOMED_CHILD_VERSION,
      true
    );
  }

  // Script para botões +/- APENAS na página de produto único
  if ( is_product() ) {
    wp_enqueue_script(
      'woodmart-child-single-product-qty',
      get_stylesheet_directory_uri() . '/assets/js/single-product-qty.js',
      array(),
      ZINCOMED_CHILD_VERSION,
      true
    );

    wp_enqueue_script(
      'zincomed-product-variation-boxes',
      get_stylesheet_directory_uri() . '/assets/js/product-variation-boxes.js',
      array(),
      ZINCOMED_CHILD_VERSION,
      true
    );
  }
}, 20 );

add_action( 'wp_enqueue_scripts', function () {

	wp_enqueue_script(
		'product-cards',
		get_stylesheet_directory_uri() . '/assets/js/product-cards.js',
		array(),
		ZINCOMED_CHILD_VERSION,
		true
	);

}, 20 );

add_filter( 'woocommerce_package_rates', 'zincomed_child_hide_transportadora_when_free', 100, 2 );

function zincomed_child_hide_transportadora_when_free( $rates, $package ) {
    $has_free = false;

    // Detecta frete grátis (price = 0)
    foreach ( $rates as $rate ) {
        if ( (float) $rate->cost === 0.0 ) {
            $has_free = true;
            break;
        }
    }

    if ( $has_free ) {
        foreach ( $rates as $rate_id => $rate ) {
            if ( false !== stripos( $rate->label, 'transportadora' ) ) {
                unset( $rates[ $rate_id ] );
            }
        }
    }

    return $rates;
}

add_filter( 'body_class', function ( $classes ) {
  $request_uri = isset( $_SERVER['REQUEST_URI'] )
    ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) )
    : '';

  if ( false !== strpos( $request_uri, 'wishlist' ) ) {
    $classes[] = 'is-wishlist-page';
  }

  return $classes;
} );
