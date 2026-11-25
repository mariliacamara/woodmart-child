# Woodmart Child (Zincomed)

Child theme of [WoodMart](https://woodmart.xtemos.com/) for the Zincomed WooCommerce store. It holds the store's template overrides, styles and small WooCommerce tweaks, so the parent theme can stay untouched.

- **Parent theme:** `woodmart` (required)
- **Current version:** 1.0.3
- **Requires:** WordPress, WooCommerce, WPBakery (used by WoodMart for the home page)

## Repository layout

The repository root is the theme folder (`wp-content/themes/woodmart-child/`).

| Path | Purpose |
| --- | --- |
| `style.css` | Theme header and all custom CSS (cards, quick shop, cart, account, responsive fixes) |
| `functions.php` | Enqueues styles and scripts, WooCommerce hooks and filters |
| `content-product-related.php` | Product card used for related products |
| `content-single-product.php` | Single product page template |
| `home.php` | "Blog Grid" page template |
| `woocommerce/content-product*.php` | Product card variants (standard, alt, icons, info, quick, fw-button) |
| `woocommerce/single-product/related.php` | Related products carousel |
| `woocommerce/order/`, `woocommerce/emails/`, `woocommerce/parts/` | Order details, email and product list overrides |
| `vc_templates/vc_images_carousel.php` | WPBakery image carousel override |
| `js/categories-fix.js` | Category accordion behavior |
| `assets/js/` | Cart auto-update and loop quantity scripts |
| `assets/icons/` | SVG icons for the account menu and product cards |
| `gtranslate/flags/` | Language flag icons |

## What the theme customizes

- **Product cards:** consistent layout across home, category, search, wishlist and related products (brand, title, rating, price, quantity and add-to-cart).
- **Brand logo:** read from the brand attribute term (term meta `image`, `image_id` or `thumbnail_id`), with a fallback to the `_brand_name` product meta.
- **Single product page:** custom summary order, rating always visible, custom delivery text.
- **Cart and account:** cart auto-update, "buy again" actions, custom account menu icons.
- **Shipping:** hides the carrier ("transportadora") rate when a free shipping rate is available.
- **Carousels:** override for the WoodMart/WPBakery image carousel autoplay timing.

## Installation

1. Install and activate the parent theme (WoodMart).
2. Upload this folder to `wp-content/themes/woodmart-child/`, or zip it and use **Appearance > Themes > Add New**.
3. Activate **Woodmart Child**.
4. Purge the page cache (LiteSpeed Cache) after every deploy.

## Development notes

- Do not edit the parent theme. Copy the file into this theme and change it here.
- After updating WoodMart, compare the overridden templates with the new parent versions. Overrides can drift and break cards, quick shop, the single product page or carousels.
- Run `php -l <file>` on any changed PHP file before deploying.
- The templates read a few term meta formats for brand images. Keep any new code type-safe: check `is_array()`, `is_numeric()` or `is_string()` before passing values to functions such as `esc_url_raw()`.
- Write commit messages and code comments in English.

## Changelog

### Unreleased

- Fix 500 error on related products when the brand term's `image` meta is an array without an `id` key (`esc_url_raw()` received an array).

### 1.0.3 (2026-10-06)

- Delivery text on the product page changed to "Entrega em 1 a 5 dias úteis para Portugal Continental."

### 1.0.2 (2026-09-24)

- Buy links in related products now use the clean product URL, without inheriting query parameters from the current page.
