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
    <?php echo __( 'Translation Cache Settings' ); ?>
</h2>

<hr />

<div style="padding-left:10px; color: #888">
    <?php echo(__("For better performance, your translations should be cached.")) ?>
    <a href="https://translationexchange.com/docs/plugins/wordpress" target="_new">Click here</a> to learn more about cache options.
</div>

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
        array_push($snapshots, $snapshot);
    }
    ?>

    <div style="margin-top: 10px; width: 100%">
        <div>
            <div style="display: inline-block; width:100px; padding:10px; vertical-align: top;"><?php echo __("Cache Options:") ?></div>
            <div style="display: inline-block; padding:10px; vertical-align: top;">

                <div style="border: 1px solid #ccc; width: 700px; margin-bottom: 10px;">
                    <div style="background:#fefefe; padding: 5px; ">
                        <div style="float:right; color:#888;">
                            <?php
                            if (get_option("tml_cache_type") == "none") {
                                echo "<strong>" . __("current") . "</strong>";
                            } else {
                                ?> <a href="#" onclick="useCache('none', '0')" style="text-decoration: none"><?php echo __("use") ?></a> <?php
                            }
                            ?>
                        </div>

                        <?php
                        if (get_option("tml_cache_version") == "0")
                            echo "<strong>";

                        echo __("No cache");
                        echo "<span style='color: #888;'>";
                        echo " - ";
                        echo __("data is requested directly from Translation Exchange");
                        echo "</span>";

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
                                    ?> <a href="#" onclick="saveDynamicCache()" style="text-decoration: none"><?php echo __("use") ?></a> <?php
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
                                            <option value="memcached" <?php if (get_option("tml_cache_adapter") == "memcached") echo "selected"; ?>>Memcached</option>
                                            <option value="redis"    <?php if (get_option("tml_cache_adapter") == "redis") echo "selected"; ?>>Redis</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 80px;padding-left:5px;">
                                        <?php echo __("Host:") ?>
                                    </td>
                                    <td style="">
                                        <input type="text" id="tml_cache_host" disabled value="<?php echo(get_option("tml_cache_host")) ?>" placeholder="localhost"  style="width:580px">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 80px;padding-left:5px;">
                                        <?php echo __("Port:") ?>
                                    </td>
                                    <td style="">
                                        <input type="text" id="tml_cache_port" disabled value="<?php echo(get_option("tml_cache_port")) ?>" placeholder="11211"  style="width:580px">
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="">
                                        <a href='#' id='tml_edit_dynamic_cache_button' class='button' style='margin-top:5px;' onClick="editDynamicCache()"><?php echo __('Edit Settings') ?></a>
                                        <a href='#' id='tml_reset_dynamic_cache_button' class='button' style='margin-top:5px;' onClick="syncDynamicCache()"><?php echo __('Update to Current Version') ?></a>
                                        <a href='#' id='tml_save_dynamic_cache_button' class='button' style='margin-top:5px;display:none;' onClick="saveDynamicCache()"><?php echo __('Save') ?></a>
                                        <a href='#' id='tml_cancel_dynamic_cache_button' class='button' style='margin-top:5px;display:none;' onClick="cancelDynamicCacheEdit()"><?php echo __('Cancel') ?></a>
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
                    ?>
                    <div style="border: 1px solid #ccc; width: 700px; margin-bottom: 10px;">
                        <div style="background:#fefefe; padding: 5px; border-bottom: 1px solid #ccc;">
                            <div style="float:right; color:#888;">
                                <?php
                                if (get_option("tml_cache_type") == 'local' && $snapshot['version'] === get_option("tml_cache_version")) {
                                    echo "<strong>" . __("current") . "</strong>";
                                } else {
                                    ?> <a href="#" onclick="useCache('local', '<?php echo $snapshot['version']; ?>')" style="text-decoration: none"><?php echo __("use") ?></a> <?php
                                }
                                ?>
                                <span style="color:#ccc;">|</span>
                                <a href="#" onclick="deleteCache('<?php echo $snapshot['version']; ?>')" style="text-decoration: none"><?php echo __("remove") ?></a>
                            </div>

                            <?php
                            if (get_option("tml_cache_type") == 'local' &&  $snapshot['version'] === get_option("tml_cache_version"))
                                echo "<strong>";

                            echo __("Local cache");
                            echo "<span style='color: #888;'>";
                            echo " - ";
                            echo __("release generated on: ") . $snapshot['created_at'];
                            echo "</span>";

                            if (get_option("tml_cache_type") == 'local' &&  $snapshot['version'] === get_option("tml_cache_version"))
                                echo "</strong>";
                            ?>
                        </div>
                        <div style="padding: 5px;">
                            <table style="width:100%; font-size:12px;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="border-right: 1px solid #ccc; padding:3px; width: 20%; color: #888;"><?php echo __("Languages") ?></td>
                                    <td style="border-right: 1px solid #ccc; padding:3px; width: 20%; color: #888;"><?php echo __("Phrases") ?></td>
                                    <td style="border-right: 1px solid #ccc; padding:3px; width: 20%; color: #888;"><?php echo __("Translations") ?></td>
                                    <td style="border-right: 1px solid #ccc; padding:3px; width: 20%; color: #888;"><?php echo __("Translated") ?></td>
                                    <td style="padding:3px; width: 20%; color: #888;"><?php echo __("Approved") ?></td>
                                </tr>
                                <tr>
                                    <td style="border-right: 1px solid #ccc; padding:3px;"><?php echo $snapshot['metrics']['language_count']; ?></td>
                                    <td style="border-right: 1px solid #ccc; padding:3px;"><?php echo $snapshot['metrics']['key_count']; ?></td>
                                    <td style="border-right: 1px solid #ccc; padding:3px;"><?php echo $snapshot['metrics']['translation_count']; ?></td>
                                    <td style="border-right: 1px solid #ccc; padding:3px;"><?php echo $snapshot['metrics']['percent_translated']; ?>%</td>
                                    <td style="padding:3px;"><?php echo $snapshot['metrics']['percent_locked']; ?>%</td>
                                </tr>
                            </table>
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
                    <?php echo __('Download Current Release') ?>
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
        for (var key in cache){
            if(key.match(/^tml_/)) cache.removeItem(key);
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

    function showAdvancedOptions() {
        document.getElementById("tml_script_host").style.display = 'inline-block';
        document.getElementById("tml_script_options").style.display = 'inline-block';
        document.getElementById("tml_script_options_button").style.display = 'none';
    }
</script>