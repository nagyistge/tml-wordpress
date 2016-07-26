<?php

function tml_log($message) {
    if (is_array($message))
        $message = json_encode($message);

    error_log($message);
}