<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Database\Schema\Table as Schema;
use Cake\I18n\Time;
use Cake\Collection\Collection;

class LogsTable extends Table {

  protected function _initializeSchema(Schema $schema) {
    $schema->columnType('context', 'json');
    return $schema;
  }

  /**
   * Find logs with filters
   * @param  Query  $query
   * @param  array  $options with key filters
   * @return Query
   */
  public function findFilter(Query $query, array $options) {

      $query->select($this);

      if ( isset($options['config']['users']) ) {
        $configUsers = $options['config']['users'];

        if ( $configUsers['firstname'] )
          $query->select(["user.{$configUsers['firstname']}"]);

        if ( $configUsers['lastname'] )
          $query->select(["user.{$configUsers['lastname']}"]);

        $query->join([
          'user' => [
            'table' => $configUsers['table'],
            'type' => 'LEFT',
            'conditions' => "user.{$configUsers['id']} = user_id",
          ],
        ]);
      }

      $filters = (isset($options['filters'])) ? $options['filters'] : [];

      if ( isset($filters['limit']) )
          $query->limit($filters['limit']);

      if ( isset($filters['order']) ) {
        $order = [];

        foreach ( $filters['order'] as $field => $direction ) {
          if ( strpos($field, '.') )
            $order[$field] = $direction;
          else
            $order['Logs.' . $field] = $direction;
        }

        $query->order($order);
      }
      else
        $query->order(['created' => 'desc']);

      if ( isset($filters['start']) && $filters['start'] && ( $start = new Time($filters['start']) ) ) {
          $query->where(function ($exp, $q) use ($start) {
              return $exp->gte('created', $start);
          });
      }

      if ( isset($filters['end']) && $filters['end'] && ( $end = new Time($filters['end']) ) ) {
          $query->where(function ($exp, $q) use ($end) {
              return $exp->lte('created', $end);
          });
      }

      if ( array_key_exists('users', $filters) ) {
        if ( is_array($filters['users']) || $filters['users'] ) {
          if ( $filters['users'] )
            $query->where(['user_id IN' => $filters['users']]);
        } else {
          $query->where(function ($exp, $q) {
            return $exp->isNull('user_id');
          });
        }
      }

      if ( array_key_exists('scopes', $filters) ) {
        if ( is_array($filters['scopes']) || $filters['scopes'] ) {
          if ( $filters['scopes'] )
            $query->where(['scope IN' => $filters['scopes']]);
        } else {
          $query->where(function ($exp, $q) {
            return $exp->isNull('scope');
          });
        }
      }

      if ( isset($filters['levels']) && $filters['levels'] )
        $query->where(['level IN' => $filters['levels']]);

      if ( isset($filters['message']) && $filters['message'] )
        $query->where(['message LIKE' => "%{$filters['message']}%"]);

      return $query;
  }

  /**
   * Filter by context (after findFilter)
   * @param  Query  $query
   * @param  array $context ['key'=>'val']
   * @return Collection
   */
  public function filterContext(Query $query, $context) {
    $col = new Collection($query);

    foreach ( $context as $field => $value ) {
      $col = $col->filter(function ($log, $key) use($field, $value) {
        return ( isset($log->context[$field]) ) && ( $log->context[$field] === $value);
      });
    }

    return $col;
  }
}