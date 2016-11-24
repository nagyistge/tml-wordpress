<?php

/**
 * @param $message
 */
function tml_log($message) {
    if ((defined('WP_DEBUG') && true === WP_DEBUG)) {
        if (is_array($message))
            $message = json_encode($message);

        error_log($message);
    }
}