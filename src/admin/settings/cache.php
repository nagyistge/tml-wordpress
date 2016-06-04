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

?>

<h2>
    <?php echo __('Translation Cache Settings'); ?>
</h2>

<hr/>

<div style="padding-left:10px; color: #888">
    <?php echo(__("For better performance, your translations should be cached.")) ?>
    <a href="https://translationexchange.com/docs/plugins/wordpress"
       target="_new"><?php echo __('Click here to learn more about cache options.'); ?></a>
</div>

<style>
    .progress {
        width: 100%;
        height: 8px;
        border-radius: 3px;
    }

    .progress-wrap {
        background: #ddd;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        background: #f80;
        left: 0;
        position: absolute;
        top: 0;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    .progress-bar.approved {
        background: #0FCE89;
    }

    .progress-bar.translated {
        background: #11f59b;
    }

    .arrow-down {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #888;
        display: inline-block;
        cursor: pointer;
        margin-right: 2px;
    }

    .arrow-right {
        width: 0;
        height: 0;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 5px solid #888;
        display: inline-block;
        cursor: pointer;
        margin-right: 7px;
    }

    .metrics-title {
        border-right: 1px solid #ccc;
        padding: 3px;
        width: 20%;
        color: #888;
        text-align: center;
    }

    .metrics-title.last {
        border-right: 0px;
    }

    .metrics-value {
        border-right: 1px solid #ccc;
        padding: 3px;
        text-align: center;
        font-weight: bold;
    }

    .metrics-value.last {
        border-right: 0px;
    }

</style>

<form id="cache_form" method="post" action="">
    <input type="hidden" name="action" id="cache_action" value="download_cache">
    <input type="hidden" name="type" id="cache_type" value="">
    <input type="hidden" name="adapter" id="cache_adapter" value="">
    <input type="hidden" name="host" id="cache_host" value="">
    <input type="hidden" name="port" id="cache_port" value="">
    <input type="hidden" name="version" id="cache_version" value="">

    <?php
    $folders = array_reverse(scandir(get_option('tml_cache_path')));
    $snapshots = array();
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

        array_push($snapshots, $snapshot);
    }
    ?>

    <div style="margin-top: 10px; width: 100%">
        <div>
            <div
                style="display: inline-block; width:100px; padding:10px; vertical-align: top;"><?php echo __("Cache Options:") ?></div>
            <div style="display: inline-block; padding:10px; vertical-align: top;">

                <div style="border: 1px solid #ccc; width: 700px; margin-bottom: 10px;">
                    <div style="background:#fefefe; padding: 5px; ">
                        <div style="float:right; color:#888;">
                            <?php
                            if (get_option("tml_cache_type") == "none") {
                                echo "<strong>" . __("current") . "</strong>";
                            } else {
                                ?> <a href="#" onclick="useCache('none', '0')"
                                      style="text-decoration: none"><?php echo __("use") ?></a> <?php
                            }
                            ?>
                        </div>

                        <?php
                        if (get_option("tml_cache_version") == "0")
                            echo "<strong>";

                        echo __("Translation Exchange CDN");

                        if (get_option("tml_cache_version") == "0")
                            echo "</strong>";
                        ?>
                    </div>
                </div>

                <?php if (get_option("tml_mode") !== "client") { ?>
                    <div style="border: 1px solid #ccc; width: 700px; margin-bottom: 10px;">
                        <div style="background:#fefefe; padding: 5px; border-bottom: 1px solid #ccc;">
                            <div style="float:right; color:#888;">
                                <?php
                                if (get_option("tml_cache_type") == "dynamic") {
                                    echo "<strong>" . __("current") . "</strong>";
                                } else {
                                    ?> <a href="#" onclick="saveDynamicCache()"
                                          style="text-decoration: none"><?php echo __("use") ?></a> <?php
                                }
                                ?>
                            </div>

                            <?php
                            if (get_option("tml_cache_type") == "dynamic") echo "<strong>";

                            echo __("Dynamic cache");
                            echo "<span style='color: #888;'>";
                            echo " - ";
                            echo __("updated from CDN, shared across multiple Wordpress servers");
                            echo "</span>";

                            if (get_option("tml_cache_type") == "dynamic") echo "</strong>";
                            ?>
                        </div>

                        <div style="padding: 5px;">
                            <table style="width:100%; font-size:12px;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width: 80px;padding-left:5px;">
                                        <?php echo __("Type:") ?>
                                    </td>
                                    <td style="">
                                        <select id="tml_cache_adapter" style="width:580px;" disabled>
                                            <option
                                                value="memcached" <?php if (get_option("tml_cache_adapter") == "memcached") echo "selected"; ?>>
                                                Memcached
                                            </option>
                                            <option
                                                value="redis" <?php if (get_option("tml_cache_adapter") == "redis") echo "selected"; ?>>
                                                Redis
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 80px;padding-left:5px;">
                                        <?php echo __("Host:") ?>
                                    </td>
                                    <td style="">
                                        <input type="text" id="tml_cache_host" disabled
                                               value="<?php echo(get_option("tml_cache_host")) ?>"
                                               placeholder="localhost" style="width:580px">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 80px;padding-left:5px;">
                                        <?php echo __("Port:") ?>
                                    </td>
                                    <td style="">
                                        <input type="text" id="tml_cache_port" disabled
                                               value="<?php echo(get_option("tml_cache_port")) ?>" placeholder="11211"
                                               style="width:580px">
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="">
                                        <a href='#' id='tml_edit_dynamic_cache_button' class='button'
                                           style='margin-top:5px;'
                                           onClick="editDynamicCache()"><?php echo __('Edit Settings') ?></a>
                                        <a href='#' id='tml_reset_dynamic_cache_button' class='button'
                                           style='margin-top:5px;'
                                           onClick="syncDynamicCache()"><?php echo __('Update to Current Version') ?></a>
                                        <a href='#' id='tml_save_dynamic_cache_button' class='button'
                                           style='margin-top:5px;display:none;'
                                           onClick="saveDynamicCache()"><?php echo __('Save') ?></a>
                                        <a href='#' id='tml_cancel_dynamic_cache_button' class='button'
                                           style='margin-top:5px;display:none;'
                                           onClick="cancelDynamicCacheEdit()"><?php echo __('Cancel') ?></a>
                                    </td>
                                </tr>
                            </table>
                            <!-- ?php var_dump($snapshot['metrics']) ? -->
                        </div>
                    </div>
                <?php } ?>


                <?php if (count($snapshots) === 0) { ?>
                    <div style="color:#888">
                        <?php echo __("You have not downloaded any releases yet.") ?>
                        <?php echo __("Click on the download button to download the current release.") ?>
                    </div>
                <?php } ?>

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
                            'translated' => round($translated_keys / $total_keys * 100),
                            'approved' => round($approved_keys / $total_keys * 100)
                        );

                        $progress['total']['translated'] = $progress['total']['translated'] + $progress[$language['locale']]['translated'];
                        $progress['total']['approved'] = $progress['total']['approved'] + $progress[$language['locale']]['approved'];
                        $progress['total']['languages'] = $progress['total']['languages'] + 1;
                    }

                    $progress['total']['translated'] = round($progress['total']['translated']/$progress['total']['languages']);
                    $progress['total']['approved'] = round($progress['total']['approved']/$progress['total']['languages']);
                    ?>

                    <div style="border: 1px solid #ccc; width: 700px; margin-bottom: 10px;">
                        <div style="background:#fefefe; padding: 5px; border-bottom: 1px solid #ccc;">
                            <div style="float:right; color:#888;">
                                <?php
                                if (get_option("tml_cache_type") == 'local' && $snapshot['version'] === get_option("tml_cache_version")) {
                                    echo "<strong>" . __("current") . "</strong>";
                                } else {
                                    ?> <a href="#" onclick="useCache('local', '<?php echo $snapshot['version']; ?>')"
                                          style="text-decoration: none"><?php echo __("use") ?></a> <?php
                                }
                                ?>
                                <span style="color:#ccc;">|</span>
                                <a href="#" onclick="deleteCache('<?php echo $snapshot['version']; ?>')"
                                   style="text-decoration: none"><?php echo __("remove") ?></a>
                            </div>

                            <div class="arrow-right" id="snapshot-<?php echo $snapshot['version'] ?>-arrow"
                                 onclick="toggleSnapshot('<?php echo $snapshot['version'] ?>')"></div>

                            <?php
                            if (get_option("tml_cache_type") == 'local' && $snapshot['version'] === get_option("tml_cache_version"))
                                echo "<strong>";

                            echo("<a href='#' style='text-decoration: none' onclick=\"toggleSnapshot('" . $snapshot['version'] . "'); return false;\">");
                            echo __("Release generated at ") . $snapshot['created_at'];
                            echo("</a>");

                            if (get_option("tml_cache_type") == 'local' && $snapshot['version'] === get_option("tml_cache_version"))
                                echo "</strong>";
                            ?>
                        </div>
                        <div id="snapshot-<?php echo $snapshot['version'] ?>-details"
                             style="display: none; padding-top:5px;">
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
                                                    <strong><?php echo $progress[$language['locale']]['approved'] ?>%</strong> Completed &nbsp; |
                                                    &nbsp;
                                                    <strong><?php echo 100 - $progress[$language['locale']]['translated'] ?>%</strong>
                                                    Untranslated &nbsp; | &nbsp;
                                                    <strong><?php echo 100 - $progress[$language['locale']]['approved'] ?>%</strong> Pending
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

                    <?php
                }
                ?>
            </div>
        </div>
        <div>
            <div style="padding-left:140px; padding-top:20px;padding-bottom:40px;">
                <button class="button" onClick="return downloadSnapshot();">
                    <?php echo __('Download Latest Release') ?>
                </button>

                <?php if (get_option("tml_mode") == "client") { ?>
                    <button class="button" onClick="return resetBrowserCache();" style="margin-right:15px;">
                        <?php echo __('Reset Browser Cache') ?>
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>
</form>


<script>
    function toggleSnapshot(version) {
//        alert(jQuery('snapshot-' + version + '-arrow'));
        var arrow = jQuery('#snapshot-' + version + '-arrow');
        if (arrow.attr('class') == 'arrow-right') {
            arrow.removeClass('arrow-right');
            arrow.addClass('arrow-down');
            jQuery('#snapshot-' + version + '-details').show();
        } else {
            arrow.removeClass('arrow-down');
            arrow.addClass('arrow-right');
            jQuery('#snapshot-' + version + '-details').hide();
        }
    }

    function editDynamicCache() {
        document.getElementById("tml_cache_adapter").disabled = false;
        document.getElementById("tml_cache_host").disabled = false;
        document.getElementById("tml_cache_port").disabled = false;

        document.getElementById("tml_edit_dynamic_cache_button").style.display = 'none';
        document.getElementById("tml_reset_dynamic_cache_button").style.display = 'none';
        document.getElementById("tml_save_dynamic_cache_button").style.display = 'inline';
        document.getElementById("tml_cancel_dynamic_cache_button").style.display = 'inline';
    }

    function saveDynamicCache() {
        cancelDynamicCacheEdit();
        var select = document.getElementById("tml_cache_adapter");
        jQuery("#cache_action").val("use_cache");
        jQuery("#cache_type").val('dynamic');
        jQuery("#cache_adapter").val(select.options[select.selectedIndex].value);
        jQuery("#cache_host").val(document.getElementById("tml_cache_host").value);
        jQuery("#cache_port").val(document.getElementById("tml_cache_port").value);
        document.getElementById("cache_form").submit();
    }

    function cancelDynamicCacheEdit() {
        document.getElementById("tml_cache_adapter").disabled = true;
        document.getElementById("tml_cache_host").disabled = true;
        document.getElementById("tml_cache_port").disabled = true;

        document.getElementById("tml_edit_dynamic_cache_button").style.display = 'inline';
        document.getElementById("tml_reset_dynamic_cache_button").style.display = 'inline';
        document.getElementById("tml_save_dynamic_cache_button").style.display = 'none';
        document.getElementById("tml_cancel_dynamic_cache_button").style.display = 'none';
    }

    function resetBrowserCache() {
        if (!confirm("<?php echo __("Are you sure you want to reset browser cache?") ?>"))
            return false;

        var cache = window.localStorage;
        for (var key in cache) {
            if (key.match(/^tml_/)) cache.removeItem(key);
        }
        window.location.reload();
        return false;
    }

    function downloadSnapshot() {
        if (!confirm("<?php echo __("Are you sure you want to download the latest snapshot from Translation Exchange?") ?>"))
            return false;
        document.getElementById("cache_form").submit();
        return true;
    }

    function deleteCache(version) {
        if (!confirm("<?php echo __("Are you sure you want to remove this cache version?") ?>"))
            return false;

        jQuery("#cache_action").val("delete_cache");
        jQuery("#cache_version").val(version);
        document.getElementById("cache_form").submit();
    }

    function useCache(type, version) {
        jQuery("#cache_action").val("use_cache");
        jQuery("#cache_type").val(type);
        jQuery("#cache_version").val(version);
        document.getElementById("cache_form").submit();
    }

    function syncDynamicCache() {
        if (!confirm("<?php echo __("Are you sure you want to update your cache version to the current release version from Translation Exchange?") ?>"))
            return false;

        jQuery("#cache_action").val("sync_cache");
        document.getElementById("cache_form").submit();
    }

    function showScriptOptions() {
        document.getElementById("tml_script_host").style.display = 'inline-block';
        document.getElementById("tml_script_options").style.display = 'inline-block';
        document.getElementById("tml_script_options_button").style.display = 'none';
    }

    function showAgentOptions() {
        document.getElementById("tml_host").style.display = 'inline-block';
        document.getElementById("tml_agent_host").style.display = 'inline-block';
        document.getElementById("tml_agent_options").style.display = 'inline-block';
        document.getElementById("tml_agent_options_button").style.display = 'none';
    }

</script>