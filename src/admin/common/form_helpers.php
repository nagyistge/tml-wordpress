<?php

function help_tag($text)
{
    ?>

    <div class="help" style="display: inline-block; margin-left: 10px;">
        ?
        <div class="tooltip">
            <?php echo $text ?>
        </div>
    </div>

    <?php
}

function text_area_tag($name, $value, $options = array())
{
    ?>

    <textarea
        name="<?php echo $name ?>"
        placeholder="<?php echo isset($options['placeholder']) ? $options['placeholder'] : '' ?>"
        style="<?php echo isset($options['style']) ? $options['style'] : '' ?>"
        ><?php echo $value ?></textarea>

    <?php
}

function text_field_tag($name, $value, $options = array())
{
    ?>

    <input
        type="text"
        name="<?php echo $name ?>"
        value="<?php echo $value ?>"
        placeholder="<?php echo isset($options['placeholder']) ? $options['placeholder'] : '' ?>"
        style="<?php echo isset($options['style']) ? $options['style'] : '' ?>"
        >

    <?php
}

function radio_button_tag($name, $value, $options = array())
{

    $disabled = isset($options['disabled']) && $options['disabled'];
    $checked = isset($options['checked']) && $options['checked'];
    ?>

    <?php if (isset($options['label'])) { ?>
        <label title="<?php echo $options['label'] ?>">
    <?php } ?>

    <input
        type="radio"
        name="<?php echo $name ?>"
        value="<?php echo $value ?>"
        <?php echo ($checked & !$disabled) ? 'checked' : '' ?>
        <?php echo $disabled ? 'disabled' : '' ?>
        >

    <?php if (isset($options['label'])) { ?>
        <?php echo $options['label'] ?></label>
    <?php } ?>

    <?php
}

function check_box_tag($name, $value = "true", $options = array())
{
    ?>

    <input
        type="checkbox"
        name="<?php echo $name ?>"
        value="<?php echo $value ?>"
        <?php echo isset($options['checked']) && $options['checked'] ? 'checked' : '' ?>
        <?php echo isset($options['disabled']) && $options['disabled'] ? 'disabled' : '' ?>
        >

    <?php
}

function span_tag($text, $style = "")
{
    ?>

    <span style="<?php echo $style ?>"><?php echo $text ?></span>
    <?php
}