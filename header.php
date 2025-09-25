<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="page">
 *
 * @package Ciabay_Divi
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <?php
    // This hook is used by Divi Theme Builder to inject the header
    do_action('et_header_top');
    ?>

    <div id="main-content">
        <?php
        // This hook is used by Divi Theme Builder to inject the main content
        do_action('et_before_main_content');
        ?>
