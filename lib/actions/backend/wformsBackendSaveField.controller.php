<?php

class wformsBackendSaveFieldController extends waJsonController {

    public function execute() {
        try {
            $model = new wformsFormFieldModel();
            $data = waRequest::post('field');
            //print_r($data);
            $model->insert($data);

            
            $this->response['field'] = $this->prepare($data);
            //$this->response['message'] = 'Сохранено';
            //$this->response['form_id'] = $id;
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

    protected function prepare($field) {
        $field_types = $this->getConfig()->getFieldTypes();
        $field['required'] = $field['required'] ? _w('Yes') : _w('No');
        $type = $field['type'];
        $field['type'] = $field_types[$type];
        return $field;
    }

}
