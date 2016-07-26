<?php

function help_tag($text)
{
    ?>

    <div class="help" style="display: inline-block; margin-left: 10px;">
        ?
        <div class="tooltip">
            <?= $text ?>
        </div>
    </div>

    <?php
}

function text_area_tag($name, $value, $options = [])
{
    ?>

    <textarea
        name="<?= $name ?>"
        placeholder="<?= isset($options['placeholder']) ? $options['placeholder'] : '' ?>"
        style="<?= isset($options['style']) ? $options['style'] : '' ?>"
        ><?= $value ?></textarea>

    <?php
}

function text_field_tag($name, $value, $options = [])
{
    ?>

    <input
        type="text"
        name="<?= $name ?>"
        value="<?= $value ?>"
        placeholder="<?= isset($options['placeholder']) ? $options['placeholder'] : '' ?>"
        style="<?= isset($options['style']) ? $options['style'] : '' ?>"
        >

    <?php
}

function radio_button_tag($name, $value, $options = [])
{

    $disabled = isset($options['disabled']) && $options['disabled'];
    $checked = isset($options['checked']) && $options['checked'];
    ?>

    <?php if (isset($options['label'])) { ?>
        <label title="<?= $options['label'] ?>">
    <?php } ?>

    <input
        type="radio"
        name="<?= $name ?>"
        value="<?= $value ?>"
        <?= ($checked & !$disabled) ? 'checked' : '' ?>
        <?= $disabled ? 'disabled' : '' ?>
        >

    <?php if (isset($options['label'])) { ?>
        <?= $options['label'] ?></label>
    <?php } ?>

    <?php
}

function check_box_tag($name, $value = "true", $options = [])
{
    ?>

    <input
        type="checkbox"
        name="<?= $name ?>"
        value="<?= $value ?>"
        <?= isset($options['checked']) && $options['checked'] ? 'checked' : '' ?>
        <?= isset($options['disabled']) && $options['disabled'] ? 'disabled' : '' ?>
        >

    <?php
}

function span_tag($text, $style = "")
{
    ?>

    <span style="<?= $style ?>"><?= $text ?></span>
    <?php
}