if (TmlConfig && TmlConfig.token) {
    var options = {
        host: TmlConfig.host,
        element: document.body,
        cdn: false
    };

    if (TmlConfig.cache) {
        options.cache = TmlConfig.cache;
    }

    tml.init(TmlConfig.token, options);
}