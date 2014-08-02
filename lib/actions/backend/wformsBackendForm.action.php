<?php

class wformsBackendFormAction extends waViewAction {


    public function execute() {
        $id = waRequest::get('id');
        $form_model = new wformsFormModel();
        $form_field_model = new wformsFormFieldModel();
        $form = $form_model->getById($id);
        if (!$form) {
            throw new waException('Форма #' . $data['id'] . ' не найдена');
        }
        $fields = $form_field_model->getFormFields($id);
        
        $field_types = $this->getConfig()->getFieldTypes();
        
        $this->view->assign('form', $form);
        $this->view->assign('fields', $fields);
        $this->view->assign('field_types', $field_types);
    }

}
