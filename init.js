if (TmlConfig) {
    tml.init(TmlConfig.token, {
        host: TmlConfig.host,
        element: document.body,
        version: TmlConfig.version,
        language_selector: {
            element: "languages",
            style: "dropdown"
        },
        cdn: false
    });
}