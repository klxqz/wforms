<?php

return array(
    'wforms_form' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'title' => array('varchar', 255, 'null' => 0, 'default' => ''),
        'enabled' => array('tinyint', 1, 'null' => 0, 'default' => '1'),
        'description' => array('text', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wforms_field' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'form_id' => array('int', 11),
        'name' => array('varchar', 255),
        'hint' => array('varchar', 255),
        'default_value' => array('varchar', 255),
        'type' => array('enum', "'text','textarea','checkbox','checkboxes','radio','select','select_multiple','file'", 'null' => 0, 'default' => 'text'),
        'required' => array('tinyint', 1, 'null' => 0, 'default' => '1'),
        'sort' => array('int', 11),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wforms_field_values' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'field_id' => array('int', 11),
        'form_id' => array('int', 11),
        'name' => array('varchar', 255),
        'value' => array('tinyint', 1, 'null' => 0, 'default' => '1'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
