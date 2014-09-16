<?php

class wformsBackendFormsAction extends waViewAction {

    public function execute() {

        $form_model = new wformsFormModel();
        $forms = $form_model->getAll();

        $this->view->assign('forms', $forms);

    }

}
