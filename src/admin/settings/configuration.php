<?php

$submit_field_name = 'tml_submit_hidden';
$cache_field_name = 'tml_update_cache_hidden';

$application_fields = array(
    'tml_key' => array(
        "title" => __('Project Key:'),
        "value" => get_option('tml_key'),
        "default" => __("Paste your application key here"),
        "help" => __("Project key uniquely identifies your application. Please visit the project integration instructions under your dashboard to get the key.")
    ),

//        'tml_token' => array("title" => __('Access Token:'), "value" => get_option('tml_token'), "default" => "Paste your application token here"),

    'tml_mode' => array(
        "title" => __('SDK Mode:'),
        "value" => get_option('tml_mode'),
        "default" => "",
        "type" => "radio",
        "separator" => "&nbsp;&nbsp;&nbsp;&nbsp;",
        "options" => array(
            array(
                "title" => __('Translate in the browser'),
                "value" => "client",
                "help" => __("This option uses a client-side JavaScript library that performs translations in the user's browser after the page is loaded. This approach reduces the load on the server, but can affect the SEO.")
            ),
            array(
                "title" => __('Translate on the server'),
                "value" => "server_automated",
                "help" => __("This option uses a server-side PHP library that performs translations on the server. It is great for SEO, but adds more load to the server.")
            )
        )),

    'separator' => true,

    'tml_locale_selector' => array(
        "title" => __('Language Detection:'),
        "value" => get_option('tml_locale_selector'),
        "default" => "param",
        "type" => "radio",
        "separator" => "<div style='height: 5px;'></div>",
        "options" => array(
            array(
                "title" => __('Use query parameter. For example, "?locale=en". Not recommended, not SEO friendly.'),
                "value" => "param"
            ),
            array(
                "title" => __('Use pre-path element. For example, adds /en/ in front of URL. SEO friendly.'),
                "value" => "pre-path",
                "disabled" => is_permalink_structure_a_query()
            ),
            array(
                "title" => __('Use domain prefix. For example, http://en.yoursite.com. Additional DNS sub-domain configuration is required.'),
                "value" => "pre-domain"
            )
        ))
);

$script_fields = array(
    'tml_script_host' => array(
        "title" => __('Script Host:'),
        "value" => get_option('tml_script_host'),
        "type" => "text",
        "default" => "https://cdn.translationexchange.com/tools/tml/stable/tml.min.js",
        "style" => "display:none",
        "help" => __("This is an advanced option. Paste an alternative URL for the tml.js script here. You can provide a specific version of the script based on the script release version.")
    ),
    'tml_script_options' => array(
        "title" => __('Options:'),
        "value" => get_option('tml_script_options'),
        "type" => "textarea",
        "default" => __('Provide custom script options in JSON format'),
        "style" => "display:none",
        "help" => __("This is an advanced option. Provide any additional custom options for the initialization instructions of the TML agent.")
    ),
);

$agent_fields = array(
    'tml_host' => array(
        "title" => __('Service Host:'),
        "value" => get_option('tml_host'),
        "default" => "https://api.translationexchange.com",
        "style" => "display:none",
        "help" => __("This is an advanced option. Provide the URL for the Translation Exchange API server.")
    ),

    'tml_agent_host' => array(
        "title" => __('Agent Host:'),
        "value" => get_option('tml_agent_host'),
        "type" => "text",
        "default" => "https://tools.translationexchange.com/agent/stable/agent.min.js",
        "style" => "display:none",
        "help" => __("This is an advanced option. Provide the URL for the Translation Exchange agent. You can use a specific version based on the agent release.")
    ),

    'tml_agent_options' => array(
        "title" => __('Options:'),
        "value" => get_option('tml_agent_options'),
        "type" => "textarea",
        "default" => __('Provide custom agent options in JSON format'),
        "style" => "display:none",
        "help" => __("This is an advanced option. Provide any additional custom options for the initialization instructions of the TML agent.")
    ),
);

$field_sets = array($application_fields, $script_fields, $agent_fields);

if (isset($_POST[$submit_field_name]) && $_POST[$submit_field_name] == 'Y') {

    $index = 0;
    foreach ($field_sets as $set) {
        foreach ($set as $key => $attributes) {
            if ($key == 'separator') continue;
            update_option($key, $_POST[$key]);
            $field_sets[$index][$key] = array_merge($attributes, array("value" => $_POST[$key]));
        }
        $index++;
    }

    if (get_option("tml_mode") == "client" && get_option("tml_cache_type") == "dynamic")
        update_option("tml_cache_type", "none");
    ?>

    <div class="updated"><p><strong><?php _e('Settings have been saved.'); ?></strong></p></div>
    <?php
}