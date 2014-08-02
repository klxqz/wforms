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
                $this->response['form_id'] = $id;
            }
            $this->response['message'] = 'Сохранено';
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

}
