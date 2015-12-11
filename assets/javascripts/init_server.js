if (TmlConfig.agent && TmlConfig.agent.type == 'agent') {

    (function () {
        var script = window.document.createElement('script');
        script.setAttribute('id', 'tml-agent');
        script.setAttribute('type', 'application/javascript');
        script.setAttribute('src', TmlConfig.agent.host);
        script.setAttribute('charset', 'UTF-8');
        script.onload = function () {
            Trex.init(TmlConfig.key, TmlConfig.agent);
            if (typeof(tml_on_ready) === 'function') tml_on_ready();
        };
        window.document.getElementsByTagName('head')[0].appendChild(script);
    })();

} else {

    (function() {
        if (window.tml_already_initialized) return;
        window.tml_already_initialized = true;

        var script = window.document.createElement('script');
        script.setAttribute('id', 'tml-tools');
        script.setAttribute('type', 'application/javascript');
        script.setAttribute('src', TmlConfig.tools.javascript);
        script.setAttribute('charset', 'UTF-8');
        script.onload = function() {
            Tml.Utils.insertCSS(window.document, TmlConfig.tools.stylesheet, false);
            Tml.Utils.insertCSS(window.document, TmlConfig.tools.css, true);

            Tml.app_key = TmlConfig.key;
            Tml.host = TmlConfig.tools.host;
            Tml.locale = TmlConfig.tools.locale;
            //Tml.current_source = TmlConfig.tools.source;
            if (TmlConfig.tools.shortcuts) {
                var shortcutFn = function (sc) {
                    return function () {
                        eval(TmlConfig.tools.shortcuts[sc]);
                    };
                };
                for (var sc in TmlConfig.tools.shortcuts) {
                    shortcut.add(sc, shortcutFn(sc));
                }
            }
            if (typeof(tml_on_ready) === 'function') tml_on_ready();
        };
        window.document.getElementsByTagName('head')[0].appendChild(script);
    })();

}