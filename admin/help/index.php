<?php

use tml\Config;

if (Config::instance()->isEnabled()) {
    ?>
    <div class="wrap" style="font-size:14px;padding:30px;">
        <img src="<?php echo Config::instance()->application->host ?>/assets/tml/tml_logo.png"><br><br>
        <img src="<?php echo Config::instance()->application->host ?>/assets/tml/spinner.gif" style="vertical-align:bottom">
        Redirecting to Translation Exchange Documentation at <a href="http://translationexchange.com/docs">http://translationexchange.com/docs</a> ...
    </div>

    <script>
        window.setTimeout(function() {
            location.href = "http://translationexchange.com/docs";
        }, 2000);
    </script>
<?php
}
?>


