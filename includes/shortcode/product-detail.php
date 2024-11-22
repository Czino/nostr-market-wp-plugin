<?php 
add_shortcode("product_detail",function($args = array()) {
    $options = get_option('nostr_market_options');
    $productId = isset($args['id']) ? $args['id'] : '';
    $show_price = isset($options['nostr_market_field_show_prices']) ? 'true' : 'false';
    $relays = isset($options['nostr_market_field_relays']) ? $options['nostr_market_field_relays'] : 'wss://nostr-pub.wellorder.net';
    $resize_images = isset($options['nostr_market_field_resize_images']);
    return '<div
        class="nostr-product-detail"
        data-id="' . $productId . '"
        data-show-price="' . $show_price. '"
        data-relays="' . $relays. '"
        ' . ($resize_images ? 'data-image-proxy="/wp-json/api/v1/resize-image?url=$URL&sw=$WIDTH"' : '') . '
    ></div>';
});
