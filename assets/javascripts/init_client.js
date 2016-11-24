if (TmlConfig && TmlConfig.key) {
    var options = {
        host:               TmlConfig.host,
        key:                TmlConfig.key,
        translate_title:    true,
        translate_body:     true,
        debug:              false,
        locale:             TmlConfig.locale,
        translator_options: {
            ignore_elements: ['#wpadminbar', '#querylist', '.sd-content', '.blog-post-meta', '.comment-content']
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