<?php

class UsersController extends AppController {
    public function beforeFilter() {
        $this->Auth->allow(array('login', 'logout'));
        return parent::beforeFilter();
    }

    public function login() {
        if ($this->Saml->isAuthenticated()) {
            return $this->redirect($this->Auth->redirect());
        } else {
            $this->Saml->login();
        }
    }

    public function logout() {
        $this->Auth->logout();
    }
}
