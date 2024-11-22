<?php

function nostr_market_settings_init() {
    register_setting('nostr_market', 'nostr_market_options');

    add_settings_section(
        'nostr_market_section_options',
        __('Nostr market options', 'nostr-market-include'), 'nostr_market_section_options_callback',
        'nostr_market'
    );

    add_settings_field(
        'nostr_market_field_pubkey',
        __('Pubkey', 'nostr-market-include'),
        'nostr_market_field_pubkey_cb',
        'nostr_market',
        'nostr_market_section_options',
        array('label_for' => 'nostr_market_field_pubkey')
    );

    add_settings_field(
        'nostr_market_field_show_prices',
        __('Show prices', 'nostr-market-include'),
        'nostr_market_field_show_prices_cb',
        'nostr_market',
        'nostr_market_section_options',
        array('label_for' => 'nostr_market_field_show_prices')
    );

    add_settings_field(
        'nostr_market_field_product_url',
        __('Product URL', 'nostr-market-include'),
        'nostr_market_field_product_url_cb',
        'nostr_market',
        'nostr_market_section_options',
        array('label_for' => 'nostr_market_field_product_url')
    );

    add_settings_field(
        'nostr_market_field_relays',
        __('Relays', 'nostr-market-include'),
        'nostr_market_field_relays_cb',
        'nostr_market',
        'nostr_market_section_options',
        array('label_for' => 'nostr_market_field_relays')
    );

    add_settings_field(
        'nostr_market_field_resize_images',
        __('Resize images', 'nostr-market-include'),
        'nostr_market_field_resize_images_cb',
        'nostr_market',
        'nostr_market_section_options',
        array('label_for' => 'nostr_market_field_resize_images')
    );
}

add_action('admin_init', 'nostr_market_settings_init');


function nostr_market_section_options_callback() {
    ?>
    <p>This plugin currently provides you with 3 distinct shortcodes to include nostr market products in your WordPress site.</p>
    <ul>
        <li><code>[product_carousel]</code> - displays up to 8 (default) products in a carousel.</li>
        <li><code>[product_carousel limit="16"]</code> - displays a product carousel with defined limit of products.</li>
        <li><code>[product_list]</code> - lists all your products in a grid.</li>
        <li><code>[product_detail]</code> - Shows the details of the currently viewed product (based on set product URL)</li>
        <li><code>[product_detail id="A_SPECIFIC_PRODUCT_ID"]</code> - Shows the details of the your set product</li>
    </ul>

    <h2>Product Detail Page</h2>
    <ol>
        <li>Create a Page and include the short code <code>[product_detail]</code>.</li>
        <li>Note the url path of your product page</li>
        <li>Configure url under Product URL</li>
    </ol>
    <?php
}

function nostr_market_field_pubkey_cb( $args ) {
    $options = get_option('nostr_market_options');
    ?>
    <input type="text"
        style="width: 100%;"
        name="nostr_market_options[<?php echo esc_attr($args['label_for']) ?>]"
        placeholder="<?php esc_html_e('fd511d...', 'nostr-market-include' ) ?>"
        value="<?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : '' ?>" />
    <p>
        <?php esc_html_e('Your stall public key (not to be confused with npub)', 'nostr-market-include') ?>
    </p>
    <?php
}
function nostr_market_field_product_url_cb( $args ) {
    $options = get_option('nostr_market_options');
    ?>
    <input type="text"
        style="width: 100%;"
        name="nostr_market_options[<?php echo esc_attr($args['label_for']) ?>]"
        placeholder="<?php esc_html_e('path/to/$PRODUCTNAME/$PRODUCTID', 'nostr-market-include' ) ?>"
        value="<?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : '' ?>" />
    <p>
        <?php esc_html_e('A pattern for your product URL. Your can use $PRODUCTNAME as a placeholder for the product name and $PRODUCTID (required) as a placeholder for the product id.', 'nostr-market-include') ?>
    </p>
    <?php
}
function nostr_market_field_show_prices_cb( $args ) {
    $options = get_option('nostr_market_options');
    ?>
    <input type="checkbox"
        name="nostr_market_options[<?php echo esc_attr($args['label_for']) ?>]"
        <?php echo isset($options[$args['label_for']]) && $options[$args['label_for']] === 'on' ? 'checked="checked"' : '' ?> />
    <?php
}
function nostr_market_field_resize_images_cb( $args ) {
    $options = get_option('nostr_market_options');
    ?>
    <input type="checkbox"
        name="nostr_market_options[<?php echo esc_attr($args['label_for']) ?>]"
        <?php echo isset($options[$args['label_for']]) && $options[$args['label_for']] === 'on' ? 'checked="checked"' : '' ?> />
        <p>Because images configured on nostr products are often static, this setting allows to resize them on demand, which is useful for displaying smaller images on product thumbnails so users do not have to request the full resolution image.</p>
        <p>Currently only supports images uploaded through Plebeian Market.</p>
        <p>If you enable this, make sure your server can handle to resizing images on demand.</p>
    <?php
}
function nostr_market_field_relays_cb( $args ) {
    $options = get_option('nostr_market_options');
    ?>
    <textarea
        style="width: 100%;"
        rows="4"
        name="nostr_market_options[<?php echo esc_attr($args['label_for']) ?>]"
        placeholder="<?php esc_html_e('wss://nostr-pub.wellorder.net', 'nostr-market-include' ) ?>"><?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : '' ?></textarea>
    <p>
        <?php esc_html_e('Your custom relay list. Put each relay on a new line', 'nostr-market-include') ?>
    </p>
    <?php
}

function nostr_market_settings() {
    add_menu_page(
        'Nostr market',
        'Nostr market',
        'manage_options',
        'nostr_market',
        'nostr_market_settings_html'
    );
}
add_action('admin_menu', 'nostr_market_settings');


function nostr_market_settings_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('nostr_market_messages', 'nostr_market_message', __('Settings Saved', 'nostr-market-include'), 'updated');
    }

    settings_errors('nostr_market_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
                settings_fields('nostr_market');
                do_settings_sections('nostr_market');
                submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}