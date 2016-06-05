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
    <div style="background:#fefefe; padding: 5px; ">
        <div style="float:right; color:#888;">
            <?php if (get_option("tml_cache_type") == "none") {
                echo "<strong>" . __("current") . "</strong>";
            } else { ?>
                <a href="#" onclick="useCache('none', '0')"
                   style="text-decoration: none"><?php echo __("use") ?></a>
            <?php } ?>

            <div class="help" style="display: inline-block; margin-left: 10px;">
                ?
                <div class="tooltip">
                    When user visits your WordPress pages, the translation cache will be loaded directly from Translation Exchange CDN and
                    stored in the user's browser for better performance.
                </div>
            </div>
        </div>

        <?php if (get_option("tml_cache_version") == "0")
            echo "<strong>" . __("Translation Exchange CDN") . "</strong>";
        else
            echo __("Translation Exchange CDN");
        ?>
    </div>
</div>