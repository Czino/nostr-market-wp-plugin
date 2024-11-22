<?php

function is_whitelisted($url, $whitelisted_parts) {
    foreach ($whitelisted_parts as $part) {
        if (strpos($url, $part) !== false) {
            return true;
        }
    }
    return false;
}

function resize_image_callback($request) {
    $whitelisted_parts = [
        'backblazeb2.com/file/plebeian-market',
    ];
    $url = $request->get_param('url');
    $width = $request->get_param('sw');
    $height = $request->get_param('sh') ?? -1;

    header('Cache-Control: public, max-age=43200');

    if (empty($url) || empty($width)) {
        return new WP_Error('missing_params', 'Missing URL or size parameters', array('status' => 400));
    }
    if (!is_whitelisted($url, $whitelisted_parts)) {
        return new WP_Error('forbidden_url', 'Image URL not allowed', array('status' => 400));
    }

    $cache_key = 'resized_image_' . md5($url . $width . $height);
    $cached_image = wp_cache_get($cache_key, 'image');

    if ($cached_image) {
        header('Content-Type: ' . $cached_image['type']);
        header('X-Cache-Hit: 1');
        echo $cached_image['data'];
    }

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return new WP_Error('image_not_found', 'Image not found.', array('status' => 404));
    }

    $image_data = wp_remote_retrieve_body($response);
    $image_type = wp_remote_retrieve_header($response, 'content-type');

    if ($image_type === 'binary/octet-stream') {
        $path_info = pathinfo($url);
        $extension = strtolower($path_info['extension'] ?? '');

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image_type = 'image/jpeg';
                break;
            case 'png':
                $image_type = 'image/png';
                break;
            case 'gif':
                $image_type = 'image/gif';
                break;
            case 'webp':
                $image_type = 'image/webp';
                break;
            default:
                return new WP_Error('unsupported_format', 'Unsupported image format.', array('status' => 415));
        }
    }

    $image = imagecreatefromstring($image_data);
    if (!$image) {
        return new WP_Error('image_creation_error', 'Error creating image from data.', array('status' => 500));
    }

    $resized_image = imagescale($image, $width, $height);
    if (!$resized_image) {
        return new WP_Error('resize_error', 'Error resizing image.', array('status' => 500));
    }

    ob_start();
    if ($image_type === 'image/jpeg') {
        imagejpeg($resized_image);
    } elseif ($image_type === 'image/png') {
        imagepng($resized_image);
    } elseif ($image_type === 'image/gif') {
        imagegif($resized_image);
    } elseif ($image_type === 'image/webp') {
        imagewebp($resized_image);
    } else {
        return new WP_Error('unsupported_format', 'Unsupported image format. ' . $image_type, array('status' => 415));
    }

    $image_data_output = ob_get_contents();
    ob_end_clean();

    wp_cache_set($cache_key, ['data' => $image_data_output, 'type' => $image_type], 'image', 43200);
    imagedestroy($image);
    imagedestroy($resized_image);

    header('Content-Type: ' . $image_type);
    echo $image_data_output;
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/resize-image/', array(
        'methods' => 'GET',
        'callback' => 'resize_image_callback',
        'permission_callback' => '__return_true',
    ));
});
