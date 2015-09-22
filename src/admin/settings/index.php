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

use tml\Cache;
use tml\utils\FileUtils;

if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

if (isset($_POST['action']) && $_POST['action'] == 'sync_cache') {
    Cache::invalidateVersion();
    echo "<div class='updated'><p><strong>" .  __('Cache version has been updated to the current release version from Translation Exchange.') . "</strong></p></div>";
}

if (isset($_POST['action']) && $_POST['action'] == 'delete_cache') {
    $version_path = get_option('tml_cache_path') . "/" . $_POST['version'];
    FileUtils::rrmdir($version_path);
}

if (isset($_POST['action']) && $_POST['action'] == 'use_cache') {
    update_option("tml_cache_type", $_POST['type']);

    if (isset($_POST['adapter']) && $_POST['adapter'] !== '')
        update_option("tml_cache_adapter", $_POST['adapter']);

    if (isset($_POST['host']) && $_POST['host'] !== '')
        update_option("tml_cache_host", $_POST['host']);

    if (isset($_POST['port']) && $_POST['port'] !== '')
        update_option("tml_cache_port", $_POST['port']);

    if (isset($_POST['version']) && $_POST['version'] !== '')
        update_option("tml_cache_version", $_POST['version']);
}

if (isset($_POST['action']) && $_POST['action'] == 'download_cache') {

    include_once dirname(__FILE__)."/releases.php";

} else { // snapshot generation end

    $submit_field_name = 'tml_submit_hidden';
    $cache_field_name = 'tml_update_cache_hidden';

    $application_fields = array(
        'tml_host' => array("title" => __('Host:'), "value" => get_option('tml_host'), "default" => "https://api.translationexchange.com", "style" => "display:none"),
        'tml_key' => array("title" => __('Project Key:'), "value" => get_option('tml_key'), "default" => "Paste your application key here"),
        'tml_token' => array("title" => __('Access Token:'), "value" => get_option('tml_token'), "default" => "Paste your application token here"),
        'tml_mode' => array("title" => __('Mode:'), "value" => get_option('tml_mode'), "default" => "", "type" => "radio", "options" => array(
            array("title" => __('Client-side (using JavaScript)'), "value" => "client"),
            array("title" => __('Server-side (using PHP)'), "value" => "server_automated"),
//            array("title" => __('Server-side (manual, using shortcodes)'), "value" => "server_manual"),
        )),
    );

    $script_fields = array(
        'tml_script_host' => array("title" => __('Script Host:'), "value" => get_option('tml_script_host'), "type" => "text", "default" => "https://cdn.translationexchange.com/tools/tml/stable/tml.min.js", "style" => "display:none"),
        'tml_script_options' => array("title" => __('Options:'), "value" => get_option('tml_script_options'), "type" => "textarea", "default" => __('Provide custom script options in a JSON format'), "style" => "display:none"),
    );

    $field_sets = array($application_fields, $script_fields);

    if (isset($_POST[ $submit_field_name ]) && $_POST[ $submit_field_name ] == 'Y') {

        $index = 0;
        foreach($field_sets as $set) {
            foreach($set as $key => $attributes) {
                update_option( $key, $_POST[ $key ] );
                $field_sets[$index][$key] = array_merge($attributes, array("value" => $_POST[$key]));
            }
            $index++;
        }

        if (get_option("tml_mode") == "client" && get_option("tml_cache_type") == "dynamic")
            update_option("tml_cache_type", "none");
        ?>

        <div class="updated"><p><strong><?php _e('Settings have been saved.'); ?></strong></p></div>
    <?php
    }

    // $field_sets = array($application_fields);

    include_once dirname(__FILE__)."/basics.php";

}
