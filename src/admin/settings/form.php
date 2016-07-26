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

include dirname(__FILE__) . "/../common/form_helpers.php";
include dirname(__FILE__) . "/configuration.php";

?>

<link rel='stylesheet' href='<?php echo plugin_dir_url(__FILE__) . "../../../assets/css/styles.css" ?>' type='text/css'
      media='all'/>

<div class="wrap">
    <h2>
        <img src="<?php echo plugin_dir_url(__FILE__) . "../../../assets/images/logo.png" ?>"
             style="width: 40px; vertical-align:middle; margin: 0px 5px;">
        <?php echo __('Translation Exchange Configuration Options'); ?>
    </h2>

    <hr/>

    <h2>
        <?php echo __('Basic Settings'); ?>
    </h2>
    <hr/>

    <p style="padding-left:10px; color:#888;">
        To get your project key, please visit your
        <a href="https://dashboard.translationexchange.com" target="_new" style="text-decoration: none">
            Translation Exchange Dashboard
        </a> and choose <strong>Integration Section</strong> from the navigation menu.
    </p>

    <form name="configuration_form" method="post" action="">
        <input type="hidden" name="<?php echo $cache_field_name; ?>" id="<?php echo $cache_field_name; ?>" value="N">
        <input type="hidden" name="<?php echo $submit_field_name; ?>" id="<?php echo $submit_field_name; ?>" value="Y">

        <table style="margin-top: 10px; width: 100%">
            <?php foreach ($field_sets as $field_set) { ?>
                <?php foreach ($field_set as $key => $field) {

                    if ($key == 'separator') {
                        echo "<tr><td colspan=\"3\"><hr></td></tr>";
                        continue;
                    }

                    $type = (!isset($field['type']) ? 'text' : $field['type']);
                    $style = (!isset($field['style']) ? '' : $field['style']);
                    ?>

                    <tr style="<?= $style ?>" id="<?= $key ?>">
                        <td style="padding-left: 10px; width: 150px; vertical-align: top;">
                            <?php echo($field["title"]) ?>
                        </td>

                        <td style="">
                            <?php

                            if ($type == 'text') {

                                text_field_tag($key, $field["value"], [
                                    'placeholder' => $field["default"],
                                    'style' => "width:100%;"
                                ]);

                            } elseif ($type == 'textarea') {

                                text_area_tag($key, stripcslashes($field["value"]), [
                                    'placeholder' => $field["default"],
                                    'style' => "width:100%; height: 200px;"
                                ]);

                            } elseif ($type == 'radio' && isset($field["options"])) {
                                foreach ($field["options"] as $option) {
                                    radio_button_tag($key, $option["value"], [
                                        'checked' => ($field["value"] == $option["value"]),
                                        'disabled' => (isset($option["disabled"]) && $option["disabled"]),
                                        'label' => $option["title"]
                                    ]);

                                    if (isset($option['help'])) {
                                        help_tag($option['help']);
                                    }

                                    echo($field['separator']);
                                }
                            } elseif ($type == 'checkbox') {
                                $value = $field["value"];

                                check_box_tag($key, "true", $value == "true");

                                if (isset($field['notes'])) {
                                    span_tag($field['notes'], "padding-left:15px;color:#666;");
                                }
                            } ?>
                        </td>
                        <td style="vertical-align: top">
                            <?php if (isset($field['help'])) {
                                help_tag($field['help']);
                            } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td colspan="3"><hr></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div style="float:right;">
                        <?php if (get_option("tml_mode") == "client") { ?>
                            <span style="padding-top:5px;" id="tml_script_options_button">
                                <a href="#" class="button"
                                   onclick="showScriptOptions();"><?php echo __('Show Advanced Options') ?></a>
                            </span>
                        <?php } else { ?>
                            <span style="padding-top:5px;" id="tml_agent_options_button">
                                <a href="#" class="button"
                                   onclick="showAgentOptions();"><?php echo __('Show Advanced Options') ?></a>
                            </span>
                        <?php } ?>

                        <?php if (get_option('tml_key') !== null) { ?>
                            <a class="button"
                               href='https://dashboard.translationexchange.com/#/projects/<?php echo get_option('tml_key') ?>'
                               target="_new">
                                <?php echo __('Visit Dashboard') ?>
                            </a>

                            <a class="button" href='https://translate.translationexchange.com'>
                                <?php echo __('Visit Translation Center') ?>
                            </a>
                        <?php } ?>
                    </div>
                    <button class="button-primary">
                        <?php echo __('Save Changes') ?>
                    </button>
                </td>
            </tr>
        </table>
    </form>

    <?php if (get_option("tml_key") !== '') { ?>

        <?php include_once dirname(__FILE__) . "/cache/settings.php" ?>

        <div style="color: #888">
            <a href="/wp-admin/widgets.php" style="text-decoration: none" target="_new">
                <?php echo __("Don't forget to configure the <strong>Language Selector widget</strong> under Appearance > Widgets."); ?>
            </a>
        </div>

    <?php } ?>
</div>
