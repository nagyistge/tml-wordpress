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

use Tml\Session;

/**
 * Filter for capturing article title
 *
 * @param $title
 * @return array|mixed
 * @throws Exception
 */
function tml_title_filter($title) {
    $title = do_shortcode($title);

    if (is_admin()) return $title;
    if (get_option('tml_mode') == "server_automated") {

        if ($title != strip_tags($title)) {
            $title = tml_tranlsate_html($title);
        } else {
            $title = tr($title);
        }

        return $title;
    }

    return $title;
}
add_filter('wp_title', 'tml_title_filter', 10, 2);
add_filter('the_title', 'tml_title_filter', 10, 2);
add_filter('category_description', 'tml_title_filter', 0);

// function tml_wp_title_filter($title, $id) {
//     return do_shortcode($title);
// }
// add_filter('wp_title', 'tml_wp_title_filter', 10, 2);

/**
 * Filter for capturing content
 *
 * @param $content
 * @return array
 */
function tml_the_content_filter($content) {
    if (is_admin()) return $content;
    if (get_option('tml_mode') == "server_automated") {
        if (strstr($content, 'tml:manual') !== false)
            return $content;

//        $content = "<div data-tml-source='" . addslashes($GLOBALS['post']->post_name) . "'>" . $content ."</div>";

        $content = do_shortcode($content);

//        if ($GLOBALS['post']->post_name == 'debug') {
//            return var_export($GLOBALS['post'], TRUE );
//        }

        return tml_tranlsate_html($content);
    }
    // Logger::instance()->debug($content);
    return $content;
}
add_filter('the_content', 'tml_the_content_filter');

/**
 * Filter for capturing widget text
 *
 * @param $content
 * @return mixed
 */
function tml_widget_text_filter($content) {
    return do_shortcode($content);
}
add_filter('widget_text', 'tml_widget_text_filter');

/**
 * Filter for capturing excerpt
 *
 * @param $content
 * @return mixed
 */
function tml_the_excerpt_filter($content) {
//    \Tml\Logger::instance()->debug($content);
    return $content;
}
add_filter('the_excerpt', 'tml_the_excerpt_filter');

/**
 * Filter for capturing comments
 *
 * @param $content
 * @return array
 */
function tml_comment_text_filter($content) {
    if (is_admin()) return $content;

    if (get_option('tml_mode') == "server_automated") {
        return tml_tranlsate_html($content);
    }
//    \Tml\Logger::instance()->debug($content);
    return $content;
}
add_filter('comment_text ', 'tml_comment_text_filter');

/**
 * Creates link to Settings
 *
 * @param $links
 * @param $file
 * @return mixed
 */
function tml_plugin_action_links_filter($links, $file) {
    if (preg_match('/tml/', $file)) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=tml-admin">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'tml_plugin_action_links_filter', 10, 2);

/**
 * Change labels from default to tml translated
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function tml_translate_fields_filter( $translated_text, $text, $domain ) {
    if (is_admin()) return $translated_text;

    if (!Session::instance()->isActive()) {
        return $translated_text;
    }

    if (get_option('tml_mode') == "server_automated") {
//        foreach(array('%s', 'http://', '%1', '%2', '%3', '%4', '&#', '%d', '&gt;') as $token) {
//            if (strpos($text, $token) !== FALSE) return $translated_text;
//        }
        return trl($text, null, array(), array("source" => "wordpress"));
    }
    return $translated_text;
}
add_filter( 'gettext', 'tml_translate_fields_filter', 20, 3 );
//add_filter( 'gettext_with_context', 'tml_translate_fields_filter',0);
//add_filter( 'ngettext', 'tml_translate_fields_filter',0);


function tml_home_url($url, $path, $orig_scheme, $blog_id)
{
    global $url_helper;
    return $url_helper->toHomeUrl($path);
}
add_filter('home_url', 'tml_home_url', 0, 4);


//function tml_bloginfo_url($url, $path = nil, $orig_scheme = nil, $blog_id = nil)
//{
//    tml_log("blog info url: " . $url);
//    return $url;
//}
//add_filter('bloginfo_url', 'tml_bloginfo_url',10,2);

//function tml_link_url($url)
//{
//    tml_log("author info url: " . $url);
//    return $url;
//}
//add_filter('author_feed_link', 'tml_link_url');
//add_filter('author_link', 'tml_link_url');
//add_filter('day_link', 'tml_link_url');
//add_filter('get_comment_author_url_link', 'tml_link_url');
//add_filter('month_link', 'tml_link_url');
//add_filter('page_link', 'tml_link_url');
//add_filter('post_link', 'tml_link_url');
//add_filter('year_link', 'tml_link_url');
//add_filter('category_feed_link', 'tml_link_url');
//add_filter('category_link', 'tml_link_url');
//add_filter('tag_link', 'tml_link_url');
//add_filter('term_link', 'tml_link_url');
//add_filter('the_permalink', 'tml_link_url');
//add_filter('feed_link', 'tml_link_url');
//add_filter('post_comments_feed_link', 'tml_link_url');
//add_filter('tag_feed_link', 'tml_link_url');
