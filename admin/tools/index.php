<?php

use tml\Config;

if (Config::instance()->isEnabled()) {
    ?>
        <div class="wrap" style="font-size:14px;padding:30px;">
            <img src="<?php echo Config::instance()->application->host ?>/assets/tml/tml_logo.png"><br><br>
            <img src="<?php echo Config::instance()->application->host ?>/assets/tml/spinner.gif" style="vertical-align:bottom">
            Redirecting to Tml service at <a href="<?php echo Config::instance()->application->host ?>"><?php echo Config::instance()->application->host ?></a> ...
        </div>

        <script>
            window.setTimeout(function() {
                location.href = "<?php echo Config::instance()->application->host ?>/tml/app/phrases/index";
            }, 2000);
        </script>
    <?php
}

?>


