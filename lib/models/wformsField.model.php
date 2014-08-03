<?php

class wformsFieldModel extends wformsSortableModel {

    protected $table = 'wforms_field';

    public function getFormFields($form_id) {
        return $this->getByField('form_id', $form_id, true);
    }

}
