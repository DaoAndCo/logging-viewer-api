<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Database\Schema\Table as Schema;

class LogsTable extends Table {

    protected function _initializeSchema(Schema $schema) {
        $schema->columnType('context', 'json');
        return $schema;
    }

    public function findFilter(Query $query, array $options) {

        $filters = (isset($options['filters'])) ? $options['filters'] : [];

        if ( isset($filters['limit']) )
            $query->limit($filters['limit']);

        return $query;
    }
}