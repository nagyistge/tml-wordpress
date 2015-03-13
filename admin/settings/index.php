<?php

use tml\Cache;
use tml\Config;

if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$submit_field_name = 'tml_submit_hidden';
$cache_field_name = 'tml_update_cache_hidden';

$application_fields = array(
    'tml_host' => array("title" => __('Host:'), "value" => get_option('tml_host'), "default" => "https://api.translationexchange.com"),
    'tml_token' => array("title" => __('Token:'), "value" => get_option('tml_token'), "default" => ""),
);

$translation_fields = array(
    'tml_translate_html' => array("title" => __('Automatic Translations:'), "value" => get_option('tml_translate_html'), "type" => "checkbox", "notes" => __('If enabled, the content will be automatically converted to TML and translated. Otherwise you should use tml:tr, tml:trh and tml:block tags to indicate translation keys and source blocks.')),
    'tml_translate_wordpress' => array("title" => __('Translate Wordpress:'), "value" => get_option('tml_translate_wordpress'), "type" => "checkbox", "notes" => __('(Beta) If enabled, the Wordpress text itself will be registered as TML and translated using Tml.')),
);

if (isset($_POST[ $submit_field_name ]) && $_POST[ $submit_field_name ] == 'Y') {
    foreach($application_fields as $key => $attributes) {
        update_option( $key, $_POST[ $key ] );
        $application_fields[$key] = array_merge($attributes, array("value" => $_POST[$key]));
    }
    foreach($translation_fields as $key => $attributes) {
        $value = isset($_POST[ $key ]) ? "true" : "false";
        update_option( $key, $value);
        $translation_fields[$key] = array_merge($attributes, array("value" => $value));
    }
    ?>
    <div class="updated"><p><strong><?php _e('Settings have been saved.'); ?></strong></p></div>
<?php } else if (isset($_POST[ $cache_field_name ]) && $_POST[ $cache_field_name ] == 'Y') {
    Config::instance()->incrementCache();
?>
    <div class="updated"><p><strong><?php _e('Cache has been updated.'); ?></strong></p></div>
<?php
}

//$field_sets = array($application_fields, $translation_fields);
$field_sets = array($application_fields);

?>

<div class="wrap">
    <?php echo "<h2>" . __( 'Translation Exchange Project Settings' ) . "</h2>"; ?>
    <form name="form1" method="post" action="">
        <input type="hidden" name="<?php echo $cache_field_name; ?>" id="<?php echo $cache_field_name; ?>" value="N">
        <input type="hidden" name="<?php echo $submit_field_name; ?>" id="<?php echo $submit_field_name; ?>" value="Y">

        <?php foreach($field_sets as $field_set) { ?>
        <table>
            <?php foreach($field_set as $key => $field) { ?>
                <?php $type = (!isset($field['type']) ? 'text' : $field['type']); ?>
                <tr>
                    <td style="width:100px;"><?php echo($field["title"]) ?></td>
                    <td>
                        <?php if ($type == 'text') {  ?>
                            <input type="text" name="<?php echo($key) ?>" value="<?php echo($field["value"]) ?>" placeholder="<?php echo($field["default"]) ?>"  size="80">
                        <?php } else if ($type == 'checkbox') { ?>
                            <?php
                                $value = $field["value"];
                            ?>
                            <input type="checkbox" name="<?php echo($key) ?>" value="true" <?php if ($value == "true") echo("checked"); ?> >
                            <?php if (isset($field['notes'])) { ?>
                                 <span style="padding-left:15px;color:#666;"><?php echo $field['notes'] ?></span>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <hr />
        <?php } ?>

        <p class="submit">
            <button class="button-primary" style="margin-right:15px;">
                <?php echo __('Save Changes') ?>
            </button>

            <?php if (!Cache::instance()->isReadOnly()) { ?>
                <button class="button" onClick="return updateCache();">
                    <?php echo __('Update Cache') ?>
                </button>
            <?php } ?>
        </p>

    </form>
</div>

<script>
    function updateCache() {
        if (!confirm("<?php echo __("Resetting cache will re-download all latest translations from the tml service. Are you sure you want to proceed?") ?>"))
            return false;

        document.getElementById("<?php echo $cache_field_name; ?>").value = "Y";
        document.getElementById("<?php echo $submit_field_name; ?>").value = "N";
        return true;
    }
</script>

