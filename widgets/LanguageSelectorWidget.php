<?php

#--
# Copyright (c) 2015 Translation Exchange
#
# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#++

use tml\Config;

class LanguageSelectorWidget extends WP_Widget {
    function LanguageSelectorWidget() {
        $widget_ops = array(
            'classname' => 'LanguageSelectorWidget',
            'description' => 'Displays current language and allows you to change languages.'
        );
        $this->WP_Widget(
            'LanguageSelectorWidget',
            '   Language Selector',
            $widget_ops
        );
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Change Language', 'text_domain' );
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'dropdown';
        $toggle_flag = ! isset( $instance['toggle_flag'] ) ? "true" : $instance['toggle_flag'];
        $toggle_label = ! empty( $instance['toggle_label'] ) ? $instance['toggle_label'] : __( 'Help Us Translate', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>"
                   type="text"
                   style="margin-bottom: 10px;"
                   value="<?php echo esc_attr( $title ); ?>">

            <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style:' ); ?></label>
            <select
                class="widefat"
                style="margin-bottom: 10px;"
                id="<?php echo $this->get_field_id( 'style' ); ?>"
                name="<?php echo $this->get_field_name( 'style' ); ?>">
                <option value="list" <?php if ($style == 'list') echo 'selected'; ?>>List</option>
                <option value="dropdown" <?php if ($style == 'dropdown') echo 'selected'; ?>>Dropdown</option>
                <option value="popup" <?php if ($style == 'popup') echo 'selected'; ?>>Popup</option>
                <option value="flags" <?php if ($style == 'flags') echo 'selected'; ?>>Flags</option>
                <option value="custom" <?php if ($style == 'custom') echo 'selected'; ?>>Custom</option>
            </select>

            <input type="checkbox"
                   <?php if ($toggle_flag == "true") echo 'checked'; ?>
                   value="true"
                   id="<?php echo $this->get_field_id( 'toggle_flag' ); ?>"
                   name="<?php echo $this->get_field_name( 'toggle_flag' ); ?>">
            <label for="<?php echo $this->get_field_id( 'toggle_flag' ); ?>"><?php _e( 'Show Translation Toggle Link With Label:' ); ?></label>

            <input class="widefat"
                   id="<?php echo $this->get_field_id( 'toggle_label' ); ?>"
                   name="<?php echo $this->get_field_name( 'toggle_label' ); ?>"
                   type="text"
                   style="margin-top: 5px; margin-bottom: 10px;"
                   value="<?php echo esc_attr( $toggle_label ); ?>">
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';
        $instance['toggle_flag'] = ( ! empty( $new_instance['toggle_flag'] ) ) ? strip_tags( $new_instance['toggle_flag'] ) : 'false';
        $instance['toggle_label'] = ( ! empty( $new_instance['toggle_label'] ) ) ? strip_tags( $new_instance['toggle_label'] ) : '';
        return $instance;
    }

    function widget($args, $instance) { // widget sidebar output
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Change Language', 'text_domain' );
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'dropdown';
        $toggle_flag = ! isset( $instance['toggle_flag'] ) ? "true" : $instance['toggle_flag'];
        $toggle_label = ! empty( $instance['toggle_label'] ) ? $instance['toggle_label'] : __( 'Help Us Translate', 'text_domain' );

        if (get_option("tml_mode") == 'client') {
            ?>
                <aside id="meta-2" class="widget widget_meta masonry-brick" style="">
                <h3><?php echo $title; ?></h3>
                <div style="border:0px solid #ccc; margin-bottom:15px; margin-top:5px;">
                    <div data-tml-language-selector='<?php echo $style; ?>'
                         <?php if ($toggle_flag == "true") { ?>
                            data-tml-toggle='<?php echo $toggle_flag; ?>'
                            data-tml-toggle-label='<?php echo $toggle_label; ?>'
                         <?php } ?>
                    ></div>
                </div>
                </aside>
        <?php
           return;
        }

        if (Config::instance()->isDisabled()) {
            echo '<h3>' . $title . '</h3>';
            echo '<div style="border:0px solid #ccc; margin-bottom:15px; font-size:13px;">';
            echo __("Language Selector is currently disabled.") . " " . __("Please verify that you have properly configured your application key and secret: ") . " ";
            echo '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=tml-admin">' . __("Tml Settings") . '</a>';
            echo "</div>";
            return;
        }

        ?>

        <aside id="meta-2" class="widget widget_meta masonry-brick" style="">
        <h3><?php echo $title; ?></h3>
        <div style="border:0px solid #ccc; margin-bottom:15px; margin-top:5px;">
            <?php tml_language_selector_tag($style, array("toggle" => $toggle_flag, "toggle_label" => $toggle_label)); ?>
        </div>
        </aside>

<?php
//        echo $after_widget; // post-widget code from theme
    }
}
