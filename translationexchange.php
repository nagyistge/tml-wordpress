<?php
/*
  Plugin Name: Translation Exchange
  Plugin URI: http://wordpress.org/plugins/translationexchange/
  Description: Translate your Wordpress site into any language in minutes.
  Author: Translation Exchange, Inc
  Version: 0.3.21
  Author URI: https://translationexchange.com/
  License: GPLv2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 */

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

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

if ( ! defined( 'ABSPATH' ) ) exit;

add_option('tml_mode', 'client');

add_option('tml_cache_type', 'none');
add_option('tml_cache_version', '0');

add_option('tml_cache_path', plugin_dir_path(__FILE__) . "cache");
update_option('tml_cache_path', plugin_dir_path(__FILE__) . "cache");

require_once(dirname(__FILE__).'/src/tml/src/init.php');

use Tml\Session;
use Tml\utils\ArrayUtils;
use Tml\utils\StringUtils;

/**
 * Prepare cacheadapter for both client and server
 */
if (get_option('tml_mode') == "server_automated" || get_option('tml_mode') == "server_manual") {
    $tml_cache = null;

    if (get_option('tml_cache_type') == 'dynamic') {
        $tml_cache = array(
            "enabled"                   => true,
            "adapter"                   => get_option('tml_cache_adapter'),
            "host"                      => get_option('tml_cache_host'),
            "port"                      => get_option('tml_cache_port'),
            "namespace"                 => get_option('tml_cache_namespace'),
            "version_check_interval"    => get_option('tml_cache_version_check_interval')
        );
    } elseif (get_option('tml_cache_type') == 'local') {
        $tml_cache = array(
            "enabled"   => true,
            "adapter"   => "file",
            "path"      => get_option('tml_cache_path'),
            "version"   => get_option('tml_cache_version', 1)
        );
    } else {
        $tml_cache = array(
            "enabled"   => false,
            "adapter"   => "file"
        );
    }

    $api_host = get_option('tml_host');
    if (empty($api_host)) $api_host = 'https://api.translationexchange.com';

    $cdn_host = get_option('tml_cdn_host');
    if (empty($cdn_host)) $cdn_host = 'https://cdn.translationexchange.com';

    $agent_host = get_option('tml_agent_host');
    if (empty($agent_host)) $agent_host = 'https://tools.translationexchange.com/agent/stable/agent.min.js';

    tml_init(array(
        "key"       => get_option('tml_key'),
        "host"      => $api_host,
        "cdn_host"  => $cdn_host,
        "agent" => array(
            "host" => $agent_host
        ),
//        "log"       => array(
//            "enabled"   => false,
//            "severity"  => "debug",
//            "path"      => plugin_dir_path(__FILE__) . "/log/tml.log"
//        ),
        "cache" => $tml_cache
    ));
}

/**
 * Report to WordPress Debug that we are ready
 */
if (Session::instance()->isActive()) {
    apply_filters('debug', 'Tml PHP Initialized');
}

/**
 * For shortcodes, extracts the tokens and options from the token information
 *
 * @param $args
 * @return array
 */
function tml_prepare_tokens_and_options($args) {
    $tokens = array();
    $options = array();

    if (count($args) == 0)
        return array("description" => "", "tokens" => $tokens, "options" => $options);

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

    foreach($args as $key => $value) {
        // echo($key . " = " . $value . "<br>");

        if (StringUtils::startsWith('option:', $value)) {
            $parts = explode('=', substr($value, 7));
            $value = trim($parts[1], '\'"');

            $parts = explode('.', $parts[0]);
            if (count($parts) == 1) {
                $options[$parts[0]] = $value;
            } else {
                if (!isset($options[$parts[0]])) $options[$parts[0]] = array();
                ArrayUtils::createAttribute($options[$parts[0]], array_slice($parts,1), $value);
            }
        } else if (StringUtils::startsWith('token:', $value)) {
            $parts = explode('=', substr($value, 6));
            $value = trim($parts[1], '\'"');

            $parts = explode('.', $parts[0]);
            if (count($parts) == 1) {
                $tokens[$parts[0]] = $value;
            } else {
                if (!isset($tokens[$parts[0]])) $tokens[$parts[0]] = array();
                ArrayUtils::createAttribute($tokens[$parts[0]], array_slice($parts,1), $value);
            }
        } else {
            $tokens[$key] = $value;
        }
    }

    if (isset($args['split'])) {
        $options['split'] = $args['split'];
    }

    return array("description" => $description, "tokens" => $tokens, "options" => $options);
}

/**
 * Translates HTML content
 *
 * @param $label
 * @param $description
 * @param $tokens
 * @param $options
 * @return array
 */
function tml_tranlsate_html($label, $description = "", $tokens = array(), $options = array()) {
    $opts = stripcslashes(get_option('tml_script_options', ''));
    if ($opts !== "") {
        $opts = json_decode($opts, true);
        if ($opts !== null) $options = array_merge_recursive($options, $opts);
    }

//    return $label;
    return trh($label, $description, $tokens, $options);
}

include plugin_dir_path(__FILE__)."/src/shortcodes.php";
include plugin_dir_path(__FILE__)."/src/filters.php";
include plugin_dir_path(__FILE__)."/src/actions.php";
include plugin_dir_path(__FILE__)."/src/widgets.php";

