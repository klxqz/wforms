<?php

class wformsBackendFormAction extends waViewAction {

    public function execute() {
        $id = waRequest::get('id');
        $form_model = new wformsFormModel();
        $field_model = new wformsFieldModel();
        $field_values_model = new wformsFieldValuesModel();
        $form = $form_model->getById($id);
        if (!$form) {
            throw new waException('Форма #' . $id . ' не найдена');
        }
        $fields = $field_model->getFormFields($id);
        foreach ($fields as &$field) {
            $field['values'] = $field_values_model->getByField('field_id', $field['id'], true);
        }
        unset($field);

        $field_types = $this->getConfig()->getFieldTypes();

        $this->view->assign('form', $form);
        $this->view->assign('fields', $fields);
        $this->view->assign('field_types', $field_types);
    }

}
