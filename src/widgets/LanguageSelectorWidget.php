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

use tml\Session;

class LanguageSelectorWidget extends WP_Widget
{

    function __construct() {
        $widget_ops = array(
            'classname' => 'LanguageSelectorWidget',
            'description' => 'Displays current language and allows you to change languages.'
        );
        parent::__construct('LanguageSelectorWidget', '   Language Selector', $widget_ops);
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Change Language', 'text_domain');
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        $toggle_flag = !isset($instance['toggle_flag']) ? "true" : $instance['toggle_flag'];
        $toggle_label = !empty($instance['toggle_label']) ? $instance['toggle_label'] : __('Help Us Translate', 'text_domain');
        $powered_by_flag = !isset($instance['powered_by_flag']) ? "true" : $instance['powered_by_flag'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text"
                   style="margin-bottom: 10px;"
                   value="<?php echo esc_attr($title); ?>">

            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:'); ?></label>
            <select
                class="widefat"
                style="margin-bottom: 10px;"
                id="<?php echo $this->get_field_id('style'); ?>"
                name="<?php echo $this->get_field_name('style'); ?>">
                <option value="sideflags-left" <?php if ($style == 'sideflags-left') echo 'selected'; ?>>Side Flags on
                    the Left
                </option>
                <option value="sideflags-right" <?php if ($style == 'sideflags-right') echo 'selected'; ?>>Side Flags on
                    the Right
                </option>
                <option value="default" <?php if ($style == 'default') echo 'selected'; ?>>Lightbox</option>
                <option value="list" <?php if ($style == 'list') echo 'selected'; ?>>Language List</option>
                <option value="dropdown" <?php if ($style == 'dropdown') echo 'selected'; ?>>Dropdown List</option>
                <option value="popup" <?php if ($style == 'popup') echo 'selected'; ?>>Popup Window</option>
                <option value="flags" <?php if ($style == 'flags') echo 'selected'; ?>>Flags List</option>
                <!--                <option value="custom" --><?php //if ($style == 'custom') echo 'selected';
                ?><!-->
                Custom</option>-->
            </select>

            <input type="checkbox"
                <?php if ($toggle_flag == "true") echo 'checked'; ?>
                   value="true"
                   id="<?php echo $this->get_field_id('toggle_flag'); ?>"
                   name="<?php echo $this->get_field_name('toggle_flag'); ?>">
            <label
                for="<?php echo $this->get_field_id('toggle_flag'); ?>"><?php _e('Show Translation Toggle Link With Label:'); ?></label>

            <input class="widefat"
                   id="<?php echo $this->get_field_id('toggle_label'); ?>"
                   name="<?php echo $this->get_field_name('toggle_label'); ?>"
                   type="text"
                   style="margin-top: 5px; margin-bottom: 10px;"
                   value="<?php echo esc_attr($toggle_label); ?>">

            <input type="checkbox"
                <?php if ($powered_by_flag == "true") echo 'checked'; ?>
                   value="true"
                   id="<?php echo $this->get_field_id('powered_by_flag'); ?>"
                   name="<?php echo $this->get_field_name('powered_by_flag'); ?>">
            <label
                for="<?php echo $this->get_field_id('powered_by_flag'); ?>"><?php _e("Show 'Powered By Translation Exchange'"); ?></label>

        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['style'] = (!empty($new_instance['style'])) ? strip_tags($new_instance['style']) : '';
        $instance['toggle_flag'] = (!empty($new_instance['toggle_flag'])) ? strip_tags($new_instance['toggle_flag']) : 'false';
        $instance['toggle_label'] = (!empty($new_instance['toggle_label'])) ? strip_tags($new_instance['toggle_label']) : '';
        $instance['powered_by_flag'] = (!empty($new_instance['powered_by_flag'])) ? strip_tags($new_instance['powered_by_flag']) : 'false';
        return $instance;
    }

    function widget($args, $instance)
    { // widget sidebar output
        $title = !empty($instance['title']) ? $instance['title'] : __('Change Language', 'text_domain');
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        $toggle_flag = !isset($instance['toggle_flag']) ? "true" : $instance['toggle_flag'];
        $toggle_label = !empty($instance['toggle_label']) ? $instance['toggle_label'] : __('Help Us Translate', 'text_domain');
        $powered_by_flag = !isset($instance['powered_by_flag']) ? "true" : $instance['powered_by_flag'];

        if (get_option("tml_mode") == 'client') {
            ?>
            <aside id="meta-2" class="widget widget_meta masonry-brick" style="">
                <h4><?php echo $title; ?></h4>

                <div style="border:0px solid #ccc; margin-bottom:15px; margin-top:5px;">
                    <?php if ($style == 'sideflags-right') { ?>
                        <div data-tml-language-selector='sideflags' data-tml-side='right'
                    <?php } else if ($style == 'sideflags-left') { ?>
                        <div data-tml-language-selector='sideflags' data-tml-side='left'
                    <?php } else { ?>
                    <div data-tml-language-selector='<?php echo $style; ?>'
                        <?php } ?>
                            data-tml-powered-by='<?php echo ($powered_by_flag === 'true' ? 'true' : 'false'); ?>'
                            data-tml-toggle='<?php echo ($toggle_flag === 'true' ? 'true' : 'false'); ?>'
                            data-tml-toggle-label='<?php echo $toggle_label; ?>'
                        ></div>
                </div>
            </aside>
            <?php
        } else {
            if (!Session::instance()->isActive()) {
                echo '<h4>' . $title . '</h4>';
                echo '<div style="border:0px solid #ccc; margin-bottom:15px; font-size:13px;">';
                echo __("Language Selector is currently disabled.") . " " . __("Please verify that you have properly configured your application key and secret: ") . " ";
                echo '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=tml-admin">' . __("Tml Settings") . '</a>';
                echo "</div>";
                return;
            }

            $options = array("toggle" => $toggle_flag, "toggle_label" => $toggle_label, "powered_by" => $powered_by_flag);
            if ($style == 'sideflags-right') {
                $style = 'sideflags';
                $options['side'] = 'right';
            } else if ($style == 'sideflags-left') {
                $style = 'sideflags';
                $options['side'] = 'left';
            }
            ?>

            <?php if ($style == 'sideflags') { ?>
                <?php tml_language_selector_tag($style, $options); ?>
            <?php } else { ?>
                <aside id="meta-2" class="widget widget_meta masonry-brick" style="">
                    <h4><?php echo $title; ?></h4>

                    <div style="border:0px solid #ccc; margin-bottom:15px; margin-top:5px;">
                        <?php tml_language_selector_tag($style, $options); ?>
                    </div>
                </aside>
            <?php }
        }
//        echo $after_widget; // post-widget code from theme
    }
}
