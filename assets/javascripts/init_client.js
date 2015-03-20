if (TmlConfig && TmlConfig.token) {
    tml.init(TmlConfig.token, {
        host: TmlConfig.host,
        element: document.body,
        version: TmlConfig.version || 1,
        cdn: false
    });
}