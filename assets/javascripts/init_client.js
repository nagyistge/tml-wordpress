if (TmlConfig && TmlConfig.token) {
    var options = {
        host:   TmlConfig.host,
        key:    TmlConfig.key,
        token:  TmlConfig.token,
        translateBody: true,
        agent: {
            enabled: true,
            type: 'tools',
            version: 'stable'
        }
    };

    if (TmlConfig.cache) {
        options.cache = TmlConfig.cache;
    }

    tml.init(options);
}