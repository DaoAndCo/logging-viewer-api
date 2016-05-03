<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Cake\Collection\Collection;
use Cake\I18n\Time;


/**
 * PagesControllerTest class
 */
class LogsControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.Users',
        'app.Logs',
    ];

    public function setup() {

        $this->configRequest([
            'environment' => [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'ad40db557bf3dd377243348a24ed215702b1c596'
            ],
        ]);

        parent::setup();
    }

    public function testFindDefault() {

        $this->post('/find', ['table' => 'logs']);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');

        $this->assertEquals("I'm a test", $result[0]->message);
        $this->assertEquals(1, $result[0]->id);
        $this->assertEquals("I'm a warning test", $result[1]->message);
        $this->assertEquals(2, $result[1]->id);
    }

    public function testFindLimit() {

        $this->post('/find', ['table' => 'logs', 'limit' => 1]);

        $this->assertResponseOk();
        $this->assertCount(1, $this->viewVariable('logs'));
    }

    public function testFindOrderAsc() {

        $this->post('/find', ['table' => 'logs', 'limit' => 1, 'order' => ['level' => 'ASC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('alert', $result[0]->level);
    }

    public function testFindOrderDesc() {

        $this->post('/find', ['table' => 'logs', 'limit' => 1, 'order' => ['level' => 'DESC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('warning', $result[0]->level);
    }

    public function testFindOrderCombine() {

        $this->post('/find', ['table' => 'logs', 'limit' => 1, 'order' => ['level' => 'DESC', 'scope' => 'ASC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('warning', $result[0]->level);
        $this->assertNull($result[0]->scope);
    }

    public function testFindStartDate() {

        $this->post('/find', ['table' => 'logs', 'start' => '2016-05-03', 'limit' => 1, 'order' => ['created' => 'ASC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals(new Time('2016-05-03'), $result[0]->created);
    }

    public function testFindEndDate() {

        $this->post('/find', ['table' => 'logs', 'end' => '2016-05-02', 'limit' => 1, 'order' => ['created' => 'DESC']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals(new Time('2016-05-02'), $result[0]->created);
    }

    public function testFindUserId() {

        $this->post('/find', ['table' => 'logs', 'users' => 2, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals(2, $result[0]->user_id);
    }

    public function testFindUserNull() {

        $this->post('/find', ['table' => 'logs', 'users' => null, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->user_id);
    }

    public function testFindUserFalse() {

        $this->post('/find', ['table' => 'logs', 'users' => false, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->user_id);
    }

    public function testFindUserArray() {

        $this->post('/find', ['table' => 'logs', 'users' => [2,3]]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $col = new Collection($result);

        $this->assertEquals([2,3], array_values(array_unique($col->extract('user_id')->toArray())));
    }

    public function testFindScopeString() {

        $this->post('/find', ['table' => 'logs', 'scopes' => 'plop', 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('plop', $result[0]->scope);
    }

    public function testFindScopeNull() {

        $this->post('/find', ['table' => 'logs', 'scopes' => null, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->scope);
    }

    public function testFindScopeFalse() {

        $this->post('/find', ['table' => 'logs', 'scopes' => false, 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertNull($result[0]->scope);
    }

    public function testFindScopeArray() {

        $this->post('/find', ['table' => 'logs', 'scopes' => ['test', 'tele']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $col = new Collection($result);

        $this->assertEquals(['test', 'tele'], array_values(array_unique($col->extract('scope')->toArray())));
    }

    public function testFindLevelString() {

        $this->post('/find', ['table' => 'logs', 'levels' => 'warning', 'limit' => 1]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $this->assertEquals('warning', $result[0]->level);
    }

    public function testFindLevelArray() {

        $this->post('/find', ['table' => 'logs', 'levels' => ['warning', 'error']]);

        $this->assertResponseOk();

        $result = $this->viewVariable('logs');
        $col = new Collection($result);

        $this->assertEquals(['warning', 'error'], array_values(array_unique($col->extract('level')->toArray())));
    }

    public function testFindContextStringValue() {

        $this->post('/find', ['table' => 'logs', 'context' => ['ip'=>'55.44.11.22']]);

        $this->assertResponseOk();

        $col = new Collection($this->viewVariable('logs'));

        $this->assertEquals(['55.44.11.22/Pages', '55.44.11.22/Blog'], array_values(array_unique($col->extract('message')->toArray())));
    }

    public function testFindContextMultiStringValue() {

        $this->post('/find', ['table' => 'logs', 'context' => ['ip'=>'55.44.11.22', 'name' => 'Pages']]);

        $this->assertResponseOk();

        $col = new Collection($this->viewVariable('logs'));

        $this->assertEquals(['55.44.11.22/Pages'], array_values(array_unique($col->extract('message')->toArray())));
    }
}
