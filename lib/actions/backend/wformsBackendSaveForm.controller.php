<?php

class wformsBackendSaveFormController extends waJsonController {

    public function execute() {
        try {
            $model = new wformsFormModel();
            $data = waRequest::post('form');
            if (!empty($data['id'])) {
                $form = $model->getById($data['id']);
                if (!$form) {
                    throw new waException('Форма #' . $data['id'] . ' не найдена');
                }
                $model->updateById($data['id'], $data);
            } else {
                $id = $model->insert($data);
                $data['id'] = $id;
            }
            $data['description'] = substr(strip_tags($data['description']), 0, 400);
            $this->response['form'] = $data;
            $this->response['message'] = 'Сохранено';
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

}
