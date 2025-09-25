<?php
/**
 * Custom search form template
 *
 * @package Ciabay_Divi
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php echo esc_html_x('Search for:', 'label', 'ciabay-divi'); ?></span>
        <input type="search" class="search-field" 
               placeholder="<?php echo esc_attr_x('Buscar...', 'placeholder', 'ciabay-divi'); ?>" 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               title="<?php echo esc_attr_x('Search for:', 'label', 'ciabay-divi'); ?>" />
    </label>
    <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x('Search', 'submit button', 'ciabay-divi'); ?>">
        <span class="search-icon" aria-hidden="true"></span>
        <span class="btn-text"><?php echo esc_html_x('Buscar', 'submit button', 'ciabay-divi'); ?></span>
    </button>
</form>
