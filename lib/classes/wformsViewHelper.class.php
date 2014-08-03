<?php

class wformsViewHelper extends waAppViewHelper {

    public function getHtmlForm($form_id) {
        $form_model = new wformsFormModel();
        $field_model = new wformsFieldModel();
        $field_values_model = new wformsFieldValuesModel();
        $form = $form_model->getById($form_id);
        if (!$form) {
            throw new waException('Форма #' . $form_id . ' не найдена');
        }

        if (!$form['enabled']) {
            return false;
        }


        $fields = $field_model->getFormFields($form_id);
        foreach ($fields as &$field) {
            $field['values'] = $field_values_model->getByField('field_id', $field['id'], true);
        }
        unset($field);

        $view = wa()->getView();
        $view->assign('form', $form);
        $view->assign('fields', $fields);
        $template_path = wa()->getAppPath('templates/actions/frontend/Form.html', 'wforms');
        $html = $view->fetch($template_path);
        return $html;
    }

}
