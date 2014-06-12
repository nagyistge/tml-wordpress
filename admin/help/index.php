<?php

if (\Tr8n\Config::instance()->isEnabled()) {
    ?>
    <div class="wrap" style="font-size:14px;padding:30px;">
        <img src="<?php echo \Tr8n\Config::instance()->application->host ?>/assets/tr8n/tr8n_logo.png"><br><br>
        <img src="<?php echo \Tr8n\Config::instance()->application->host ?>/assets/tr8n/spinner.gif" style="vertical-align:bottom">
        Redirecting to Tr8n Wiki at <a href="http://wiki.tr8nhub.com/index.php?title=Wordpress_Plugin">http://wiki.tr8nhub.com</a> ...
    </div>

    <script>
        window.setTimeout(function() {
            location.href = "http://wiki.tr8nhub.com/index.php?title=Tr8n_WordPress_Plugin";
        }, 2000);
    </script>
<?php
}

?>


