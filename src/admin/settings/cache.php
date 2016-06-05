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
        <?php echo __('Translation Cache Options'); ?>
    </h2>

    <hr/>

    <div style="padding-left:10px; color: #888">
        <?php echo(__("For best performance, your translations should always be cached.")) ?>
        <a href="http://docs.translationexchange.com/wordpress"
           target="_new"
           style="text-decoration: none"><?php echo __('Click here to learn more about cache options.'); ?></a>
    </div>

    <form id="cache_form" method="post" action="">
        <input type="hidden" name="action" id="cache_action" value="download_cache">
        <input type="hidden" name="type" id="cache_type" value="">
        <input type="hidden" name="adapter" id="cache_adapter" value="">
        <input type="hidden" name="host" id="cache_host" value="">
        <input type="hidden" name="port" id="cache_port" value="">
        <input type="hidden" name="namespace" id="cache_namespace" value="">
        <input type="hidden" name="version_check_interval" id="cache_version_check_interval" value="">
        <input type="hidden" name="version" id="cache_version" value="">

        <div style="display: inline-block; padding:10px; vertical-align: top;">
            <?php if (get_option("tml_mode") === "client") { // client-side only cache ?>
                <?php include_once dirname(__FILE__)."/cache_local_cdn.php" ?>
            <?php } else {  // server-side only cache ?>
                <?php include_once dirname(__FILE__)."/cache_shared_settings.php" ?>
            <?php } ?>

            <?php include_once dirname(__FILE__)."/cache_snapshots.php" ?>
        </div>
    </form>

<?php include_once dirname(__FILE__)."/cache_scripts.php" ?>