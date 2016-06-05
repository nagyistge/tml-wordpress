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

echo "<p>" .  __("Downloading latest release from Translation Exchange CDN...") . "</p>";

function getCdnHost() {
    return "https://cdn.translationexchange.com";
}

function fetchFromCdn($path, $opts = array()) {
    try {
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, getCdnHost() . "/" . $path);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl_handle);
        curl_close($curl_handle);

        if (isset($opts['decode']) && $opts['decode'])
            $data = json_decode($data, true);
    } catch (Exception $e) {
        $data = false;
    }

    return $data;
}

try {
    $version = fetchFromCdn(get_option('tml_key') . "/version.json", ['decode' => true]);

    if (!$version) {
        echo "<p>" .  __("You current don't have any releases.") . "</p>";
        echo "<p>To release your translations, please visit <a href='https://dashboard.translationexchange.com'>your dashboard</a>, choose <strong>Releases</strong> section and click on <strong>Publish Translations</strong> button.</p>";
        echo "<a href='/wp-admin/admin.php?page=tml-admin' class='button' style='margin-right:15px;'>" .  __('Go Back') . "</a>";
    } else {
        $snapshot = fetchFromCdn(get_option('tml_key') . '/' . $version['version'] . '/snapshot.json', ['decode' => true]);

        try {
            $data = file_get_contents(getCdnHost() . '/' . get_option('tml_key') . '/' . $snapshot['version'] . '.tar.gz');
            $version_path = get_option('tml_cache_path') . '/' . $snapshot['version'];
            $file_path = $version_path . ".tar.gz";
            $result = file_put_contents($file_path, $data);
        } catch (Exception $e) {
            $result = false;
        }

        if (!$result) {
            echo "<p>Failed to download and store release data. Please make sure that <strong>" . get_option('tml_cache_path') . "</strong> has write permissions.</p>";
        } else {
            echo "<p>Downloaded version <strong>" . $snapshot['version'] . "</strong> ($result bytes).</p>";
            echo "<p>Summary: " . $snapshot['metrics']['language_count'] . " languages, " . $snapshot['metrics']['translation_key_count'] . " phrases, " . $snapshot['metrics']['source_count'] . " sources </p>";
            echo "<p>Extracting content...</p>";
            try {
                $phar = new PharData($file_path);
                FileUtils::rrmdir($version_path);
                $phar->extractTo($version_path);
                unlink($file_path);
                $result = true;
            } catch (Exception $e) {
                $result = false;
            }

            if ($result) {
                update_option("tml_cache_version", $snapshot['version']);
                echo "<p>Snapshot has been extracted and is ready to be used.</p>";
            } else {
                echo "<p>Failed to extract snapshot. Please make sure that <strong>" . get_option('tml_cache_path') . "</strong> has write permissions and has enough space..</p>";
            }
        }

        echo "<a href='/wp-admin/admin.php?page=tml-admin' class='button' style='margin-right:15px;'>" .  __('Go Back') . "</a>";
    }
} catch (Exception $e) {
    echo "<p>We were unable to download the latest snapshot. Please ensure that you are using a correct access token, and you have a write permission to the cache folder.</p>";
    echo "<a href='/wp-admin/admin.php?page=tml-admin' class='button' style='margin-right:15px;'>" .  __('Go Back') . "</a>";
}