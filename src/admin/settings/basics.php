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

?>

<div class="wrap">
    <h2>
        <img src="<?php echo plugin_dir_url(__FILE__) . "../../../assets/images/logo.png" ?>"
             style="width: 40px; vertical-align:middle; margin: 0px 5px;">
        <?php echo __('Translation Exchange Settings'); ?>
    </h2>

    <hr/>

    <p style="padding-left:10px; color:#888;">
        To get your project key, please visit your <a href="https://dashboard.translationexchange.com">Translation
            Exchange Dashboard</a> and choose <strong>Integration Section</strong> from the navigation menu.
    </p>

    <form name="form1" method="post" action="">
        <input type="hidden" name="<?php echo $cache_field_name; ?>" id="<?php echo $cache_field_name; ?>" value="N">
        <input type="hidden" name="<?php echo $submit_field_name; ?>" id="<?php echo $submit_field_name; ?>" value="Y">

        <div style="margin-top: 10px; width: 100%">
            <?php foreach ($field_sets as $field_set) { ?>
                <?php foreach ($field_set as $key => $field) { ?>
                    <?php $type = (!isset($field['type']) ? 'text' : $field['type']); ?>
                    <?php $style = (!isset($field['style']) ? '' : $field['style']); ?>
                    <div style="<?php echo($style) ?>" id="<?php echo($key) ?>">
                        <div
                            style="display:inline-block; width:100px; padding:5px 15px; vertical-align: top;"><?php echo($field["title"]) ?></div>
                        <div style="display:inline-block; padding:2px 15px;">
                            <?php if ($type == 'text') { ?>
                                <input type="text" name="<?php echo($key) ?>" value="<?php echo($field["value"]) ?>"
                                       placeholder="<?php echo($field["default"]) ?>" style="width:700px">
                            <?php } elseif ($type == 'textarea') { ?>
                                <textarea style="width:700px; height: 200px;" name="<?php echo($key) ?>"
                                          placeholder="<?php echo($field["default"]) ?>"><?php echo(stripcslashes($field["value"])) ?></textarea>
                            <?php } elseif ($type == 'radio' && isset($field["options"])) { ?>
                                <?php foreach ($field["options"] as $option) { ?>
                                    <input type="radio" name="<?php echo($key) ?>"
                                           value="<?php echo($option["value"]) ?>" <?php if ($field["value"] == $option["value"]) echo("checked"); ?> >
                                    <?php echo($option["title"]) ?>
                                    &nbsp;&nbsp;
                                <?php } ?>
                            <?php } elseif ($type == 'checkbox') { ?>
                                <?php
                                $value = $field["value"];
                                ?>
                                <input type="checkbox" name="<?php echo($key) ?>"
                                       value="true" <?php if ($value == "true") echo("checked"); ?> >
                                <?php if (isset($field['notes'])) { ?>
                                    <span style="padding-left:15px;color:#666;"><?php echo $field['notes'] ?></span>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <hr/>
            <div style="padding-left: 150px; padding-top:10px;padding-bottom:40px;">
                <div style="width:700px;">
                    <div style="float:right;">
                        <?php if (get_option("tml_mode") == "client") { ?>
                            <span style="padding-top:5px;" id="tml_script_options_button">
                                <a href="#" class="button" onclick="showScriptOptions();"><?php echo __('Show Advanced Options') ?></a>
                            </span>
                        <?php } else { ?>
                            <span style="padding-top:5px;" id="tml_agent_options_button">
                                <a href="#" class="button" onclick="showAgentOptions();"><?php echo __('Show Advanced Options') ?></a>
                            </span>
                        <?php } ?>

                        <a class="button" href='https://dashboard.translationexchange.com'>
                            <?php echo __('Visit Dashboard') ?>
                        </a>

                        <a class="button" href='https://translate.translationexchange.com'>
                            <?php echo __('Visit Translation Center') ?>
                        </a>
                    </div>
                </div>

                <button class="button-primary">
                    <?php echo __('Save Changes') ?>
                </button>
            </div>
        </div>
    </form>

    <?php if (get_option("tml_key") != "" && get_option("tml_token") != "") { ?>

        <?php include_once dirname(__FILE__) . "/cache.php" ?>

        <div style="color: #888">
            <?php echo __("Don't forget to configure the <strong>Language Selector widget</strong> under Appearance > Widgets."); ?>
        </div>

    <?php } ?>
</div>