<?php
namespace App\Test\Fixture;
use Cake\TestSuite\Fixture\TestFixture;
/**
 * ContactDatasFixture
 *
 */
class UserFixture extends TestFixture
{
    public $table = 'users';

    public $fields = [
          'id' => ['type' => 'integer'],
          'firstname' => ['type' => 'string', 'length' => 50, 'null' => false],
          'lastname' => ['type' => 'string', 'length' => 50, 'null' => false],
          '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
          ]
      ];

    public $records = [
        [
            'id' => 1,
            'firstname' => 'Richard',
            'lastname' => 'Coeur de chien',
        ],
        [
            'id' => 2,
            'firstname' => 'Paul',
            'lastname' => 'Le thon',
        ],
        [
            'id' => 3,
            'firstname' => 'Jean Claude',
            'lastname' => 'Van Dome',
        ],
        [
            'id' => 4,
            'firstname' => 'Mat',
            'lastname' => 'Dumon',
        ],
        [
            'id' => 7,
            'firstname' => 'Arnold',
            'lastname' => 'Sweppeszeneguer',
        ],
        [
            'id' => 8,
            'firstname' => 'Ilariz',
            'lastname' => 'Blington',
        ],
    ];
}