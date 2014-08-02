<?php

class wformsBackendDeleteFieldController extends waJsonController {

    public function execute() {
        try {
            $model = new wformsFormFieldModel();
            $id = waRequest::post('id');
            $model->deleteById($id);
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

}
