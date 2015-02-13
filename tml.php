<?php
/*
  Plugin Name: Tml
  Plugin URI: http://translationexchange.com/
  Description: Translation service
  Author: Translation Exchange, Inc
  Version: 0.1.0
  Author URI: http://translationexchange.com/
  License: MIT (http://opensource.org/licenses/MIT)
  Text Domain: translationexchange
  Domain Path: /
 */

/*
 * Tml v0.2.0
 * http://translationexchange.com/
 *
 * Copyright 2015, Michael Berkovich, TranslationExchange
 * Licensed under the MIT.
 * http://translationexchange.com/license
 *
 */
add_option('tml_version', '0.1.2');

// require_once(dirname(__FILE__).'/../tml-php/library/tml.php');

require_once(dirname(__FILE__).'/../../../vendor/translationexchange/tml/library/tml.php');
//require_once( dirname( __FILE__ ) . '/sdk/library/Tml.php' );

use tml\Config;
use tml\Logger;
use tml\TmlException;
use tml\utils\ArrayUtils;
use tml\utils\StringUtils;

tml_init(get_option('tml_token'), get_option('tml_host'));

if (Config::instance()->isEnabled()) {
    apply_filters('debug', 'Tml Initialized');
}

//class TmlWordpressConfig extends \Tml\Config {
//    public function isCachingEnabled() {
//        return true;
//    }
//}
//\Tml\Config::init(new TmlWordpressConfig());

function tml_prepare_tokens_and_options($args) {
    $tokens = array();
    $options = array();

    if (is_string($args)) $args = array();

    $description = isset($args['description']) ? $args['description'] : null;
    if ($description == null) {
        $description = isset($args['context']) ? $args['context'] : null;
    }

    if (isset($args['tokens'])) {
        $tokens = json_decode($args['tokens'], true);
    }

    if (isset($args['options'])) {
        $options = json_decode($args['options'], true);
    }

    foreach(array_values($args) as $value) {
        if (StringUtils::startsWith('token:', $value)) {
            $parts = explode('=', substr($value, 6));
            $value = trim($parts[1], '\'"');

            $parts = explode('.', $parts[0]);
            if (count($parts) == 1) {
                $tokens[$parts[0]] = $value;
            } else {
                if (!isset($tokens[$parts[0]])) $tokens[$parts[0]] = array();
                ArrayUtils::createAttribute($tokens[$parts[0]], array_slice($parts,1), $value);
            }
        } else if (StringUtils::startsWith('option:', $value)) {
            $parts = explode('=', substr($value, 7));
            $value = trim($parts[1], '\'"');

            $parts = explode('.', $parts[0]);
            if (count($parts) == 1) {
                $options[$parts[0]] = $value;
            } else {
                if (!isset($options[$parts[0]])) $options[$parts[0]] = array();
                ArrayUtils::createAttribute($options[$parts[0]], array_slice($parts,1), $value);
            }
        }
    }

    if (isset($args['split'])) {
        $options['split'] = $args['split'];
    }

    return array("description" => $description, "tokens" => $tokens, "options" => $options);
}

function tml_translate($atts, $content = null) {
    if (Config::instance()->isDisabled()) {
        return $content;
    }

    if ($content == null) return $content;

    $label = trim($content);
    $atts = tml_prepare_tokens_and_options($atts);

//    \Tml\Logger::instance()->info("translating: \"" . $content . "\"", $tokens);

    try {
        return tr($label, $atts["description"], $atts["tokens"], $atts["options"]);
    } catch(TmlException $e) {
        Logger::instance()->info($e->getMessage());
        return $content;
    }
}
add_shortcode('tml:tr', 'tml_translate', 2);

function tml_translate_html($attrs, $content = null) {
    $attrs = tml_prepare_tokens_and_options($attrs);

//    \Tml\Logger::instance()->debug($content);
    return trh($content, $attrs["description"], $attrs["tokens"], $attrs["options"]);
}
add_shortcode('tml:trh', 'tml_translate_html', 2);

function tml_block($atts, $content = null) {
    if (Config::instance()->isDisabled()) {
        return do_shortcode($content);
    }

    $options = array();
    if (isset($atts['source'])) {
        $options['source'] = $atts['source'];
    }
    if (isset($atts['locale'])) {
        $options['locale'] = $atts['locale'];
    }
    Config::instance()->beginBlockWithOptions($options);
    $content = do_shortcode($content);
    Config::instance()->finishBlockWithOptions();
    return $content;
}
add_shortcode('tml:block', 'tml_block', 2);

function tml_title($title, $id) {
    if (get_option('tml_translate_html') == 'true') {
        if ($title != strip_tags($title)) {
            return trh($title);
        }
        return tr($title);
    }

    return do_shortcode($title);
}
add_filter('the_title', 'tml_title', 10, 2);
add_filter('widget_title', 'tml_title', 10, 2);
add_filter('wp_title', 'tml_title', 10, 2);

// function tml_wp_title_filter($title, $id) {
//     return do_shortcode($title);
// }
// add_filter('wp_title', 'tml_wp_title_filter', 10, 2);

function tml_the_content_filter($content) {
    if (get_option('tml_translate_html') == 'true') {
        if (strstr($content, 'tml:manual') !== false)
            return $content;
        return trh($content);
    }
    // Logger::instance()->debug($content);
    return $content;
}
add_filter('the_content', 'tml_the_content_filter');

function tml_widget_text_filter($content) {
    return do_shortcode($content);
}
add_filter('widget_text', 'tml_widget_text_filter');

function tml_the_excerpt_filter($content) {
//    \Tml\Logger::instance()->debug($content);
    return $content;
}
add_filter('the_excerpt', 'tml_the_excerpt_filter');

function tml_comment_text_filter($content) {
    if (get_option('tml_translate_html') == 'true') {
        return trh($content);
    }
//    \Tml\Logger::instance()->debug($content);
    return $content;
}
add_filter('comment_text ', 'tml_comment_text_filter');


function tml_request_shutdown() {
    tml_complete_request();
//    \Tml\Config::instance()->application->submitMissingKeys();
}
add_action('shutdown', 'tml_request_shutdown');

/*
 * Api Forwarding
 */

//add_rewrite_rule('^tml/api/([^/]*)/([^/]*)/?','vendor/tml_php_clientsdk/library/Tml/Api/Router.php?controller=$matches[1]&action=$matches[2]','top');

/*
 * Javascript
 */

function tml_enqueue_scripts() {
    if (Config::instance()->isDisabled()) {
        return;
    }

//    wp_enqueue_script('tml_js', Config::instance()->application->tools['javascript']);
}
add_action('wp_enqueue_scripts', 'tml_enqueue_scripts');
add_action('admin_init', 'tml_enqueue_scripts');


function tml_enqueue_styles() {
    if (Config::instance()->isDisabled()) {
        return;
    }

//    wp_enqueue_script('tml_css', Config::instance()->application->tools['stylesheet']);
}
add_action('wp_enqueue_style', 'tml_enqueue_styles');
add_action('admin_init', 'tml_enqueue_styles');

/*
 * Admin Settings
 */

function tml_menu_pages() {
    // Add the top-level admin menu
    $page_title = 'Tml Settings';
    $menu_title = 'Tml';
    $capability = 'manage_options';
    $menu_slug = 'tml-admin';
    $function = 'tml_settings';
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);

    // Add submenu page with same slug as parent to ensure no duplicates
    $sub_menu_title = __('Settings');
    add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

    // Now add the submenu page for Help
//    $submenu_page_title = __('Tml Tools');
//    $submenu_title = __('Tools');
//    $submenu_slug = 'tml-tools';
//    $submenu_function = 'tml_tools';
//    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);

    // Now add the submenu page for Help
    $submenu_page_title = __('Tml Help');
    $submenu_title = __('Help');
    $submenu_slug = 'tml-help';
    $submenu_function = 'tml_help';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
}
add_action('admin_menu', 'tml_menu_pages');

function tml_settings() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/settings/index.php');
}

function tml_help() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/help/index.php');
}

function tml_tools() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/tools/index.php');
}

function tml_plugin_action_links($links, $file) {
    if (preg_match('/tml/', $file)) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=tml-admin">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'tml_plugin_action_links', 10, 2);


/*
 * Widgets
 */
require_once('widgets/LanguageSelectorWidget.php');

function tml_register_widgets() {
    register_widget('LanguageSelectorWidget');
}
add_action('widgets_init', 'tml_register_widgets');


/**
 * Change labels from default to tml translated
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function tml_translate_field_names( $translated_text, $text, $domain ) {
//    return trh($text, null, array(), array("source" => "wordpress"));
    if (get_option('tml_translate_wordpress') == 'true') {
        foreach(array('%s', 'http://', '%1', '%2', '%3', '%4', '&#', '%d', '&gt;') as $token) {
            if (strpos($text, $token) !== FALSE) return $translated_text;
        }
        return tr($text, null, array(), array("source" => "wordpress"));
//    return "[" . $translated_text . "]";
    }
    return $translated_text;
}
add_filter( 'gettext', 'tml_translate_field_names', 20, 3 );
