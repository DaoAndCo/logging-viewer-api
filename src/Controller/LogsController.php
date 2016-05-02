<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class LogsController extends AppController {

    public function isAuthorized($user = null) {
        return true;
    }

    public function find() {
        $this->request->allowMethod(['post']);

        if ( ! isset($this->request->data['table']) )
            throw new NotFoundException(__('Table required'));

        $this->Logs = TableRegistry::get('Logs', ['table' => $this->request->data['table']]);

        $this->set([
            'logs' => $this->Logs->find('filter', ['filters' => $this->request->data])->toArray(),
        ]);
    }
}
