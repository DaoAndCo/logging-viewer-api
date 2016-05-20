<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class LogsController extends AppController {

    public function isAuthorized($user = null) {
        return true;
    }

    /**
     * Find logs with filters (POST)
     * $_POST:
        * config : config name (see config/log.php)
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

        if ( ! isset($this->request->data['config']) || ( ! $config = Configure::read("LoggingViewer.{$this->request->data['config']}") ) )
            throw new NotFoundException(__('Config required'));

        $this->Logs = TableRegistry::get('Logs', ['table' => $config['table']]);

        $options = [
            'config'  => $config,
            'filters' => $this->request->data,
        ];
        $result = $this->Logs->find('filter', $options);

        if ( ! empty($this->request->data['context']) )
            $result = $this->Logs->filterContext($result, $this->request->data['context']);

        $this->set([
            'logs' => $result->toArray(),
        ]);
    }
}
