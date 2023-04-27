<?php
/*
Plugin Name: Snillrik Booster buttons for Tax
Plugin URI: http://www.snillrik.com
Description: This needs the Booster plugin to work. add the shortcode [snpopjboost_tax_shortcode business="Business" private="Private"] where you want buttons to toggle prices with or without tax. Mostly used for popup, but can be used anywhere.
Version: 0.1.1
Author: Mattias P Kallio    
Author URI: https://www.snillrik.com
Author Email: kallio@snillrik.se
Text Domain: snillrik-pop-booster-tax
Domain Path: /languages/ 
*/

//add frontend css
add_action('wp_enqueue_scripts', 'snpopjboost_tax_css');
function snpopjboost_tax_css()
{
    wp_register_style('snpopjboost_tax_css', plugins_url('css/main.css', __FILE__));
}
//add frontend js
add_action('wp_enqueue_scripts', 'snpopjboost_tax_js');
function snpopjboost_tax_js()
{
    wp_register_script('snpopjboost_tax_js', plugins_url('js/main.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('snpopjboost_tax_js', 'snpopjboost_tax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}

//actions and shortcode
//init functions 
add_action('init', function () {
    add_action('wp_ajax_nopriv_snpopjboost_tax_display', 'snpopjboost_tax_display', 999);
    add_action('wp_ajax_snpopjboost_tax_display', 'snpopjboost_tax_display', 999);
    add_shortcode('snpopjboost_tax_shortcode', 'snpopjboost_tax_shortcode');
}, 999);

//ajax function for setting session variable
function snpopjboost_tax_display()
{
    if (function_exists('wcj_session_maybe_start')) {
        wcj_session_maybe_start();
        $taxit = isset($_POST['taxit']) ? sanitize_text_field($_POST['taxit']) : 'incl';
        wcj_session_set('wcj_toggle_tax_display', $taxit);
        wp_send_json_success("prices are now $taxit");
    }
    wp_send_json_error("jetpack not active?");
}

//shortcode function
function snpopjboost_tax_shortcode($args)
{
    wp_enqueue_style('snpopjboost_tax_css');
    wp_enqueue_script('snpopjboost_tax_js');
    $taxit = '';
    if (function_exists('wcj_session_maybe_start')) {
        wcj_session_maybe_start();
        $taxit = wcj_session_get('wcj_toggle_tax_display', 'incl');
    }
    $args = shortcode_atts(array(
        'business' => 'Business',
        'private' => 'Private',
    ), $args, 'snillrik-pop-booster-tax');


    return '<div class="taxitornot-box"><div class="taxitornot-box-inner">
    <span class="taxitornot-button taxitornot business ' . ($taxit == 'excl' ? 'selected' : '') . '" data-taxit="excl">' . esc_attr__($args["business"], 'snillrik-pop-booster-tax') . '</span>
    <span class="taxitornot-button taxitornot private ' . ($taxit == 'incl' ? 'selected' : '') . '" data-taxit="incl">' . esc_attr__($args["private"], 'snillrik-pop-booster-tax') . '</span>
    </div></div>';
}
