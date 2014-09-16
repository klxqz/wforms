<?php

class wformsViewHelper extends waAppViewHelper {

    public function sendForm($form_id) {
        $form_model = new wformsFormModel();
        $field_model = new wformsFieldModel();
        $field_values_model = new wformsFieldValuesModel();
        $form = $form_model->getById($form_id);
        if (!$form) {
            throw new waException('Форма #' . $form_id . ' не найдена');
        }

        $fields = $field_model->getFormFields($form_id);
        
            foreach ($fields as $field) {
                if ($field['type'] != 'file') {
                    $value = waRequest::post('field_' . $field['id']);
                    if($field['required'] && empty($value)) {
                        throw new waException('Ошибка отправки формы. Заполните обязательные поля');
                    }
                    if (is_array($value)) {
                        $data[$field['name']] = implode(', ', $value);
                    } else {
                        $data[$field['name']] = $value;
                    }
                }
            }
        


        $view = wa()->getView();
        $view->assign('data', $data);
        $template_path = wa()->getAppPath('templates/actions/frontend/Message.html', 'wforms');
        $html = $view->fetch($template_path);

        $message = new waMailMessage($form['title'], $html);
        $message->setTo($form['to']);
        foreach ($fields as $field) {
            if ($field['type'] == 'file') {
                $file = waRequest::file('field_' . $field['id']);
                if ($file->uploaded()) {
                    $message->addAttachment($file->tmp_name, $field['name'] . '.' . $file->extension);
                }
            }
        }

        if ($form['from']) {
            $message->setFrom($from, $form['from']);
        }


        if ($message->send()) {
            return true;
        }
        return false;
    }

    public function getHtmlForm($form_id) {
        $post_wform_id = waRequest::post('wform_id');
        $error = '';
        try{
            if ($post_wform_id == $form_id) {
                $this->sendForm($form_id);
            }
        } catch (waException $e){
            $error = $e->getMessage();   
        }

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
        $form['fields'] = $fields;

        $view = wa()->getView();
        $view->assign('form', $form);
        $view->assign('error', $error);
        $template_path = wa()->getAppPath('templates/actions/frontend/Form.html', 'wforms');
        $html = $view->fetch($template_path);
        return $html;
    }

    public function getFormData($form_id) {
        $form_model = new wformsFormModel();
        $field_model = new wformsFieldModel();
        $field_values_model = new wformsFieldValuesModel();
        $form = $form_model->getById($form_id);
        if (!$form) {
            throw new waException('Форма #' . $form_id . ' не найдена');
        }


        $fields = $field_model->getFormFields($form_id);
        foreach ($fields as &$field) {
            $field['values'] = $field_values_model->getByField('field_id', $field['id'], true);
        }
        unset($field);
        $form['fields'] = $fields;

        return $form;
    }

}
