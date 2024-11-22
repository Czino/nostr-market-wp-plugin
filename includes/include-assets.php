<?
function nostr_market() {
    // Source code: https://czino.github.io/nostr-nip-15-storefront-inject
    wp_register_style('nostr_market', plugins_url('../css/nostr-nip-15-storefront-inject.min.css',__FILE__));
    wp_enqueue_style('nostr_market');

    // Source code: https://czino.github.io/nostr-nip-15-storefront-inject
    wp_register_script(
        'nostr_market',
        plugins_url('../js/nostr-nip-15-storefront-inject.min.js', __FILE__),
        null, '1.0.0',
        array(
            'strategy' => 'defer',
    ));
    wp_enqueue_script('nostr_market');
}

add_action('wp_enqueue_scripts','nostr_market');