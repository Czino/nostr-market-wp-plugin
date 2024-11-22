<?php
/**
 * Plugin Name: Nostr Market Include
 * Description: Include any nostr market stall.
 */

if (! defined('ABSPATH') ) {
    exit;
}

require plugin_dir_path(__FILE__) .'includes/settings.php';
require plugin_dir_path(__FILE__) .'includes/include-assets.php';
require plugin_dir_path(__FILE__) .'includes/shortcode/product-list.php';
require plugin_dir_path(__FILE__) .'includes/shortcode/product-carousel.php';
require plugin_dir_path(__FILE__) .'includes/shortcode/product-detail.php';
