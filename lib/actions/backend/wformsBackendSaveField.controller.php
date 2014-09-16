<?php

class wformsBackendSaveFieldController extends waJsonController {

    public function execute() {
        try {
            $model = new wformsFieldModel();
            $model_values = new wformsFieldValuesModel();
            $data = waRequest::post('field');

            if (empty($data['form_id'])) {
                throw new waException('Форма не определена');
            }
            if (empty($data['id'])) {
                $id = $model->insert($data);
                $data['id'] = $id;
            } else {
                $model->updateById($data['id'], $data);
            }
            if (!empty($data['values'])) {
                $ids = $data['values']['id'];
                $names = $data['values']['name'];
                $values = $data['values']['value'];
                $_values = array();

                $model_values->deleteNotExists($data['id'], $ids);

                foreach ($names as $index => $value_name) {
                    $value = array(
                        'id' => $ids[$index],
                        'field_id' => $data['id'],
                        'form_id' => $data['form_id'],
                        'name' => $value_name,
                        'value' => $values[$index],
                        'sort' => (int) $index,
                    );

                    if (empty($value['id'])) {
                        $id = $model_values->insert($value);
                        $value['id'] = $id;
                    } else {
                        $model_values->updateById($value['id'], $value);
                    }
                    $_values[] = $value;
                }
                $data['values'] = $_values;
            }

            $this->response['field'] = $this->prepare($data);
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
