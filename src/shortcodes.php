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

use Tml\Config;
use Tml\Logger;
use Tml\TmlException;


/**
 * Shortcode for translating a label
 *
 * [tml:tr]Hello World[/tml:tr]
 *
 * @param $atts
 * @param null $content
 * @return mixed|null
 * @throws Exception
 */
function tml_shortcode_tr($atts, $content = null) {
    if (Config::instance()->isDisabled()) {
        return $content;
    }

    if ($content == null) return $content;

    $label = trim($content);
    $atts = tml_prepare_tokens_and_options($atts);

//    \Tml\Logger::instance()->info("translating: \"" . $content . "\"", $tokens);

    try {
        return tr($label, $atts["description"], $atts["tokens"], $atts["options"]);
    } catch(TmlException $e) {
        Logger::instance()->info($e->getMessage());
        return $content;
    }
}
add_shortcode('tml:tr', 'tml_shortcode_tr', 2);

/**
 * Shortcode for translating an HTML block
 *
 * [tml:trh] <strong>Hello World</strong>  [/tml:trh]
 *
 * @param $attrs
 * @param null $content
 * @return array
 */
function tml_shortcode_trh($attrs, $content = null) {
    $attrs = tml_prepare_tokens_and_options($attrs);
    return tml_tranlsate_html($content, $attrs["description"], $attrs["tokens"], $attrs["options"]);
}
add_shortcode('tml:trh', 'tml_shortcode_trh', 2);

/**
 * Shortcode for a source block
 *
 * @param $atts
 * @param null $content
 * @return null
 */
function tml_shortcode_block($atts, $content = null) {
    if (Config::instance()->isDisabled()) {
        return do_shortcode($content);
    }
    $options = array();
    if (isset($atts['source'])) {
        $options['source'] = $atts['source'];
    }
    if (isset($atts['locale'])) {
        $options['locale'] = $atts['locale'];
    }
    Config::instance()->beginBlockWithOptions($options);
    $content = do_shortcode($content);
    Config::instance()->finishBlockWithOptions();
    return $content;
}
add_shortcode('tml:block', 'tml_shortcode_block', 2);
