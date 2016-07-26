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

use tml\Cache;
use tml\utils\FileUtils;

include dirname(__FILE__) . "/../common/url_helpers.php";

if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$post_action = isset($_POST['action']) ? $_POST['action'] : null;

if ($post_action == 'sync_cache') {

    Cache::invalidateVersion();
    echo "<div class='updated'><p><strong>" . __('Cache version has been updated to the current release version from Translation Exchange.') . "</strong></p></div>";

} elseif ($post_action == 'delete_cache') {

    $version_path = get_option('tml_cache_path') . "/" . $_POST['version'];
    FileUtils::rrmdir($version_path);

} elseif ($post_action == 'use_cache') {

    update_option("tml_cache_type", $_POST['type']);

    if (isset($_POST['adapter']) && $_POST['adapter'] !== '')
        update_option("tml_cache_adapter", $_POST['adapter']);

    if (isset($_POST['host']) && $_POST['host'] !== '')
        update_option("tml_cache_host", $_POST['host']);

    if (isset($_POST['port']) && $_POST['port'] !== '')
        update_option("tml_cache_port", $_POST['port']);

    if (isset($_POST['namespace']) && $_POST['namespace'] !== '')
        update_option("tml_cache_namespace", $_POST['namespace']);

    if (isset($_POST['version_check_interval']) && $_POST['version_check_interval'] !== '')
        update_option("tml_cache_version_check_interval", $_POST['version_check_interval']);

    if (isset($_POST['version']) && $_POST['version'] !== '')
        update_option("tml_cache_version", $_POST['version']);

} elseif ($post_action == 'download_cache') {

    include dirname(__FILE__) . "/cache/download_snapshot.php";

} else { // snapshot generation end

    // $field_sets = array($application_fields);

    include dirname(__FILE__) . "/form.php";
}
