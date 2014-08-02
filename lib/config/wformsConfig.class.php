<?php

class wformsConfig extends waAppConfig {

    protected $field_types = null;

    public function getFieldTypes($check_rights = false) {
        if ($this->field_types === null) {
            $this->field_types = include(dirname(__FILE__) . '/fieldTypes.php');
        }

        return $this->field_types;
    }

}
