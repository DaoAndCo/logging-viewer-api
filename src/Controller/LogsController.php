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
     * Find logs with filters (POST)
     * $_POST:
        * table : table name
        * filters :
            * (int) limit : limit number results
            * (array) order : order the result set [field => "ASC|DESC", ...]
            * (string) start : starting date ("2016-01-01")
            * (string) end : end date ("2016-02-01")
            * (int|null|false|array) users : filter by user_id
            * (string|null|false|array) scopes : filter by scope
            * (string|array) levels : filter by level
            * (array) context : filter by context (['key'=>'val'])
     */
    public function find() {
        $this->request->allowMethod(['post']);

        if ( ! isset($this->request->data['table']) )
            throw new NotFoundException(__('Table required'));

        $this->Logs = TableRegistry::get('Logs', ['table' => $this->request->data['table']]);

        $result = $this->Logs->find('filter', ['filters' => $this->request->data]);

        if ( ! empty($this->request->data['context']) )
            $result = $this->Logs->filterContext($result, $this->request->data['context']);

        $this->set([
            'logs' => $result->toArray(),
        ]);
    }
}
