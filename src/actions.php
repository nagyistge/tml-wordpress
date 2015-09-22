<?php

/*
  Copyright (c) 2015 Translation Exchange, Inc

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

use Tml\Config;


/**
 * Mechanism for injecting JavaScript
 */
function tml_enqueue_scripts() {
    if (get_option('tml_mode') == "server_automated" || get_option('tml_mode') == "server_manual") {
        wp_register_script('tml_init', plugins_url('/../assets/javascripts/init_server.js', __FILE__) , false, null, true);
        wp_enqueue_script('tml_init');
        wp_localize_script('tml_init', 'TmlConfig', array(
            "host" => get_option('tml_host'),
            "key" => tml_application()->key,
            "tools" => tml_application()->tools["host"],
            "stylesheet" => tml_application()->tools["stylesheet"],
            "css" => tml_application()->css,
            "javascript" => tml_application()->tools["javascript"],
            "default_locale" => tml_application()->default_locale,
            "page_locale" => Config::instance()->current_language->locale,
            "locale" => Config::instance()->current_language->locale,
            "shortcuts" => (tml_application()->isFeatureEnabled("shortcuts") ? tml_application()->shortcuts : null)
        ));
    } else if (get_option('tml_mode') == "client") {

        if (get_option("tml_script_host") != null)
            wp_register_script('tml_js', get_option("tml_script_host"), false, null, false);
        else
            wp_register_script('tml_js', 'https://cdn.translationexchange.com/tools/tml/stable/tml.min.js', false, null, false);

        wp_register_script('tml_init', plugins_url('/../assets/javascripts/init_client.js', __FILE__) , false, null, false);
        wp_enqueue_script('tml_js');
        wp_enqueue_script('tml_init');

        $options = array(
            "host"      => get_option('tml_host'),
            "key"       => get_option('tml_key'),
            "token"     => get_option('tml_token'),
            "advanced"  => get_option('tml_script_options')
        );

        if (get_option("tml_cache_type")=="local" && get_option("tml_cache_version") != '0') {
            $options['cache'] = array(
                "path" => plugins_url("translation-exchange/cache"),
                "version" => get_option('tml_cache_version')
            );
        }

        wp_localize_script('tml_init', 'TmlConfig', $options);
    }
}
add_action('wp_enqueue_scripts', 'tml_enqueue_scripts');


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

function tml_dashboard() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include('admin/dashboard/index.php');
}

/*
 * Admin Settings
 */
function tml_menu_pages() {
    // Add the top-level admin menu
    $page_title = 'Translation Exchange Settings';
    $menu_title = 'Translation Exchange';
    $capability = 'manage_options';
    $menu_slug = 'tml-admin';
    $function = 'tml_settings';
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function,  plugin_dir_url(__FILE__) . "../assets/images/icon.png");

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
function tml_request_shutdown() {
    tml_complete_request();
}
add_action('shutdown', 'tml_request_shutdown');
