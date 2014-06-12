<?php
/*
  Plugin Name: Tr8n
  Plugin URI: http://tr8nhub.com/
  Description: Crowdsourced translation service
  Author: TranslationExchange
  Version: 0.1.0
  Author URI: https://translationexchange.com/
  License: GPL (http://www.gnu.org/licenses/gpl.txt)
  Text Domain: tr8nhub
  Domain Path: /tr8n
 */

/*
 * Tr8n v0.1.0
 * http://TranslationExchange.com/
 *
 * Copyright 2014, Michael Berkovich, TranslationExchange
 * Licensed under the MIT.
 * http://TranslationExchange.com/license
 *
 */

add_option('tr8n_version', '0.1.0');

require_once(dirname(__FILE__).'/../../../vendor/tr8n/tr8n-client-sdk/library/Tr8n.php');

// tr8n_init_client_sdk('https://sandbox.tr8nhub.com', 'df8a6877f0918aeb5', '5c07f42936f816eda');

tr8n_init_client_sdk(get_option('tr8n_server_url'), get_option('tr8n_application_key'), get_option('tr8n_application_secret'));


if (\Tr8n\Config::instance()->isEnabled()) {
    apply_filters('debug', 'Tr8n Initialized');
}


//class Tr8nWordpressConfig extends \Tr8n\Config {
//    public function isCachingEnabled() {
//        return true;
//    }
//}
//\Tr8n\Config::init(new Tr8nWordpressConfig());

function tr8n_prepare_tokens_and_options($args) {
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
        if (\Tr8n\Utils\StringUtils::startsWith('token:', $value)) {
            $parts = explode('=', substr($value, 6));
            $value = trim($parts[1], '\'"');

            $parts = explode('.', $parts[0]);
            if (count($parts) == 1) {
                $tokens[$parts[0]] = $value;
            } else {
                if (!isset($tokens[$parts[0]])) $tokens[$parts[0]] = array();
                \Tr8n\Utils\ArrayUtils::createAttribute($tokens[$parts[0]], array_slice($parts,1), $value);
            }
        } else if (\Tr8n\Utils\StringUtils::startsWith('option:', $value)) {
            $parts = explode('=', substr($value, 7));
            $value = trim($parts[1], '\'"');

            $parts = explode('.', $parts[0]);
            if (count($parts) == 1) {
                $options[$parts[0]] = $value;
            } else {
                if (!isset($options[$parts[0]])) $options[$parts[0]] = array();
                \Tr8n\Utils\ArrayUtils::createAttribute($options[$parts[0]], array_slice($parts,1), $value);
            }
        }
    }

    if (isset($args['split'])) {
        $options['split'] = $args['split'];
    }

    return array("description" => $description, "tokens" => $tokens, "options" => $options);
}

function tr8n_translate($atts, $content = null) {
    if (\Tr8n\Config::instance()->isDisabled()) {
        return $content;
    }

    if ($content == null) return $content;

    $label = trim($content);
    $atts = tr8n_prepare_tokens_and_options($atts);

//    \Tr8n\Logger::instance()->info("translating: \"" . $content . "\"", $tokens);

    try {
        return tr($label, $atts["description"], $atts["tokens"], $atts["options"]);
    } catch(\Tr8n\Tr8nException $e) {
        \Tr8n\Logger::instance()->info($e->getMessage());
        return $content;
    }
}
add_shortcode('tr8n:tr', 'tr8n_translate', 2);

function tr8n_translate_html($attrs, $content = null) {
    $attrs = tr8n_prepare_tokens_and_options($attrs);

//    \Tr8n\Logger::instance()->debug($content);
    return trh($content, $attrs["description"], $attrs["tokens"], $attrs["options"]);
}
add_shortcode('tr8n:trh', 'tr8n_translate_html', 2);

function tr8n_block($atts, $content = null) {
    if (\Tr8n\Config::instance()->isDisabled()) {
        return do_shortcode($content);
    }

    $options = array();
    if (isset($atts['source'])) {
        $options['source'] = $atts['source'];
    }
    if (isset($atts['locale'])) {
        $options['locale'] = $atts['locale'];
    }
    \Tr8n\Config::instance()->beginBlockWithOptions($options);
    $content = do_shortcode($content);
    \Tr8n\Config::instance()->finishBlockWithOptions();
    return $content;
}
add_shortcode('tr8n:block', 'tr8n_block', 2);

function tr8n_title($title, $id) {
    if (get_option('tr8n_translate_html') == 'true') {
        if ($title != strip_tags($title)) {
            return trh($title);
        }
        return tr($title);
    }
    return do_shortcode($title);
}
add_filter('the_title', 'tr8n_title', 10, 2);
add_filter('widget_title', 'tr8n_title', 10, 2);
add_filter('wp_title', 'tr8n_title', 10, 2);

// function tr8n_wp_title_filter($title, $id) {
//     return do_shortcode($title);
// }
// add_filter('wp_title', 'tr8n_wp_title_filter', 10, 2);

function tr8n_the_content_filter($content) {
    if (get_option('tr8n_translate_html') == 'true') {
        if (strstr($content, 'tr8n:manual') !== false)
            return $content;
        return trh($content);
    }
    \Tr8n\Logger::instance()->debug($content);
    return $content;
}
add_filter('the_content', 'tr8n_the_content_filter');

function tr8n_widget_text_filter($content) {
    return do_shortcode($content);
}
add_filter('widget_text', 'tr8n_widget_text_filter');

function tr8n_the_excerpt_filter($content) {
//    \Tr8n\Logger::instance()->debug($content);
    return $content;
}
add_filter('the_excerpt', 'tr8n_the_excerpt_filter');

function tr8n_comment_text_filter($content) {
    if (get_option('tr8n_translate_html') == 'true') {
        return trh($content);
    }
//    \Tr8n\Logger::instance()->debug($content);
    return $content;
}
add_filter('comment_text ', 'tr8n_comment_text_filter');


function tr8n_request_shutdown() {
    tr8n_complete_request();
//    \Tr8n\Config::instance()->application->submitMissingKeys();
}
add_action('shutdown', 'tr8n_request_shutdown');

/*
 * Api Forwarding
 */

//add_rewrite_rule('^tr8n/api/([^/]*)/([^/]*)/?','vendor/tr8n_php_clientsdk/library/Tr8n/Api/Router.php?controller=$matches[1]&action=$matches[2]','top');

/*
 * Javascript
 */

function tr8n_enqueue_style() {
    if (\Tr8n\Config::instance()->isDisabled()) {
        return;
    }

    wp_enqueue_script('tr8n', \Tr8n\Config::instance()->application->jsBootUrl());
}
add_action('wp_enqueue_scripts', 'tr8n_enqueue_style');
add_action('admin_init', 'tr8n_enqueue_style');

/*
 * Admin Settings
 */

function tr8n_menu_pages() {
    // Add the top-level admin menu
    $page_title = 'Tr8n Settings';
    $menu_title = 'Tr8n';
    $capability = 'manage_options';
    $menu_slug = 'tr8n-admin';
    $function = 'tr8n_settings';
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);

    // Add submenu page with same slug as parent to ensure no duplicates
    $sub_menu_title = __('Settings');
    add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

    // Now add the submenu page for Help
    $submenu_page_title = __('Tr8n Tools');
    $submenu_title = __('Tools');
    $submenu_slug = 'tr8n-tools';
    $submenu_function = 'tr8n_tools';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);

    // Now add the submenu page for Help
    $submenu_page_title = __('Tr8n Help');
    $submenu_title = __('Help');
    $submenu_slug = 'tr8n-help';
    $submenu_function = 'tr8n_help';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
}
add_action('admin_menu', 'tr8n_menu_pages');

function tr8n_settings() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/settings/index.php');
}

function tr8n_help() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/help/index.php');
}

function tr8n_tools() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/tools/index.php');
}

function tr8n_plugin_action_links($links, $file) {
    if (preg_match('/tr8n/', $file)) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=tr8n-admin">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'tr8n_plugin_action_links', 10, 2);


/*
 * Widgets
 */
require_once('widgets/LanguageSelectorWidget.php');

function tr8n_register_widgets() {
    register_widget('LanguageSelectorWidget');
}
add_action('widgets_init', 'tr8n_register_widgets');


/**
 * Change labels from default to tr8n translated
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function tr8n_translate_field_names( $translated_text, $text, $domain ) {
//    return trh($text, null, array(), array("source" => "wordpress"));
    if (get_option('tr8n_translate_wordpress') == 'true') {
        foreach(array('%s', 'http://', '%1', '%2', '%3', '%4', '&#', '%d', '&gt;') as $token) {
            if (strpos($text, $token) !== FALSE) return $translated_text;
        }
        return tr($text, null, array(), array("source" => "wordpress"));
//    return "[" . $translated_text . "]";
    }
    return $translated_text;
}
add_filter( 'gettext', 'tr8n_translate_field_names', 20, 3 );
