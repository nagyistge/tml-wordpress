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

<div id="tml-translation-center-loading" style="text-align:center; padding-top: 100px; font-size: 20px;">
    <img src="<?php echo plugins_url( 'translationexchange/assets/images/logo.png' ) ?>" style="width: 120px; margin: 20px;">
    <br>
    Initializing Translation Center....
</div>

<iframe id="tml-translation-center" src="http://localhost:3002/login?app_token=<?php echo get_option('tml_token')?>"
        style="display:none; margin-left:-20px; width: calc(100% + 20px); height: 400px;"
        onload="showTranslationCenter()"></iframe>

<script>
    function showTranslationCenter() {
        jQuery("#tml-translation-center-loading").hide();
        jQuery("#tml-translation-center").show();
    }

    function resizeIframe() {
        jQuery("#tml-translation-center").css('height',window.innerHeight - 100);
    }
    resizeIframe();
    jQuery(window).on('resize',resizeIframe )
</script>