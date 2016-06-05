<?php

/*
  Copyright (c) 2016 Translation Exchange, Inc

   _______                  _       _   _             ______          _
  |__   __|                | |     | | (_)           |  ____|        | |
     | |_ __ __ _ _ __  ___| | __ _| |_ _  ___  _ __ | |__  __  _____| |__   __ _ _ __   __ _  ___
     | | '__/ _` | '_ \/ __| |/ _` | __| |/ _ \| '_ \|  __| \ \/ / __| '_ \ / _` | '_ \ / _` |/ _ \
     | | | | (_| | | | \__ \ | (_| | |_| | (_) | | | | |____ >  < (__| | | | (_| | | | | (_| |  __/
     |_|_|  \__,_|_| |_|___/_|\__,_|\__|_|\___/|_| |_|______/_/\_\___|_| |_|\__,_|_| |_|\__, |\___|
                                                                                         __/ |
                                                                                        |___/
    GNU General Public License, version 2

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

    http://www.gnu.org/licenses/gpl-2.0.html
*/

use Tml\Session;


/**
 * The client side option
 */
function tml_enqueue_client_script()
{
    $tml_script_host = get_option("tml_script_host");
    if (empty($tml_script_host)) $tml_script_host = "https://cdn.translationexchange.com/tools/tml/stable/tml.min.js";

    $cache_interval = 86400;
    $t = time();
    $t = $t - ($t % $cache_interval);
    $tml_script_host = $tml_script_host . '?ts=' . $t;

    wp_register_script('tml_js', $tml_script_host, false, null, false);
    wp_register_script('tml_init', plugins_url('/../assets/javascripts/init_client.js', __FILE__), false, null, false);
    wp_enqueue_script('tml_js');
    wp_enqueue_script('tml_init');

    $tml_host = get_option('tml_host');
    if (empty($tml_host)) $tml_host = "https://api.translationexchange.com";

    $options = array(
        "host" => $tml_host,
        "key" => get_option('tml_key'),
        "token" => get_option('tml_token'),
        "advanced" => get_option('tml_script_options')
    );

    if (get_option("tml_cache_type") == "local" && get_option("tml_cache_version") != '0') {
        $options['cache'] = array(
            "path" => plugins_url("translation-exchange/cache"),
            "version" => get_option('tml_cache_version')
        );
    }

    wp_localize_script('tml_init', 'TmlConfig', $options);
}

/**
 * The server side option
 */
function tml_enqueue_server_script()
{
    wp_register_script('tml_init', plugins_url('/../assets/javascripts/init_server.js', __FILE__), false, null, true);
    wp_enqueue_script('tml_init');

    $agent_host = get_option('tml_agent_host');
    if (empty($agent_host)) $agent_host = "https://tools.translationexchange.com/agent/stable/agent.min.js";

    $options = array(
        "key" => get_option('tml_key'),
        "agent" => array(
            "host" => $agent_host
        )
    );

    $agent_options = get_option('tml_agent_options');
    if (!empty($agent_options)) {
        $result = json_decode(stripslashes($agent_options), true);

        if (json_last_error() == JSON_ERROR_NONE) {
            $options['agent'] = array_merge($options['agent'], $result);
        }
    }

    $cache_interval = 86400;
    if (isset($options['agent']['cache']))
        $cache_interval = $options['agent']['cache'];
    if (isset($options['agent']['host']))
        $agent_host = $options['agent']['host'];

    $t = time();
    $t = $t - ($t % $cache_interval);
    $options['agent']['host'] = $agent_host . '?ts=' . $t;
    $options['agent']['languages'] = array();
    $options['agent']['locale'] = tml_current_locale();
    $options['agent']['css'] = tml_application()->css;
    $options['agent']['sdk'] = "tml-php v" . Tml\Version::VERSION;
    $options['agent']['source'] = tml_current_source();

    foreach (Session::application()->languages as $lang) {
        array_push($options['agent']['languages'], array(
            "locale" => $lang->locale,
            "english_name" => $lang->english_name,
            "native_name" => $lang->native_name,
            "flag_url" => $lang->flag_url
        ));
    }

    wp_localize_script('tml_init', 'TmlConfig', $options);
}

/**
 * Mechanism for injecting JavaScript
 */
function tml_enqueue_scripts()
{
    if (get_option('tml_mode') == "client") {
        tml_enqueue_client_script();
    } else {
        tml_enqueue_server_script();
    }
}

add_action('wp_enqueue_scripts', 'tml_enqueue_scripts');


function tml_settings()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/settings/index.php');
}

function tml_help()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/help/index.php');
}

function tml_tools()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/tools/index.php');
}

function tml_dashboard()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/dashboard/index.php');
}

/*
 * Admin Settings
 */
function tml_menu_pages()
{
    // Add the top-level admin menu
    $page_title = 'Translation Exchange Settings';
    $menu_title = 'Translation Exchange';
    $capability = 'manage_options';
    $menu_slug = 'tml-admin';
    $function = 'tml_settings';
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, plugin_dir_url(__FILE__) . "../assets/images/icon.png");

    $sub_menu_title = __('Settings');
    add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

//    $submenu_page_title = __('Dashboard');
//    $submenu_title = __('Dashboard');
//    $submenu_slug = 'tml-dashboard';
//    $submenu_function = 'tml_dashboard';
//    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
//
//    $submenu_page_title = __('Translation Center');
//    $submenu_title = __('Translation Center');
//    $submenu_slug = 'tml-tools';
//    $submenu_function = 'tml_tools';
//    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);

//    $submenu_page_title = __('Tml Help');
//    $submenu_title = __('Help');
//    $submenu_slug = 'tml-help';
//    $submenu_function = 'tml_help';
//    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
}

add_action('admin_menu', 'tml_menu_pages');


/**
 * Action for completing request
 *
 */
function tml_request_shutdown()
{
    tml_complete_request();
}

add_action('shutdown', 'tml_request_shutdown');
