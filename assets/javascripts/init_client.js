if (TmlConfig && TmlConfig.key && TmlConfig.token) {
    var options = {
        host:               TmlConfig.host,
        key:                TmlConfig.key,
        token:              TmlConfig.token,
        translate_title:    true,
        translate_body:     true,
        debug:              false,
        translator_options: {
            ignore_elements: ['#wpadminbar', '.sd-content', '.blog-post-meta']
        },
        agent: {
            enabled:    true,
            type:       'tools',
            version:    'stable'
        }
    };

    if (TmlConfig.cache) {
        options.cache = TmlConfig.cache;
    }

    if (TmlConfig.advanced && TmlConfig.advanced != '') {
        try {
            var json = TmlConfig.advanced.replace(/\\"/g, '"').replace("\\r\\n", "");
            options = tml.utils.merge(options, JSON.parse(json));
        } catch(e) {
            console.log("tml: Failed to parse advanced options", e);
        }
    }

    tml.init(options);
}