<?php

class wformsForm {

    protected $data = array();
    protected $model;

    public function __construct($data = array()) {
        $this->model = new wformsFormModel();
        if (is_array($data)) {
            $this->data = $data;
        } elseif ($data) {
            $this->data = $this->model->getById($data);
        }
    }

    public function delete() {
        if (!empty($this->data['id'])) {
            $id = $this->data['id'];

            $field_model = new wformsFieldModel();
            $field_values_model = new wformsFieldValuesModel();
            $field_model->deleteByField('form_id', $id);
            $field_values_model->deleteByField('form_id', $id);

            $this->model->deleteById($id);
        }
    }

}
