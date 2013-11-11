<?php

#--
# Copyright (c) 2010-2013 Michael Berkovich, tr8nhub.com
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

class LanguageSelectorWidget extends WP_Widget {
    function LanguageSelectorWidget() {
        $widget_ops = array(
            'classname' => 'LanguageSelectorWidget',
            'description' => 'Displays current language and allows you to change the language.'
        );
        $this->WP_Widget(
            'LanguageSelectorWidget',
            'Tr8n Language Selector',
            $widget_ops
        );
    }

    function widget($args, $instance) { // widget sidebar output
        if (\Tr8n\Config::instance()->isDisabled()) {
            echo '<div class="page-title widget-title">Languages</div>';
            echo '<div style="border:0px solid #ccc; margin-bottom:15px; font-size:13px;">';
            echo __("Tr8n was not able to initialize your application.") . " " . __("Please verify that you have properly configured your application key and secret: ") . " ";
            echo '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=tr8n-admin">' . __("Tr8n Settings") . '</a>';
            echo "</div>";
            return;
        }

        extract($args, EXTR_SKIP);
//        echo $before_widget; // pre-widget code from theme

        $langs = "<ul>";
        foreach(\Tr8n\Config::instance()->application->languages as $language) {
            $bold = (\Tr8n\Config::instance()->current_language->locale == $language->locale);
            $langs .= "<li>";
            $langs .= "<a href='#' onClick='Tr8n.UI.LanguageSelector.change(\"" . $language->locale . "\");'>";
            $langs .= "<img src='" . $language->flagUrl() . "' style='margin-right:3px;'>";
            $langs .= $language->name;
            $langs .= "</a></li>";
        }
        $langs .= "</ul>";

        $translator_options = "";
        if (\Tr8n\Config::instance()->current_translator) {
            $translator_options .= "<div style='margin-top:10px;margin-bottom:15px;font-size:10px;'>";
            $translator_options .= "<a href='#' onClick='Tr8n.UI.LanguageSelector.toggleInlineTranslations();'>Toggle inline translations</a>";
            $translator_options .= "</div>";
        }

        print <<<EOM
        <aside id="meta-2" class="widget widget_meta masonry-brick" style="">
        <div>Languages <span style='font-size:10px;color:#21759b;cursor:pointer;' onClick='Tr8n.UI.LanguageSelector.show(true);'>open</span></div>
        <div style="border:0px solid #ccc; margin-bottom:15px;">
            $langs

            $translator_options
        </div>
        </aside>
EOM;
//        echo $after_widget; // post-widget code from theme
    }
}
