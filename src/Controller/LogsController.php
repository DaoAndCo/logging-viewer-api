<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class LogsController extends AppController {

    public function isAuthorized($user = null) {
        return true;
    }

    /**
     * [find description]
     * filters :
            * (int) limit : limit number results
            * (array) order : order the result set [field => "ASC|DESC", ...]
            * (string) start : starting date ("2016-01-01")
            * (string) end : end date ("2016-02-01")
            * (int|null|false|array) users : filter by user_id
            * (string|null|false|array) scopes : filter by scope
            * (string|array) levels : filter by level
     */
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
