<?php

class wformsBackendAction extends waViewAction {

    public function execute() {
        
        $model = new wformsFormModel();
        $forms = $model->getAll();

        $right_add = $this->getRights('add');
        $this->view->assign('right_add', $right_add);
        $this->view->assign('forms', $forms);
        $this->view->assign('lang', substr(wa()->getLocale(), 0, 2));
    }

}
