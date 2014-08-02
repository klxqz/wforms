<?php

class wformsBackendEditFieldController extends waJsonController {

    public function execute() {
        try {
            $model = new wformsFormFieldModel();
            $id = waRequest::post('id');            
            $field = $model->getById($id);
            $field['field_types'] = $this->getConfig()->getFieldTypes();
            $this->response['field'] = $field;
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }
}
