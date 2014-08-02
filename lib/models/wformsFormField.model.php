<?php

class wformsFormFieldModel extends waModel {

    protected $table = 'wforms_form_field';

    public function getFormFields($form_id) {
        return $this->getByField('form_id', $form_id, true);
    }

}
