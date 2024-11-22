<?php
/**
 * Plugin Name: Nostr Market Include
 * Description: Include any nostr market stall.
 */

if (! defined('ABSPATH') ) {
    exit;
}

require plugin_dir_path(__FILE__) .'includes/settings.php';
require plugin_dir_path(__FILE__) .'includes/include_assets.php';
require plugin_dir_path(__FILE__) .'includes/shortcode/product_list.php';
require plugin_dir_path(__FILE__) .'includes/shortcode/product_carousel.php';
require plugin_dir_path(__FILE__) .'includes/shortcode/product_detail.php';
require plugin_dir_path(__FILE__) .'includes/controllers/image_resizer.php';
