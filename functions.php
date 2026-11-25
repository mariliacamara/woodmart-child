<?php
/**
 * Zincomed child theme compatibility layer for Woodmart 8.5.x.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ZINCOMED_CHILD_VERSION' ) ) {
  define( 'ZINCOMED_CHILD_VERSION', wp_get_theme()->get( 'Version' ) ?: '2.0.13' );
}

/**
 * Version for a child theme asset: the theme version plus the file's
 * modification time, so every deploy gives the CSS and JS a new URL and
 * Cloudflare or the browser never keep serving the previous copy.
 *
 * @param string $relative_path Path inside the child theme, starting with "/".
 * @return string
 */
function zincomed_child_asset_version( $relative_path ) {
  $file = get_stylesheet_directory() . $relative_path;

  return file_exists( $file ) ? ZINCOMED_CHILD_VERSION . '.' . filemtime( $file ) : ZINCOMED_CHILD_VERSION;
}

add_action( 'after_setup_theme', function () {
  load_child_theme_textdomain( 'zincomed-child', get_stylesheet_directory() . '/languages' );
} );

add_action( 'wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'zincomed-child',
    get_stylesheet_uri(),
    array( 'woodmart-style' ),
    zincomed_child_asset_version( '/style.css' )
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
		zincomed_child_asset_version( '/js/categories-fix.js' ),
		true
	);

	wp_enqueue_script(
		'zincomed-platform-classes',
		get_stylesheet_directory_uri() . '/assets/js/platform-classes.js',
		array(),
		zincomed_child_asset_version( '/assets/js/platform-classes.js' ),
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
    zincomed_child_asset_version( '/assets/js/autoupdate-cart.js' ),
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
      zincomed_child_asset_version( '/assets/js/loop-qty.js' ),
      true
    );
  }

  // Script para botões +/- APENAS na página de produto único
  if ( is_product() ) {
    wp_enqueue_script(
      'woodmart-child-single-product-qty',
      get_stylesheet_directory_uri() . '/assets/js/single-product-qty.js',
      array(),
      zincomed_child_asset_version( '/assets/js/single-product-qty.js' ),
      true
    );

    wp_enqueue_script(
      'zincomed-product-variation-boxes',
      get_stylesheet_directory_uri() . '/assets/js/product-variation-boxes.js',
      array(),
      zincomed_child_asset_version( '/assets/js/product-variation-boxes.js' ),
      true
    );
  }
}, 20 );

add_action( 'wp_enqueue_scripts', function () {

	wp_enqueue_script(
		'product-cards',
		get_stylesheet_directory_uri() . '/assets/js/product-cards.js',
		array(),
		zincomed_child_asset_version( '/assets/js/product-cards.js' ),
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

/**
 * Build related-product purchase links from the product's own permalink.
 * WooCommerce otherwise carries the current page's query parameters into
 * these URLs (for example, ?gQT=1&add-to-cart=123).
 */
add_filter( 'woocommerce_product_add_to_cart_url', function ( $url, $product ) {
	if (
		! is_singular( 'product' ) ||
		! ( $product instanceof WC_Product ) ||
		! $product->is_type( 'simple' ) ||
		! $product->is_purchasable() ||
		! $product->is_in_stock() ||
		get_queried_object_id() === $product->get_id()
	) {
		return $url;
	}

	return add_query_arg( 'add-to-cart', $product->get_id(), get_permalink( $product->get_id() ) );
}, 20, 2 );

/**
 * Woodmart 8 mudou alguns textos e a tradução pt_PT (feita no Loco Translate)
 * ainda tem os antigos, por isso estes apareciam em inglês: a mensagem do
 * popup "adicionado ao carrinho" e alguns rótulos de acessibilidade.
 */
add_filter( 'gettext_woodmart', function ( $translation, $text ) {
	static $map = array(
		'Product was successfully added to your cart.' => 'O seu produto foi adicionado ao carrinho com sucesso.',
		'Clear search'                                  => 'Limpar pesquisa',
		'Clear search button'                           => 'Botão para limpar a pesquisa',
		'Next slide'                                    => 'Diapositivo seguinte',
		'Previous slide'                                => 'Diapositivo anterior',
		'Shopping cart sidebar'                         => 'Carrinho de compras lateral',
		'Toggle wishlist'                               => 'Adicionar ou remover dos favoritos',
		'Wishlist products'                             => 'Produtos favoritos',
	);

	if ( $translation === $text && isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}

	return $translation;
}, 10, 2 );
