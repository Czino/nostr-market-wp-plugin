<?php 
add_shortcode("product_list",function() {
    $options = get_option('nostr_market_options');
    $pubkey = isset($options['nostr_market_field_pubkey']) ? $options['nostr_market_field_pubkey'] : '';
    $product_url = isset($options['nostr_market_field_product_url']) ? $options['nostr_market_field_product_url'] : '';
    $show_price = isset($options['nostr_market_field_show_prices']) ? 'true' : 'false';
    $relays = isset($options['nostr_market_field_relays']) ? $options['nostr_market_field_relays'] : 'wss://nostr-pub.wellorder.net';
    $resize_images = isset($options['nostr_market_field_resize_images']);
    return '<div
        class="nostr-product-list"
        data-pubkey="' . $pubkey. '"
        ' . ($product_url ? 'data-product-url="' . $product_url . '"' : '') . '
        data-show-price="' . $show_price. '"
        data-relays="' . $relays. '"
        ' . ($resize_images ? 'data-image-proxy="/wp-json/api/v1/resize-image?url=$URL&sw=$WIDTH"' : '') . '
    ></div>';
});
