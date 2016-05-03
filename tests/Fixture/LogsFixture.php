<?php
namespace App\Test\Fixture;
use Cake\TestSuite\Fixture\TestFixture;
/**
 * ContactDatasFixture
 *
 */
class LogsFixture extends TestFixture
{
    public $table = 'logs';

    public $fields = [
          'id' => ['type' => 'integer'],
          'created' => ['type' => 'datetime', 'null' => true],
          'level' => ['type' => 'string', 'length' => 50, 'null' => false],
          'scope' => ['type' => 'string', 'length' => 50, 'null' => true],
          'user_id' => ['type' => 'integer', 'null' => true],
          'message' => ['type' => 'text', 'null' => false],
          'context' => ['type' => 'text', 'null' => false],
          '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
          ]
      ];
    public $records = [
        [
            'created' => '2016-05-01 00:00:00',
            'level'   => 'info',
            'scope'   => 'test',
            'user_id' => 1,
            'message' => "I'm a test",
            'context' => '',
        ],
        [
            'created' => '2016-05-02 00:00:00',
            'level'   => 'warning',
            'scope'   => 'test',
            'user_id' => null,
            'message' => "I'm a warning test",
            'context' => '',
        ],
        [
            'created' => '2016-05-03 00:00:00',
            'level'   => 'info',
            'scope'   => null,
            'user_id' => 2,
            'message' => "I'm a message",
            'context' => '',
        ],
        [
            'created' => '2016-05-04 00:00:00',
            'level'   => 'info',
            'scope'   => 'tele',
            'user_id' => 1,
            'message' => "I'm a message",
            'context' => '',
        ],
        [
            'created' => '2016-05-02 00:00:00',
            'level'   => 'alert',
            'scope'   => null,
            'user_id' => 1,
            'message' => "I'm a message",
            'context' => '',
        ],
        [
            'created' => '2016-05-02 00:00:00',
            'level'   => 'warning',
            'scope'   => null,
            'user_id' => 1,
            'message' => "I'm a message",
            'context' => '',
        ],
        [
            'created' => '2016-05-02 00:00:00',
            'level'   => 'error',
            'scope'   => 'plop',
            'user_id' => 3,
            'message' => "I'm a message",
            'context' => '',
        ],
        [
            'created' => '2016-05-02 00:00:00',
            'level'   => 'error',
            'scope'   => 'plop',
            'user_id' => 4,
            'message' => "I'm a message",
            'context' => '',
        ],
        [
            'created' => '2016-05-03 00:00:00',
            'level'   => 'info',
            'scope'   => 'chicken',
            'user_id' => 7,
            'message' => "55.44.11.22/Pages",
            'context' => '{"ip":"55.44.11.22","name":"Pages"}',
        ],
        [
            'created' => '2016-05-03 00:00:00',
            'level'   => 'info',
            'scope'   => 'chicken',
            'user_id' => 7,
            'message' => "55.44.11.22/Blog",
            'context' => '{"ip":"55.44.11.22","name":"Blog"}',
        ],
        [
            'created' => '2016-05-03 00:00:00',
            'level'   => 'info',
            'scope'   => 'chicken',
            'user_id' => 8,
            'message' => "1.1.1.1/Pages",
            'context' => '{"ip":"1.1.1.1","name":"Pages"}',
        ],
    ];
}