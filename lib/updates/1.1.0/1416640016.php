<?php

$model = new waModel();
try {
    $sql = 'SELECT `use_captcha` FROM `wforms_form` WHERE 0';
    $model->query($sql);
} catch (waDbException $ex) {
    $sql = "ALTER TABLE `wforms_form` ADD `use_captcha` TINYINT( 1 ) NOT NULL";
    $model->query($sql);
}