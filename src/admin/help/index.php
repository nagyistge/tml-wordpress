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

<div class="wrap" style="font-size:20px; padding:30px; text-align:center;">
    <img src="<?php echo plugins_url( 'translationexchange/assets/images/logo.png' ) ?>" style="width: 120px; margin: 20px;"><br>
<!--        <img src="--><?php //echo plugin_dir_url("assets/images/loading.png") ?><!--" style="vertical-align:bottom">-->
    Redirecting to documentation at <a href="http://welcome.translationexchange.com/docs/plugins/wordpress">http://welcome.translationexchange.com/docs/plugins/wordpress</a> ...
</div>

<script>
    window.setTimeout(function() {
        location.href = "http://welcome.translationexchange.com/docs/plugins/wordpress";
    }, 2000);
</script>

