if (TmlConfig && TmlConfig.key) {
    (function () {
        var script = window.document.createElement('script');
        script.setAttribute('id', 'tml-agent');
        script.setAttribute('type', 'application/javascript');
        script.setAttribute('src', TmlConfig.agent.host);
        script.setAttribute('charset', 'UTF-8');
        script.onload = function () {
            Trex.init(TmlConfig.key, TmlConfig.agent);
        };
        window.document.getElementsByTagName('head')[0].appendChild(script);
    })();
}