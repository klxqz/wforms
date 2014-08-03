<?php

class wformsBackendDeleteFieldController extends waJsonController {

    public function execute() {
        try {
            $model = new wformsFieldModel();
            $field_values_model = new wformsFieldValuesModel();
            $id = waRequest::post('id');
            $model->deleteById($id);
            $field_values_model->deleteByField('field_id', $id);
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

}
