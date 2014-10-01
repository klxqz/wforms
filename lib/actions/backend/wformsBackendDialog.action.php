<?php

class wformsBackendDialogAction extends waViewAction {

    public function execute() {
        $user = $this->getUser();
        $email = $user->get('email', 'default');
        $this->view->assign('email', $email);
    }

}
