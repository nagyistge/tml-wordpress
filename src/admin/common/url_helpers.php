<?php

function is_permalink_structure_a_query(){
    $permalink_structure = get_option('permalink_structure');
    if (empty($permalink_structure)) return true;
    if (strpos($permalink_structure, '?')!==false) return true;
    return strpos($permalink_structure, 'index.php')!==false;
}

function getCdnHost()
{
    $cdn_host = "https://cdn.translationexchange.com";

    $agent_options = stripcslashes(get_option('tml_agent_options'));

    if ($agent_options === '') {
        return $cdn_host;
    }

    $custom_host = null;
    try {
        $data = json_decode($agent_options, true);
        $custom_host = isset($data['cdn_host']) ? $data['cdn_host'] : null;
    } catch (Exception $e) {
        $custom_host = null;
    }

    if ($custom_host != null)
        return $custom_host;

    return $cdn_host;
}

function fetchFromCdn($path, $opts = array())
{
    try {
        $curl_handle = curl_init();

        if (substr( $path, 0, 1 ) != '/')
            $path = '/' . $path;

        $url = getCdnHost() . $path;
//        echo "Fetching from " . $url;

        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl_handle);
        curl_close($curl_handle);

        if (isset($opts['decode']) && $opts['decode'])
            $data = json_decode($data, true);
    } catch (Exception $e) {
        $data = false;
    }

    return $data;
}
