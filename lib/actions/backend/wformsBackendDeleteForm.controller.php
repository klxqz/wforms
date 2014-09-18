<?php

class wformsBackendDeleteFormController extends waJsonController {

    public function execute() {
        try {
            $id = waRequest::post('id', 0, waRequest::TYPE_INT);
            $form = new wformsForm($id);
            $form->delete();
            
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

}
