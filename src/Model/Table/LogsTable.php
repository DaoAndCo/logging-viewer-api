<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Database\Schema\Table as Schema;
use Cake\I18n\Time;

class LogsTable extends Table {

    protected function _initializeSchema(Schema $schema) {
        $schema->columnType('context', 'json');
        return $schema;
    }

    public function findFilter(Query $query, array $options) {

        $filters = (isset($options['filters'])) ? $options['filters'] : [];

        if ( isset($filters['limit']) )
            $query->limit($filters['limit']);

        if ( isset($filters['order']) )
            $query->order($filters['order']);

        if ( isset($filters['start']) && ( $start = new Time($filters['start']) ) ) {
            $query->where(function ($exp, $q) use ($start) {
                return $exp->gte('created', $start);
            });
        }

        if ( isset($filters['end']) && ( $end = new Time($filters['end']) ) ) {
            $query->where(function ($exp, $q) use ($end) {
                return $exp->lte('created', $end);
            });
        }

        if ( array_key_exists('users', $filters) ) {
            if ( $filters['users'] )
                $query->where(['user_id IN' => $filters['users']]);
            else
                $query->where(function ($exp, $q) {
                    return $exp->isNull('user_id');
                });
        }

        if ( array_key_exists('scopes', $filters) ) {
            if ( $filters['scopes'] )
                $query->where(['scope IN' => $filters['scopes']]);
            else
                $query->where(function ($exp, $q) {
                    return $exp->isNull('scope');
                });
        }

        if ( isset($filters['levels']) )
            $query->where(['level IN' => $filters['levels']]);

        return $query;
    }
}