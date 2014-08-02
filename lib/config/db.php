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
    'wforms_form_field' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'form_id' => array('int', 11),
        'name' => array('varchar', 255),
        'hint' => array('varchar', 255),
        'type' => array('enum', "'text','textarea','checkbox','radio','select','file'", 'null' => 0, 'default' => 'text'),
        'required' => array('tinyint', 1, 'null' => 0, 'default' => '1'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
