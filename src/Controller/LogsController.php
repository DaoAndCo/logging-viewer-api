<?php
namespace App\Controller;

use App\Controller\AppController;

class LogsController extends AppController {

    public function isAuthorized($user = null) {
        return true;
    }

    public function find() {
        $this->request->allowMethod(['post']);

        $this->set('test', 'OK');
        $this->set('user', env('PHP_AUTH_USER'));
    }
}
