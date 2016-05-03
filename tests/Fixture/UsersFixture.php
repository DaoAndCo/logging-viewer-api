<?php
namespace App\Test\Fixture;
use Cake\TestSuite\Fixture\TestFixture;
/**
 * ContactDatasFixture
 *
 */
class UsersFixture extends TestFixture
{
    public $table = 'log_users';

    public $fields = [
          'id' => ['type' => 'integer'],
          'username' => ['type' => 'string', 'length' => 50, 'null' => false],
          'api_key' => ['type' => 'string', 'length' => 255, 'null' => false],
          '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
          ]
      ];

    public $records = [
        [
            'username' => 'admin',
            'api_key' => '$2y$10$/6lY41Hckc.y0BcRED6APemZuIevv4jfKXXJFzmnSFKRS.dUTpAjC',
        ],
    ];
}