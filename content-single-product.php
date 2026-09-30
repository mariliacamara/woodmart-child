<?php
defined( 'ABSPATH' ) || exit;
global $product;
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product-content', $product ); ?>>

  <!-- Breadcrumb -->
  <div class="wd-breadcrumbs-wrap container">
    <?php if ( function_exists( 'woodmart_current_breadcrumbs' ) ) : ?>
    <div class="wd-breadcrumbs">
      <?php woodmart_current_breadcrumbs( 'shop' ); ?>
    </div>
    <?php else : ?>
    <?php woocommerce_breadcrumb(); ?>
    <?php endif; ?>
  </div>

  <div class="wd-product-top product-image-summary-wrap container wd-grid-g"
    style="--wd-col-lg: 12; --wd-gap-lg: 30px; --wd-gap-sm: 20px;">

    <div class="row wd-product-main product-image-summary product-image-summary-inner wd-grid-g wd-grid-col"
      style="--wd-col-lg: 12; --wd-gap-lg: 30px; --wd-gap-sm: 20px;">

      <!-- LEFT: Gallery -->
      <div class="col-lg-6 wd-product-gallery-col product-images wd-grid-col"
        style="--wd-col-lg: 6; --wd-col-md: 6; --wd-col-sm: 12;">
        <div class="wd-product-gallery">
          <?php do_action( 'woocommerce_before_single_product_summary' ); ?>

          <?php if ( ! $product->is_in_stock() ) : ?>
          <div class="oos-svg-badge" aria-hidden="true">
            <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/icons/outofstock.svg' ); ?>" alt="">
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- RIGHT: Summary -->
      <div class="col-lg-6 wd-product-summary-col summary entry-summary wd-grid-col"
        style="--wd-col-lg: 6; --wd-col-md: 6; --wd-col-sm: 12;">
        <div class="wd-summary-inner summary-inner">

          <?php
            // BRAND NAME acima do título + logo ao lado (robusto)
            global $product;

            $attr = woodmart_get_opt( 'brands_attribute' );
            $brand_term = null;
            $brand_logo_url = null;

            // 1) Pegar o termo configurado como brand (atributo)
            if ( $attr ) {
                $terms = wc_get_product_terms(
                    $product->get_id(),
                    $attr,
                    array( 'fields' => 'all' )
                );

                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $brand_term = $terms[0];

                    // 2) Tentar várias meta keys onde a imagem pode estar
                    $image_meta = get_term_meta( $brand_term->term_id, 'image', true );       // woodmart sometimes use 'image' array
                    $image_id_meta = get_term_meta( $brand_term->term_id, 'image_id', true ); // some versions
                    $thumb_id_meta = get_term_meta( $brand_term->term_id, 'thumbnail_id', true ); // WP standard

                    // Normalize into a URL
                    if ( $image_meta ) {
                        // sometimes it's array with ['id'] or it's already a URL
                        if ( is_array( $image_meta ) && ! empty( $image_meta['id'] ) ) {
                            $brand_logo_url = wp_get_attachment_image_url( intval( $image_meta['id'] ), 'full' );
                        } elseif ( is_numeric( $image_meta ) ) {
                            $brand_logo_url = wp_get_attachment_image_url( intval( $image_meta ), 'full' );
                        } else {
                            // assume it's a URL string
                            $brand_logo_url = esc_url_raw( $image_meta );
                        }
                    }

                    if ( ! $brand_logo_url && $image_id_meta ) {
                        if ( is_numeric( $image_id_meta ) ) {
                            $brand_logo_url = wp_get_attachment_image_url( intval( $image_id_meta ), 'full' );
                        }
                    }

                    if ( ! $brand_logo_url && $thumb_id_meta ) {
                        if ( is_numeric( $thumb_id_meta ) ) {
                            $brand_logo_url = wp_get_attachment_image_url( intval( $thumb_id_meta ), 'full' );
                        }
                    }

                    // final fallback: sometimes theme stores directly term meta 'image' as attachment array
                    // already covered above
                }
            }

            // 3) Fallback para meta antiga _brand_name (só nome)
            if ( ! $brand_term ) {
                $fallback_name = get_post_meta( get_the_ID(), '_brand_name', true );
                if ( $fallback_name ) {
                    $brand_term = (object) array( 'name' => $fallback_name );
                }
            }
            ?>

          <?php if ( $brand_term ) : ?>
          <div class="my-brand-wrapper">

            <div class="my-title-row">
              <div>
                <div class="my-brand-name">
                  <?php echo esc_html( $brand_term->name ); ?>
                </div>
                <h1 class="product-title"><?php the_title(); ?></h1>
              </div>

              <?php
              $brand_link = '';

              if ( $brand_term && isset( $brand_term->slug ) ) {
                  $brand_link = home_url( '/loja/?filter_marca=' . $brand_term->slug );
              }
              ?>
              <?php if ( $brand_logo_url ) : ?>
              <div class="my-brand-logo">
                <?php if ( $brand_link ) : ?>
                <a href="<?php echo esc_url( $brand_link ); ?>"
                  aria-label="<?php echo esc_attr( $brand_term->name ); ?>">
                  <img src="<?php echo esc_url( $brand_logo_url ); ?>"
                    alt="<?php echo esc_attr( $brand_term->name ); ?>">
                </a>
                <?php else : ?>
                <img src="<?php echo esc_url( $brand_logo_url ); ?>" alt="<?php echo esc_attr( $brand_term->name ); ?>">
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php else : ?>
          <h1 class="product-title"><?php the_title(); ?></h1>
          <?php endif; ?>


          <!-- Excerpt -->
          <div class="product-excerpt">
            <?php the_excerpt(); ?>
          </div>

          <!-- PRICE -->
          <div class="wd-price-pill">
            <?php woocommerce_template_single_price(); ?>
          </div>

          <!-- ⭐ RATING (FORÇADO) -->
          <div class="my-rating-force">
            <?php
            $avg   = (float) $product->get_average_rating();
            $count = (int) $product->get_rating_count();

            echo '<div class="my-rating-inner">';
            echo wp_kses_post( wc_get_rating_html( $avg ) );
            if ( $count > 0 ) {
                echo '<span class="count">(' . esc_html( $count ) . ')</span>';
            }
            echo '</div>';
            ?>
          </div>

          <!-- Shipping -->
          <div class="wd-delivery">
            <?php
            if ( function_exists( 'woodmart_get_shipping_text' ) ) {
              echo wp_kses_post( woodmart_get_shipping_text() );
            } else {
              echo '<span>' . esc_html__( 'Entrega entre 24h a 48h para Portugal continental.', 'zincomed-child' ) . '</span>';
            }
            ?>
          </div>

          <!-- ADD TO CART -->
          <div class="wd-add-to-cart-wrap<?php
            // Esconde quantity se só há 1 unidade disponível
            $stock_qty = $product->get_stock_quantity();
            $manages_stock = $product->managing_stock();
            $max_qty = $manages_stock ? intval( $stock_qty ) : null;
            if ( $max_qty !== null && $max_qty <= 1 ) {
              echo ' hide-quantity';
            }
          ?>">
            <?php woocommerce_template_single_add_to_cart(); ?>

            <?php
            $stock_qty = $product->get_stock_quantity();
            $manages_stock = $product->managing_stock();

            if ( $manages_stock && $stock_qty !== null && $stock_qty <= 3 && $stock_qty > 0 ) : ?>
              <div class="low-stock-warning">
                <?php echo esc_html__( 'Baixo stock', 'zincomed-child' ); ?>
              </div>
            <?php endif; ?>
          </div>

          <!-- Variations placeholder -->
          <div class="wd-variations-boxes"></div>

          <!-- META (SKU + Categoria) -->
          <div class="wd-product-meta">
            <?php
            $sku = $product->get_sku();

            if ( $sku ) {
                echo '<div class="product-sku"><strong>' . esc_html__( 'REF:', 'zincomed-child' ) . '</strong> ' . esc_html( $sku ) . '</div>';
            }

            // categorias do produto
            $categories = wc_get_product_category_list( $product->get_id(), ', ' );

            if ( $categories ) {
                echo '<div class="product-category"><strong>' . esc_html__( 'Categoria:', 'zincomed-child' ) . '</strong> ' . wp_kses_post( $categories ) . '</div>';
            }
            ?>
          </div>


          <!-- Informação adicional -->
        </div>
      </div>

    </div> <!-- .row -->
  </div> <!-- .wd-product-top -->

  <section class="product-tabs-wrapper">
    <div class="container product-tabs-inner">
      <?php woocommerce_output_product_data_tabs(); ?>
    </div>
  </section>

  <section class="related-products wd-related-products container">
    <h2 class="wd-related-title"><?php echo esc_html__( 'PRODUTOS RELACIONADOS', 'zincomed-child' ); ?></h2>

    <?php
    $limit       = 5;
    $related_ids = array_merge( $product->get_cross_sell_ids(), $product->get_upsell_ids() );

    if ( count( $related_ids ) < $limit ) {
      $related_ids = array_merge(
        $related_ids,
        wc_get_related_products( $product->get_id(), $limit - count( $related_ids ), $related_ids )
      );
    }

    $related_ids = array_slice( array_unique( array_map( 'absint', $related_ids ) ), 0, $limit );

    if ( $related_ids && function_exists( 'woodmart_shortcode_products' ) ) {
      echo woodmart_shortcode_products( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        array(
          'element_title'          => '',
          'layout'                 => 'carousel',
          'post_type'              => 'ids',
          'include'                => implode( ',', $related_ids ),
          'orderby'                => 'post__in',
          'items_per_page'         => $limit,
          'slides_per_view'        => 5,
          'slides_per_view_tablet' => 3,
          'slides_per_view_mobile' => 2,
          'columns'                => 5,
          'columns_tablet'         => 3,
          'columns_mobile'         => 2,
          'img_size'               => 'woocommerce_thumbnail',
          'product_quantity'       => 'enable',
          'spacing'                => 10,
          'wrapper_classes'        => ' zincomed-related-products',
          'query_post_type'        => array( 'product', 'product_variation' ),
        )
      );
    }
    ?>
  </section>


</div>
