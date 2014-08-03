<?php

class wformsBackendEditFieldController extends waJsonController {

    public function execute() {
        try {
            $model = new wformsFieldModel();
            $field_values_model = new wformsFieldValuesModel();
            $id = waRequest::post('id');            
            $field = $model->getById($id);
            $field['values'] = $field_values_model->getByField('field_id', $id, true);
            $field['field_types'] = $this->getConfig()->getFieldTypes();
            $this->response['field'] = $field;
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }
}
