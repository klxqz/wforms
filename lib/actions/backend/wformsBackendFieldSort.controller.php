<?php

class wformsBackendFieldSortController extends waJsonController {

    public function execute() {
        $model = new wformsFieldModel();
        $id = waRequest::post('field_id', 0, waRequest::TYPE_INT);
        $after_id = waRequest::post('after_id', 0, waRequest::TYPE_INT);

        try {
            $model->move($id, $after_id);
        } catch (waException $e) {
            $this->setError($e->getMessage());
        }
    }

}
