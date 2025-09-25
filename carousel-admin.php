<?php
/**
 * Carousel Slides Administration System
 * 
 * @package Ciabay Divi
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Carousel Slides Custom Post Type
 */
function ciabay_register_carousel_slides() {
    $labels = array(
        'name'                  => 'Slides del Carousel',
        'singular_name'         => 'Slide del Carousel',
        'menu_name'             => 'Carousel Slides',
        'add_new'               => 'Agregar Slide',
        'add_new_item'          => 'Agregar Nuevo Slide',
        'edit_item'             => 'Editar Slide',
        'new_item'              => 'Nuevo Slide',
        'view_item'             => 'Ver Slide',
        'search_items'          => 'Buscar Slides',
        'not_found'             => 'No se encontraron slides',
        'not_found_in_trash'    => 'No se encontraron slides en la papelera',
    );

    $args = array(
        'labels'                => $labels,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-images-alt2',
        'supports'              => array('title', 'thumbnail'),
        'has_archive'           => false,
        'rewrite'               => false,
        'capability_type'       => 'post',
    );

    register_post_type('carousel_slide', $args);
}
add_action('init', 'ciabay_register_carousel_slides');

/**
 * Add meta boxes for carousel slide fields
 */
function ciabay_add_carousel_meta_boxes() {
    add_meta_box(
        'carousel_slide_details',
        'Detalles del Slide',
        'ciabay_carousel_slide_meta_box_callback',
        'carousel_slide',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ciabay_add_carousel_meta_boxes');

/**
 * Meta box callback function
 */
function ciabay_carousel_slide_meta_box_callback($post) {
    wp_nonce_field('ciabay_save_carousel_slide', 'ciabay_carousel_slide_nonce');
    
    $subtitle = get_post_meta($post->ID, '_carousel_subtitle', true);
    $button1_text = get_post_meta($post->ID, '_carousel_button1_text', true);
    $button1_url = get_post_meta($post->ID, '_carousel_button1_url', true);
    $button2_text = get_post_meta($post->ID, '_carousel_button2_text', true);
    $button2_url = get_post_meta($post->ID, '_carousel_button2_url', true);
    $slide_order = get_post_meta($post->ID, '_carousel_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="carousel_subtitle">Subtítulo</label></th>
            <td><input type="text" id="carousel_subtitle" name="carousel_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="carousel_button1_text">Texto Botón 1</label></th>
            <td><input type="text" id="carousel_button1_text" name="carousel_button1_text" value="<?php echo esc_attr($button1_text); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="carousel_button1_url">URL Botón 1</label></th>
            <td><input type="url" id="carousel_button1_url" name="carousel_button1_url" value="<?php echo esc_attr($button1_url); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="carousel_button2_text">Texto Botón 2</label></th>
            <td><input type="text" id="carousel_button2_text" name="carousel_button2_text" value="<?php echo esc_attr($button2_text); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="carousel_button2_url">URL Botón 2</label></th>
            <td><input type="url" id="carousel_button2_url" name="carousel_button2_url" value="<?php echo esc_attr($button2_url); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="carousel_order">Orden del Slide</label></th>
            <td><input type="number" id="carousel_order" name="carousel_order" value="<?php echo esc_attr($slide_order ?: 0); ?>" min="0" /></td>
        </tr>
    </table>
    <?php
}

/**
 * Save carousel slide meta data
 */
function ciabay_save_carousel_slide($post_id) {
    if (!isset($_POST['ciabay_carousel_slide_nonce']) || !wp_verify_nonce($_POST['ciabay_carousel_slide_nonce'], 'ciabay_save_carousel_slide')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('carousel_subtitle', 'carousel_button1_text', 'carousel_button1_url', 'carousel_button2_text', 'carousel_button2_url', 'carousel_order');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'ciabay_save_carousel_slide');

/**
 * Get carousel slides data
 */
function ciabay_get_carousel_slides() {
    $slides = get_posts(array(
        'post_type' => 'carousel_slide',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => '_carousel_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    ));
    
    $slides_data = array();
    foreach ($slides as $slide) {
        $slides_data[] = array(
            'id' => $slide->ID,
            'title' => $slide->post_title,
            'subtitle' => get_post_meta($slide->ID, '_carousel_subtitle', true),
            'image' => get_the_post_thumbnail_url($slide->ID, 'full'),
            'button1_text' => get_post_meta($slide->ID, '_carousel_button1_text', true),
            'button1_url' => get_post_meta($slide->ID, '_carousel_button1_url', true),
            'button2_text' => get_post_meta($slide->ID, '_carousel_button2_text', true),
            'button2_url' => get_post_meta($slide->ID, '_carousel_button2_url', true),
            'order' => get_post_meta($slide->ID, '_carousel_order', true)
        );
    }
    
    return $slides_data;
}
