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

try {
    $current_release_version = fetchFromCdn(get_option('tml_key') . "/version.json", ['decode' => true]);
} catch (Exception $ex) {
    $current_release_version = null;
}

$folders = array_reverse(scandir(get_option('tml_cache_path')));
$snapshots = array();
$has_new_release = ($current_release_version != null);

foreach ($folders as $folder) {
    $path = get_option('tml_cache_path') . "/" . $folder;
    if (!is_dir($path)) continue;
    if ($folder == '.' || $folder == '..') continue;

    $data = file_get_contents($path . "/snapshot.json");
    $snapshot = json_decode($data, true);
    $snapshot['path'] = $path;

    $data = file_get_contents($path . "/application.json");
    $snapshot['application'] = json_decode($data, true);

    $data = file_get_contents($path . "/sources.json");
    $snapshot['sources'] = json_decode($data, true);

    if ($current_release_version != null && $current_release_version['version'] == $snapshot['version'])
        $has_new_release = false;

    array_push($snapshots, $snapshot);
}

if (count($snapshots) > 0) { ?>
    <?php
    foreach ($snapshots as $snapshot) {
        $progress = array(
            'total' => array(
                'languages' => 0,
                'translated' => 0,
                'approved' => 0
            )
        );

        foreach ($snapshot['application']['languages'] as $language) {
            if ($language['locale'] == $snapshot['application']['default_locale'])
                continue;


            $total_keys = $snapshot['metrics']['translation_key_count'];
            $translated_keys = $snapshot['metrics']['languages'][$language['locale']]['translated_key_count'];
            $approved_keys = $snapshot['metrics']['languages'][$language['locale']]['approved_key_count'];

            $progress[$language['locale']] = array(
                'translated' => $total_keys > 0 ? round($translated_keys / $total_keys * 100) : 0,
                'approved' => $total_keys > 0 ? round($approved_keys / $total_keys * 100) : 0
            );

            $progress['total']['translated'] = $progress['total']['translated'] + $progress[$language['locale']]['translated'];
            $progress['total']['approved'] = $progress['total']['approved'] + $progress[$language['locale']]['approved'];
            $progress['total']['languages'] = $progress['total']['languages'] + 1;
        }

        $progress['total']['translated'] = round($progress['total']['translated'] / $progress['total']['languages']);
        $progress['total']['approved'] = round($progress['total']['approved'] / $progress['total']['languages']);
        ?>

        <div style="border: 1px solid #ccc; width: 100%; margin-bottom: 10px;">
            <div style="background:#fefefe; padding: 5px;">
                <div style="float:right; color:#888;">
                    <?php
                    if (get_option("tml_cache_type") == 'local' && $snapshot['version'] === get_option("tml_cache_version")) {
                        echo "<strong>" . __("current") . "</strong>";
                    } else {
                        ?> <a href="#" onclick="useCache('local', '<?php echo $snapshot['version']; ?>'); return false;"
                              style="text-decoration: none"><?php echo __("use") ?></a> <?php
                    }
                    ?>
                    <span style="color:#ccc;">|</span>
                    <a href="#" onclick="deleteCache('<?php echo $snapshot['version']; ?>'); return false;"
                       style="text-decoration: none"><?php echo __("remove") ?></a>

                    <div class="help" style="display: inline-block; margin-left: 10px;">
                        ?
                        <div class="tooltip">This release has been downloaded from the Translation Exchange CDN and
                            installed locally. When WordPress pages are loaded
                            the cache will be served from local files.
                        </div>
                    </div>
                </div>

                <div class="arrow-right" id="snapshot-<?php echo $snapshot['version'] ?>-arrow"
                     onclick="toggleSnapshot('<?php echo $snapshot['version'] ?>'); return false;">
                </div>

                <?php
                if (get_option("tml_cache_type") == 'local' && $snapshot['version'] === get_option("tml_cache_version"))
                    echo "<strong>";
                $date = new DateTime($snapshot['created_at']);
                ?>

                <a href='#' style='text-decoration: none'
                   onclick="toggleSnapshot('<?php echo $snapshot['version'] ?>'); return false;">
                    <?php echo __("Release ") . $snapshot['version'] ?>
                </a>
                &nbsp;&nbsp;
                <span style='font-size: 10px; color: #888'>
                    <?php echo $date->format('F d, Y') . __(" at ") . $date->format('H:i:s') ?>
                </span>

                <?php
                if (get_option("tml_cache_type") == 'local' && $snapshot['version'] === get_option("tml_cache_version"))
                    echo "</strong>";
                ?>
            </div>

            <div id="snapshot-<?php echo $snapshot['version'] ?>-details"
                 style="display: none; padding:10px; border-top: 1px solid #ccc;">
                <table style="width:100%; font-size:12px;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="metrics-title"><?php echo __("Phrases") ?></td>
                        <td class="metrics-title"><?php echo __("Sources") ?></td>
                        <td class="metrics-title"><?php echo __("Languages") ?></td>
                        <td class="metrics-title"><?php echo __("Translated") ?></td>
                        <td class="metrics-title last"><?php echo __("Approved") ?></td>
                    </tr>
                    <tr>
                        <td class="metrics-value"><?php echo $snapshot['metrics']['translation_key_count']; ?></td>
                        <td class="metrics-value"><?php echo $snapshot['metrics']['source_count']; ?></td>
                        <td class="metrics-value"><?php echo $progress['total']['languages']; ?></td>
                        <td class="metrics-value">
                            <?php echo $progress['total']['translated']; ?>%
                        </td>
                        <td class="metrics-value last">
                            <?php echo $progress['total']['approved']; ?>%
                        </td>
                    </tr>
                </table>
                <hr>
                <?php
                foreach ($snapshot['application']['languages'] as $language) {
                    if ($language['locale'] == $snapshot['application']['default_locale'])
                        continue;
                    ?>
                    <div style="padding: 3px;">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 25px; vertical-align: top;">
                                    <img src='<?php echo $language['flag_url']; ?>'>
                                </td>
                                <td style="width: 100px; vertical-align: top;">
                                    <?php echo $language['english_name']; ?>
                                    <div
                                        style="color: #888; font-size: 11px;"><?php echo $language['native_name']; ?></div>
                                </td>
                                <td style="vertical-align: top; padding-top: 6px;">
                                    <div class="progress-wrap progress">
                                        <div class="progress-bar progress translated"
                                             style="width: <?php echo $progress[$language['locale']]['translated'] ?>%"></div>
                                        <div class="progress-bar progress approved"
                                             style="width: <?php echo $progress[$language['locale']]['approved'] ?>%"></div>
                                    </div>
                                    <div
                                        style="color: #888; font-size: 11px; padding-top: 5px; text-align: center;">
                                        <strong>
                                            <?php echo $progress[$language['locale']]['approved'] ?>%
                                        </strong> Completed &nbsp; |
                                        &nbsp;
                                        <strong>
                                            <?php echo 100 - $progress[$language['locale']]['translated'] ?>%
                                        </strong>
                                        Untranslated &nbsp; | &nbsp;
                                        <strong>
                                            <?php echo 100 - $progress[$language['locale']]['approved'] ?>%
                                        </strong> Pending
                                        Approval &nbsp;
                                    </div>
                                </td>
                                <td style="vertical-align: top; width: 60px; text-align: right; font-weight: bold;">
                                    <?php echo $progress[$language['locale']]['translated'] ?>%
                                </td>
                            </tr>
                            <?php if (end($snapshot['application']['languages']) != $language) { ?>
                                <tr>
                                    <td colspan="5" style="height: 1px; border-bottom: 1px solid #ccc"></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php } ?>
                <!-- ?php var_dump($snapshot['metrics']) ? -->
            </div>
        </div>

    <?php } ?>

<?php } ?>

<div style="padding-top:5px;padding-bottom:40px;">
    <?php if (get_option("tml_mode") == "client") { ?>
        <div style="float:right">
            <button class="button" onClick="return resetBrowserCache();">
                <?php echo __('Reset Your Browser Cache') ?>
            </button>
        </div>
    <?php } ?>

    <?php if ($has_new_release) { ?>
        <button class="button" onClick="return downloadSnapshot();">
            <?php echo __('<strong>New Release is Available</strong> - Download & Install Locally') ?>
        </button>
    <?php } ?>
</div>
