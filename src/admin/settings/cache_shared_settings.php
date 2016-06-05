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

?>

<div style="border: 1px solid #ccc; width: 840px; margin-bottom: 10px;">
    <div style="background:#fefefe; padding: 5px; border-bottom: 1px solid #ccc;">
        <div style="float:right; color:#888;">
            <?php if (get_option("tml_cache_type") == "dynamic") {
                echo "<strong>" . __("current") . "</strong>";
            } else { ?>
                <a href="#" onclick="saveDynamicCache()" style="text-decoration: none"><?php echo __("use") ?></a>
            <?php } ?>

            <div class="help" style="display: inline-block; margin-left: 10px;">
                ?
                <div class="tooltip">
                    Shared cache is used to accommodate multiple installations of WordPress. It automatically synchronizes with
                    the releases published to Translation Exchange CDN from your project dashboard.
                </div>
            </div>
        </div>

        <?php if (get_option("tml_cache_type") == "dynamic") { ?>
            <strong>
                <?php echo __("Shared cache") ?>
            </strong>
        <?php } else { ?>
            <?php echo __("Shared cache") ?>
        <?php } ?>
    </div>

    <div style="padding: 5px;">
        <table style="width:100%; font-size:12px;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 140px;padding-left:5px;">
                    <?php echo __("Type:") ?>
                </td>
                <td style="">
                    <select id="tml_cache_adapter" style="width:620px;" disabled>
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
                <td style="padding-left: 10px; width: 40px;">
                    <div class="help">
                        ?
                        <div class="tooltip">Shared cache server type. If you have multiple WordPress servers, they all
                            can share the same cache.
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 80px;padding-left:5px;">
                    <?php echo __("Host:") ?>
                </td>
                <td style="">
                    <input type="text" id="tml_cache_host" disabled value="<?php echo(get_option("tml_cache_host")) ?>"
                           placeholder="localhost" style="width:620px">
                </td>
                <td style="padding-left: 10px;">
                    <div class="help">
                        ?
                        <div class="tooltip">URL of where the server is hosted. The URL must be accessible by the server
                            where the WordPress is running.
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 80px;padding-left:5px;">
                    <?php echo __("Port:") ?>
                </td>
                <td style="">
                    <input type="text" id="tml_cache_port" disabled value="<?php echo(get_option("tml_cache_port")) ?>"
                           placeholder="11211" style="width:620px">
                </td>
                <td style="padding-left: 10px;">
                    <div class="help">
                        ?
                        <div class="tooltip">Port of the server.</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 80px;padding-left:5px;">
                    <?php echo __("Namespace:") ?>
                </td>
                <td style="">
                    <input type="text" id="tml_cache_namespace" disabled value="<?php echo(get_option("tml_cache_namespace", "wordpress")) ?>"
                           placeholder="11211" style="width:620px">
                </td>
                <td style="padding-left: 10px;">
                    <div class="help">
                        ?
                        <div class="tooltip">Namespace for translations within the shared cache.</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 80px;padding-left:5px;">
                    <?php echo __("Refresh Interval:") ?>
                </td>
                <td style="">
                    <input type="text" id="tml_cache_version_check_interval" disabled
                           value="<?php echo(get_option("tml_cache_version_check_interval", '3600')) ?>"
                           placeholder="Interval in seconds" style="width:620px">
                </td>
                <td style="padding-left: 10px;">
                    <div class="help">
                        ?
                        <div class="tooltip">Time interval (in seconds) for how often the server should check for the
                            latest release on Translation Exchange CDN.
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding: 5px 0;">
                    <a href='#' id='tml_edit_dynamic_cache_button' class='button' style='margin-top:5px;'
                       onClick="editDynamicCache(); return false;"><?php echo __('Edit Settings') ?></a>
                    <a href='#' id='tml_reset_dynamic_cache_button' class='button' style='margin-top:5px;'
                       onClick="syncDynamicCache(); return false;"><?php echo __('Update to Current Version') ?></a>
                    <a href='#' id='tml_save_dynamic_cache_button' class='button' style='margin-top:5px;display:none;'
                       onClick="saveDynamicCache(); return false;"><?php echo __('Save') ?></a>
                    <a href='#' id='tml_cancel_dynamic_cache_button' class='button' style='margin-top:5px;display:none;'
                       onClick="cancelDynamicCacheEdit(); return false;"><?php echo __('Cancel') ?></a>
                </td>
            </tr>
        </table>
        <!-- ?php var_dump($snapshot['metrics']) ? -->
    </div>
</div>