<?php
/**
 * Ciabay Divi Child Theme Functions
 * 
 * @package Ciabay Divi
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function ciabay_divi_enqueue_styles() {
    // Get parent theme version
    $parent_style = 'divi-style';
    
    // Enqueue parent theme stylesheet
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    
    // Enqueue child theme stylesheet
    wp_enqueue_style('ciabay-divi-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'ciabay_divi_enqueue_styles');

/**
 * Add custom functions for Ciabay Divi child theme below this line
 */

/**
 * Enqueue custom scripts and styles for shortcodes
 */
function ciabay_enqueue_custom_assets() {
    wp_enqueue_script('ciabay-carousel', get_stylesheet_directory_uri() . '/js/carousel.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('ciabay-custom', get_stylesheet_directory_uri() . '/css/custom.css', array(), '2.0.0', true);
}
add_action('wp_enqueue_scripts', 'ciabay_enqueue_custom_assets');

/**
 * Theme setup
 */
function ciabay_theme_setup() {
    // Register navigation menu
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'ciabay-divi'),
    ));
    
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
}
add_action('after_setup_theme', 'ciabay_theme_setup');

// Include carousel administration system
require_once get_stylesheet_directory() . '/carousel-admin.php';

/**
 * Hero Carousel Shortcode with 3D Effect
 */
function ciabay_hero_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'height' => '500px',
        'autoplay' => 'true'
    ), $atts);
    
    $slides_data = ciabay_get_carousel_slides();
    
    // Fallback slides if no custom slides exist
    if (empty($slides_data)) {
        $slides_data = array(
            array(
                'id' => 1,
                'title' => 'SERVICIOS',
                'subtitle' => 'Servicios especializados para el campo',
                'image' => get_stylesheet_directory_uri() . '/assets/images/ejemplo.jpg',
                'button1_text' => 'VER SERVICIOS',
                'button1_url' => '#',
                'button2_text' => 'CONTACTAR',
                'button2_url' => '#'
            ),
            array(
                'id' => 2,
                'title' => 'INSUMOS',
                'subtitle' => 'Insumos de calidad para tu producción',
                'image' => get_stylesheet_directory_uri() . '/assets/images/ejemplo.jpg',
                'button1_text' => 'VER PRODUCTOS',
                'button1_url' => '#',
                'button2_text' => 'VER POR CULTIVO',
                'button2_url' => '#'
            ),
            array(
                'id' => 3,
                'title' => 'MÁQUINAS',
                'subtitle' => 'Maquinaria agrícola de última tecnología',
                'image' => get_stylesheet_directory_uri() . '/assets/images/ejemplo.jpg',
                'button1_text' => 'VER MÁQUINAS',
                'button1_url' => '#',
                'button2_text' => 'FINANCIACIÓN',
                'button2_url' => '#'
            )
        );
    }
    
    ob_start();
    ?>
    <div class="ciabay-hero-carousel" data-height="<?php echo esc_attr($atts['height']); ?>" data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>">
        <div class="carousel-container">
            <div class="carousel-track">
                <?php foreach ($slides_data as $index => $slide): ?>
                <div class="carousel-slide <?php echo $index === 1 ? 'active main-slide' : ''; ?>" data-slide="<?php echo esc_attr($slide['id']); ?>">
                    <div class="slide-content">
                        <div class="slide-image">
                            <img src="<?php echo esc_url($slide['image'] ?: get_stylesheet_directory_uri() . '/assets/images/ejemplo.jpg'); ?>" alt="<?php echo esc_attr($slide['title']); ?>">
                        </div>
                        <div class="slide-overlay">
                            <h3><?php echo esc_html($slide['title']); ?></h3>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Navigation -->
        <div class="carousel-navigation">
            <button class="nav-btn prev-btn">&lt;</button>
            <button class="nav-btn next-btn">&gt;</button>
        </div>
        
        <!-- Dots -->
        <div class="carousel-dots">
            <?php foreach ($slides_data as $index => $slide): ?>
            <span class="dot <?php echo $index === 1 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Pass slides data to JavaScript -->
    <script type="text/javascript">
        window.ciabayCarouselData = <?php echo json_encode($slides_data); ?>;
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('ciabay_hero_carousel', 'ciabay_hero_carousel_shortcode');

/**
 * Simple Content Carousel Shortcode (linked to 3D carousel)
 */
function ciabay_content_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'background_color' => '#1e3a8a'
    ), $atts);
    
    $slides_data = ciabay_get_carousel_slides();
    
    // Fallback slides if no custom slides exist
    if (empty($slides_data)) {
        $slides_data = array(
            array(
                'title' => 'SERVICIOS',
                'subtitle' => 'Servicios especializados para el campo',
                'button1_text' => 'VER SERVICIOS',
                'button1_url' => '#',
                'button2_text' => 'CONTACTAR',
                'button2_url' => '#'
            ),
            array(
                'title' => 'INSUMOS',
                'subtitle' => 'Semillas, protección y nutrición de marcas líderes con el respaldo CIABAY, para cultivos más sanos y rentables.',
                'button1_text' => 'VER PRODUCTOS',
                'button1_url' => '#',
                'button2_text' => 'VER POR CULTIVO',
                'button2_url' => '#'
            ),
            array(
                'title' => 'MÁQUINAS',
                'subtitle' => 'Maquinaria agrícola de última tecnología',
                'button1_text' => 'VER MÁQUINAS',
                'button1_url' => '#',
                'button2_text' => 'FINANCIACIÓN',
                'button2_url' => '#'
            )
        );
    }
    
    ob_start();
    ?>
    <div class="ciabay-content-carousel">
        <div class="container">
            <?php foreach ($slides_data as $index => $slide): ?>
            <div class="content-slide <?php echo $index === 1 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
                <p class="slide-subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                <div class="slide-buttons">
                    <?php if (!empty($slide['button1_text'])): ?>
                    <a href="<?php echo esc_url($slide['button1_url']); ?>" class="btn btn-primary"><?php echo esc_html($slide['button1_text']); ?></a>
                    <?php endif; ?>
                    <?php if (!empty($slide['button2_text'])): ?>
                    <a href="<?php echo esc_url($slide['button2_url']); ?>" class="btn btn-secondary"><?php echo esc_html($slide['button2_text']); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ciabay_content_carousel', 'ciabay_content_carousel_shortcode');

/**
 * Video Section Shortcode
 */
function ciabay_video_section_shortcode($atts) {
    $atts = shortcode_atts(array(
        'video_url' => '',
        'thumbnail' => '',
        'description' => 'Única empresa paraguaya que te ofrece todo lo que el productor necesita.'
    ), $atts);
    
    ob_start();
    ?>
    <div class="ciabay-video-section">
        <div class="video-container">
            <div class="video-thumbnail">
                <img src="<?php echo esc_url($atts['thumbnail']); ?>" alt="Video Ciabay">
                <div class="play-overlay">
                    <button class="play-button" data-video="<?php echo esc_url($atts['video_url']); ?>">
                        <span>Ver Video</span>
                        <i class="fa fa-play"></i>
                    </button>
                </div>
            </div>
        </div>
        <p class="video-description"><?php echo esc_html($atts['description']); ?></p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ciabay_video_section', 'ciabay_video_section_shortcode');

/**
 * Stats Section Shortcode
 */
function ciabay_stats_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'CONFIANZA RESPALDADA POR RESULTADOS'
    ), $atts);
    
    ob_start();
    ?>
    <div class="ciabay-stats-section">
        <h2><?php echo esc_html($atts['title']); ?></h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">+30</div>
                <div class="stat-label">AÑOS</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">+300</div>
                <div class="stat-label">COLABORADORES</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">08</div>
                <div class="stat-label">SUCURSALES</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">+120</div>
                <div class="stat-label">TÉCNICOS DE POST VENTA</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">96%</div>
                <div class="stat-label">TASA DE SATISFACCIÓN</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">1</div>
                <div class="stat-label">PRIMEROS EN VENTAS</div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ciabay_stats', 'ciabay_stats_shortcode');

/**
 * Custom Header Shortcode
 */
function ciabay_custom_header_shortcode() {
    ob_start();
    ?>
    <!-- Top Bar -->
    <div class="ciabay-top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <span class="top-bar-text">SI AMÁS EL ROJO, ESTA TIENDA ES PARA VOS.</span>
                </div>
                <div class="top-bar-right">
                    <div class="delivery-info">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/botonred.png" alt="Case Red" class="logo-img">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Header -->
    <div class="ciabay-main-header">
        <div class="container">
            <div class="header-content">
                <!-- White rounded container -->
                <div class="header-white-container">
                    <!-- Logo -->
                    <div class="site-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.jpg" alt="CIABAY" class="logo-img">
                        </a>
                    </div>
                    
                    <!-- Desktop Navigation Menu -->
                    <nav class="desktop-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'desktop-menu',
                            'fallback_cb' => 'ciabay_desktop_fallback_menu',
                            'walker' => new Walker_Nav_Menu()
                        ));
                        ?>
                    </nav>
                    
                    <!-- Header Actions (Search, etc.) -->
                    <div class="header-actions">
                        <!-- Search Toggle Button -->
                        <button class="search-toggle-btn" aria-label="Toggle Search">
                            <span class="search-icon"></span>
                        </button>
                        
                        <!-- Mobile Menu Toggle -->
                        <div class="mobile-menu-toggle">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
                
                <!-- Search Dropdown -->
                <div class="header-search-dropdown">
                    <div class="search-container">
                        <?php get_search_form(); ?>
                    </div>
                </div>
                
                <!-- Mobile Navigation Menu -->
                <nav class="mobile-nav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'mobile-menu',
                        'fallback_cb' => 'ciabay_mobile_fallback_menu',
                        'walker' => new Walker_Nav_Menu()
                    ));
                    ?>
                </nav>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ciabay_header', 'ciabay_custom_header_shortcode');

// Fallback menu for desktop
function ciabay_desktop_fallback_menu() {
    echo '<ul class="desktop-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Inicio</a></li>';
    echo '<li><a href="' . esc_url(home_url('/sobre-nosotros')) . '">Sobre Nosotros</a></li>';
    echo '<li><a href="' . esc_url(home_url('/servicios')) . '">Servicios</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contacto')) . '">Contacto</a></li>';
    echo '</ul>';
}

// Fallback menu for mobile
function ciabay_mobile_fallback_menu() {
    echo '<ul class="mobile-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Inicio</a></li>';
    echo '<li><a href="' . esc_url(home_url('/sobre-nosotros')) . '">Sobre Nosotros</a></li>';
    echo '<li><a href="' . esc_url(home_url('/servicios')) . '">Servicios</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contacto')) . '">Contacto</a></li>';
    echo '</ul>';
}

// Enqueue header styles and scripts
function ciabay_header_assets() {
    // Only load on frontend
    if (!is_admin()) {
        // Enqueue header styles
        wp_enqueue_style('ciabay-header-styles', get_stylesheet_directory_uri() . '/css/custom.css', array(), '1.0.0');
        
        // Enqueue header scripts
        wp_enqueue_script('ciabay-header-scripts', get_stylesheet_directory_uri() . '/js/carousel.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'ciabay_header_assets');
